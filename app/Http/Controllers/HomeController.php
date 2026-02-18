<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
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
            $date = $request->date;
            $query->whereDate('start_date', $date);
        }
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->q . '%')
                    ->orWhere('description', 'like', '%' . $request->q . '%');
            });
        }

        $events = $query->orderBy('start_date')->paginate(12);
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('home', compact('events', 'categories'));
    }

    public function show(Event $event): View
    {
        if ($event->status !== 'published') {
            abort(404);
        }
        $event->load(['category', 'ticketTypes']);
        return view('events.show', compact('event'));
    }
}
