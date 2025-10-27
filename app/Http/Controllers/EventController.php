<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function show($eventId): View
    {
        $event = Event::with(['tickets', 'payments', 'creator.user', 'reviews'])
            ->findOrFail($eventId);
        
        return view('events.show', compact('event'));
    }
}

