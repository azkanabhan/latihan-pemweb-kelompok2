<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCreator;
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

    return redirect()->back()->with('success', 'Event created and requested for approval.');
  }
  public function showParticipants($eventId)
  {
      $event = Event::with('attendees')->findOrFail($eventId);
      return view('creator.participants', compact('event'));
  }

}
