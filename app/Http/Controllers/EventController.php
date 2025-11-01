<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display welcome page with upcoming events
     */
    public function index(): View
    {
        $events = Event::getApprovedUpcomingEvents();
        return view('welcome', compact('events'));
    }

    /**
     * Display event detail page
     */
    public function show($eventId): View
    {
        $event = Event::with(['tickets', 'payments', 'creator.user', 'reviews'])
            ->findOrFail($eventId);
        
        return view('events.show', compact('event'));
    }
}

