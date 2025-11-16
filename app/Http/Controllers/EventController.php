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
        // Ambil event yang sudah disetujui dan yang akan datang (berdasarkan start_date)
        $events = Event::getApprovedUpcomingEvents(); // Pastikan ini sudah mencakup start_date dan end_date
        return view('welcome', compact('events'));
    }

    /**
     * Display event detail page
     */
    public function show($eventId): View
    {
        // Ambil detail event berdasarkan eventId, dengan relasi yang lengkap
        $event = Event::with(['tickets', 'payments', 'creator.user', 'reviews'])
            ->findOrFail($eventId);

        // Pastikan start_date dan end_date ter-handle dengan benar di model (seperti yang sudah dibahas)
        return view('events.show', compact('event'));
    }
}
