<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventCreator;
use App\Models\EventDay;
use App\Models\Ticket;
use App\Models\EventPhoto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
      'event_capacity' => 'required|integer|min:1',
      'days' => 'required|array|min:1',
      'days.*.start_at' => 'required|date',
      'days.*.end_at' => 'required|date|after_or_equal:days.*.start_at',
      'days.*.label' => 'nullable|string|max:100',
      'tickets' => 'required|array|min:1',
      'tickets.*.name' => 'required|string|max:255',
      'tickets.*.price' => 'required|numeric|min:0',
      'tickets.*.event_day_index' => 'nullable|integer|min:0',
      'photos' => 'nullable|array',
      'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
    ], [
      'tickets.required' => 'Minimal harus ada satu tiket.',
      'tickets.min' => 'Minimal harus ada satu tiket.',
      'tickets.*.name.required' => 'Nama tiket wajib diisi.',
      'tickets.*.price.required' => 'Harga tiket wajib diisi.',
      'tickets.*.price.numeric' => 'Harga harus berupa angka.',
      'tickets.*.price.min' => 'Harga tidak boleh negatif.',
      'photos.*.image' => 'File harus berupa gambar.',
      'photos.*.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
      'photos.*.max' => 'Ukuran gambar maksimal 5MB.',
    ]);

    // Ensure the authenticated user has an EventCreator row, create if missing
    $userId = Auth::id();
    $creator = EventCreator::firstOrCreate([
      'user_id' => $userId,
    ], [
      'age' => null,
    ]);

    // Calculate start_date and end_date from days array
    $startMin = collect($request->days)->min('start_at');
    $endMax = collect($request->days)->max('end_at');

    // Create the event and set default status to 'requested'
    $event = Event::create(array_merge($data, [
      'events_creators_id' => $creator->id,
      'status' => 'requested',
      'approved_at' => null,
      'rejected_at' => null,
      'start_date' => $startMin,
      'end_date' => $endMax,
    ]));

    // Create event days and map index -> id
    $dayIdMap = [];
    if (!empty($request->days)) {
      foreach ($request->days as $idx => $day) {
        $createdDay = EventDay::create([
          'event_id' => $event->event_id,
          'start_at' => $day['start_at'],
          'end_at' => $day['end_at'],
          'label' => $day['label'] ?? null,
        ]);
        $dayIdMap[$idx] = $createdDay->id;
      }
    }

    // Create tickets for the event (optional per-day assignment)
    foreach ($request->tickets as $ticketData) {
      $dayIndex = $ticketData['event_day_index'] ?? null;
      $eventDayId = $dayIndex !== null && isset($dayIdMap[$dayIndex]) ? $dayIdMap[$dayIndex] : null;

      Ticket::create([
        'event_id' => $event->event_id,
        'event_day_id' => $eventDayId,
        'name' => $ticketData['name'],
        'price' => $ticketData['price'],
      ]);
    }

    // Handle photo uploads
    if ($request->hasFile('photos')) {
      $order = 1;
      foreach ($request->file('photos') as $photo) {
        // Store photo to storage/app/public/events/{event_id}/
        $filename = 'event_' . $event->event_id . '_' . time() . '_' . $photo->hashName();
        $path = $photo->storeAs(
          'events/' . $event->event_id,
          $filename,
          'public'
        );

        // Create EventPhoto record
        EventPhoto::create([
          'event_id' => $event->event_id,
          'photo_path' => $path,
          'order' => $order,
        ]);

        $order++;
      }
    }

    return redirect()->back()->with('success', 'Event created and requested for approval.');
  }
  public function showParticipants($eventId)
  {
    $event = Event::getEventWithFullRelations($eventId);
    $perPage = request()->get('per_page', 10);

    $ticketHolders = $event->getPaginatedTicketHolders($perPage);

    return view('creator.detail', compact('event', 'ticketHolders'));
  }
}
