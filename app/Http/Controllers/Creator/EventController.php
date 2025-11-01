<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCreator;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
  /**
   * Store a newly created event for the authenticated creator.
   */
  public function store(Request $request)
  {
    $data = $request->validate([
      'event_name' => 'required|string|max:255',
      'event_description' => 'required|string',
      'event_location' => 'required|string|max:255',
      'event_date' => 'required|date',
      'event_capacity' => 'required|integer|min:1',
      'tickets' => 'required|array|min:1',
      'tickets.*.name' => 'required|string|max:255',
      'tickets.*.price' => 'required|numeric|min:0',
    ], [
      'tickets.required' => 'Minimal harus ada satu tiket.',
      'tickets.min' => 'Minimal harus ada satu tiket.',
      'tickets.*.name.required' => 'Nama tiket wajib diisi.',
      'tickets.*.price.required' => 'Harga tiket wajib diisi.',
      'tickets.*.price.numeric' => 'Harga harus berupa angka.',
      'tickets.*.price.min' => 'Harga tidak boleh negatif.',
    ]);

    // Ensure the authenticated user has an EventCreator row, create if missing
    $userId = Auth::id();
    $creator = EventCreator::firstOrCreate([
      'user_id' => $userId,
    ], [
      'age' => null,
    ]);

    // Create the event and set default status to 'requested'
    $event = Event::create(array_merge($data, [
      'events_creators_id' => $creator->id,
      'status' => 'requested',
      'approved_at' => null,
      'rejected_at' => null,
    ]));

    // Create tickets for the event
    foreach ($request->tickets as $ticketData) {
      Ticket::create([
        'event_id' => $event->event_id,
        'name' => $ticketData['name'],
        'price' => $ticketData['price'],
      ]);
    }

    return redirect()->back()->with('success', 'Event created and requested for approval.');
  }
  public function showParticipants($eventId)
  {
      $event = Event::with(['tickets', 'ticket_holders.attendee.user'])
          ->findOrFail($eventId);
      
      // Get pagination per page from request
      $perPage = request()->get('per_page', 10);
      
      // Handle "all" option
      if ($perPage === 'all') {
          $totalCount = $event->ticket_holders()->count();
          $perPage = $totalCount > 0 ? $totalCount : 10;
      } else {
          $perPage = (int) $perPage;
          if (!in_array($perPage, [10, 20, 50])) {
              $perPage = 10;
          }
      }
      
      // Get paginated ticket holders
      $ticketHolders = $event->ticket_holders()
          ->with(['attendee.user'])
          ->orderBy('created_at', 'desc')
          ->paginate($perPage);
      
      // Append per_page parameter to pagination links
      $ticketHolders->appends(request()->query());
      
      return view('creator.detail', compact('event', 'ticketHolders'));
  }

}
