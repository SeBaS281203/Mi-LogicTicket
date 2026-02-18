<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Listado público de eventos (publicados y próximos).
     * Acepta: category (id), category_slug (redirige a category), city, date, q (búsqueda).
     */
    public function index(Request $request): View|RedirectResponse
    {
        // Redirección canónica: category_slug → category (id) para que la URL y filtros sean consistentes
        if ($request->filled('category_slug')) {
            $category = Category::where('slug', $request->category_slug)->where('is_active', true)->first();
            if ($category) {
                $params = $request->only(['q', 'city', 'date']);
                $params['category'] = $category->id;
                return redirect()->route('events.index', $params);
            }
            // Slug inexistente: quitar category_slug y seguir sin filtro de categoría
            $params = $request->only(['q', 'city', 'date', 'category']);
            return redirect()->route('events.index', $params);
        }

        $query = Event::query()
            ->published()
            ->upcoming()
            ->with(['category', 'ticketTypes']);

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }
        if ($request->filled('date')) {
            $query->whereDate('start_date', $request->date);
        }
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%' . $term . '%')
                    ->orWhere('description', 'like', '%' . $term . '%');
            });
        }

        $events = $query->orderBy('start_date')->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        // Carrusel "Los más populares": siempre con eventos destacados (sin filtros de búsqueda)
        $featuredEvents = Event::query()
            ->published()
            ->upcoming()
            ->with(['category', 'ticketTypes'])
            ->orderBy('start_date')
            ->take(12)
            ->get();
        // Si no hay destacados, usar los primeros de la lista actual para que debajo del hero siempre haya algo
        if ($featuredEvents->isEmpty() && $events->count() > 0) {
            $featuredEvents = $events->take(12);
        }
        $banners = Banner::active()->orderBy('sort_order')->get();

        return view('events.index', compact('events', 'categories', 'featuredEvents', 'banners'));
    }

    /**
     * Detalle público del evento por slug.
     */
    public function show(string $slug): View|RedirectResponse
    {
        $event = Event::where('slug', $slug)
            ->with(['category', 'ticketTypes'])
            ->firstOrFail();

        if ($event->status !== 'published') {
            abort(404);
        }

        return view('events.show', compact('event'));
    }

    /**
     * Formulario de creación (solo organizadores).
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('events.create', compact('categories'));
    }

    /**
     * Guardar nuevo evento (solo organizadores).
     */
    public function store(StoreEventRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']) . '-' . uniqid();
        $validated['country'] = $validated['country'] ?? 'Peru';
        $validated['ticket_price'] = $validated['ticket_price'] ?? 0;
        $validated['available_tickets'] = $validated['available_tickets'] ?? 0;
        $validated['status'] = $validated['status'] ?? 'draft';

        $event = Event::create($validated);

        if ($request->has('ticket_types')) {
            foreach ($request->ticket_types as $tt) {
                if (! empty($tt['name']) && isset($tt['price']) && isset($tt['quantity'])) {
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

        return redirect()->route('events.index')->with('success', 'Evento creado correctamente.');
    }

    /**
     * Formulario de edición (solo organizador dueño o admin).
     */
    public function edit(Event $event): View
    {
        $this->authorizeEvent($event);
        $event->load('ticketTypes');
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('events.edit', compact('event', 'categories'));
    }

    /**
     * Actualizar evento (solo organizador dueño o admin).
     */
    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);
        $validated = $request->validated();
        $validated['country'] = $validated['country'] ?? 'Peru';
        $validated['ticket_price'] = $validated['ticket_price'] ?? 0;
        $validated['available_tickets'] = $validated['available_tickets'] ?? $event->available_tickets;

        $event->update($validated);

        if ($request->has('ticket_types')) {
            $ids = [];
            foreach ($request->ticket_types as $tt) {
                if (! empty($tt['name']) && isset($tt['price']) && isset($tt['quantity'])) {
                    $data = [
                        'name' => $tt['name'],
                        'price' => $tt['price'],
                        'quantity' => (int) $tt['quantity'],
                        'description' => $tt['description'] ?? null,
                    ];
                    if (! empty($tt['id'])) {
                        $ticketType = TicketType::where('event_id', $event->id)->find($tt['id']);
                        if ($ticketType) {
                            $ticketType->update($data);
                            $ids[] = $ticketType->id;
                            continue;
                        }
                    }
                    $newType = TicketType::create(array_merge($data, ['event_id' => $event->id]));
                    $ids[] = $newType->id;
                }
            }
            $event->ticketTypes()->whereNotIn('id', $ids)->where('quantity_sold', 0)->delete();
        }

        return redirect()->route('events.index')->with('success', 'Evento actualizado correctamente.');
    }

    /**
     * Eliminar/cancelar evento (solo organizador dueño o admin).
     */
    public function destroy(Event $event): RedirectResponse
    {
        $this->authorizeEvent($event);
        $event->update(['status' => 'cancelled']);
        return redirect()->route('events.index')->with('success', 'Evento cancelado.');
    }

    private function authorizeEvent(Event $event): void
    {
        if ($event->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'No tienes permiso para modificar este evento.');
        }
    }
}
