<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminEventController extends Controller
{
    public function index(Request $request): View
    {
        $query = Event::with(['user', 'category']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('city', 'like', '%' . $request->q . '%');
            });
        }
        $events = $query->latest()->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function show(Event $event): View
    {
        $event->load(['user', 'category', 'ticketTypes']);
        return view('admin.events.show', compact('event'));
    }

    public function approve(Event $event): RedirectResponse
    {
        $event->update(['status' => 'published']);
        return redirect()->route('admin.events.index')->with('success', 'Evento aprobado y publicado.');
    }

    public function reject(Event $event): RedirectResponse
    {
        $event->update(['status' => 'draft']);
        return redirect()->route('admin.events.index')->with('success', 'Evento rechazado (vuelve a borrador).');
    }

    public function edit(Event $event): View
    {
        $event->load(['user', 'category', 'ticketTypes']);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,pending_approval,published,cancelled',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'city' => 'required|string|max:100',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        $event->update($validated);
        return redirect()->route('admin.events.show', $event)->with('success', 'Evento actualizado.');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->update(['status' => 'cancelled']);
        return redirect()->route('admin.events.index')->with('success', 'Evento cancelado.');
    }
}
