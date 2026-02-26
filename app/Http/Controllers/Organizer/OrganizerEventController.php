<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Services\ImageOptimizationService;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrganizerEventController extends Controller
{
    public function index(): View
    {
        $events = Auth::user()->events()->with('category')->latest()->paginate(10);
        return view('organizer.events.index', compact('events'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'in:draft,pending_approval',
            'event_image' => 'nullable|image|max:2048',
        ]);
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        if (($validated['status'] ?? '') === 'published') {
            $validated['status'] = 'pending_approval';
        }
        $validated['country'] = $validated['country'] ?? 'Peru';
        if ($request->hasFile('event_image')) {
            $validated['event_image'] = app(ImageOptimizationService::class)->storeOptimized($request->file('event_image'), 'events');
        }
        $event = Event::create($validated);

        if ($request->has('ticket_types')) {
            foreach ($request->ticket_types as $tt) {
                if (!empty($tt['name']) && isset($tt['price']) && isset($tt['quantity'])) {
                    TicketType::create([
                        'event_id' => $event->id,
                        'name' => $tt['name'],
                        'price' => $tt['price'],
                        'quantity' => (int) $tt['quantity'],
                        'description' => $tt['description'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('organizer.events.index')->with('success', 'Evento creado.');
    }

    public function edit(Event $event): View
    {
        $this->authorizeEvent($event);
        $event->load('ticketTypes');
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'city' => 'required|string|max:100',
            'country' => 'nullable|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'in:draft,pending_approval,cancelled',
            'event_image' => 'nullable|image|max:2048',
        ]);
        if (($validated['status'] ?? '') === 'published') {
            $validated['status'] = 'pending_approval';
        }
        $validated['country'] = $validated['country'] ?? 'Peru';
        if ($request->hasFile('event_image')) {
            if ($event->event_image) {
                Storage::disk('public')->delete($event->event_image);
            }
            $validated['event_image'] = app(ImageOptimizationService::class)->storeOptimized($request->file('event_image'), 'events');
        }
        $event->update($validated);

        if ($request->has('ticket_types')) {
            $ids = [];
            foreach ($request->ticket_types as $tt) {
                if (!empty($tt['name']) && isset($tt['price']) && isset($tt['quantity'])) {
                    $qty = (int) $tt['quantity'];
                    $data = [
                        'name' => $tt['name'],
                        'price' => $tt['price'],
                        'description' => $tt['description'] ?? null,
                    ];
                    if (!empty($tt['id'])) {
                        $ticketType = TicketType::where('event_id', $event->id)->find($tt['id']);
                        if ($ticketType) {
                            // No permitir reducir stock por debajo de lo ya vendido
                            $data['quantity'] = max($qty, $ticketType->quantity_sold);
                            $ticketType->update($data);
                            $ids[] = $ticketType->id;
                            continue;
                        }
                    }
                    $data['quantity'] = $qty;
                    $newType = TicketType::create(array_merge($data, ['event_id' => $event->id]));
                    $ids[] = $newType->id;
                }
            }
            $event->ticketTypes()->whereNotIn('id', $ids)->where('quantity_sold', 0)->delete();
        }

        return redirect()->route('organizer.events.index')->with('success', 'Evento actualizado.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);
        $event->update(['status' => 'cancelled']);
        return redirect()->route('organizer.events.index')->with('success', 'Evento cancelado.');
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
    }
}
