<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCreator;
use App\Models\EventPhoto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// use Spatie\GoogleCalendar\Event as GoogleCalendarEvent; // Disabled: Package not installed

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $tab = $request->string('tab')->toString() ?: 'requested';
        $search = $request->string('q')->toString() ?: null;

        $events = Event::getEventsWithFilters($tab, $search, 10);

        return view('admin.events.index', compact('events', 'tab'));
    }

    // create/store disabled for admin per new requirements

    public function show(Event $event): View
    {
        $event = Event::getEventForAdmin($event->event_id);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event): View
    {
        $creators = EventCreator::getAllCreatorsWithUser();
        return view('admin.events.edit', compact('event', 'creators'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        // 3. PERBAIKI VALIDASI (MENGGANTI event_date)
        $validated = $request->validate([
            'event_name' => ['required', 'string', 'max:255'],
            'event_description' => ['nullable', 'string'],
            'event_location' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'], // <-- DIUBAH
            'end_date' => ['required', 'date', 'after_or_equal:start_date'], // <-- DIUBAH
            'event_capacity' => ['required', 'integer', 'min:1'],
            'events_creators_id' => ['required', 'integer', 'exists:event_creators,id'],
        ]);

        $event->update($validated);

        // Google Calendar sync disabled (package not installed)
        /*
        if ($event->status === 'approved' && $event->google_calendar_event_id) {
            try {
                $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                if ($calendarEvent) {
                    $calendarEvent->name = $event->event_name;
                    $calendarEvent->description = $event->event_description;
                    $calendarEvent->startDateTime = $event->start_date;
                    $calendarEvent->endDateTime = $event->end_date;
                    $calendarEvent->location = $event->event_location;
                    $calendarEvent->save();
                }
            } catch (\Exception $e) {
                Log::error('Gagal update event GCal saat admin update: ' . $e->getMessage());
            }
        }
        */

        return redirect()->route('admin.events.index')->with('status', 'Event updated');
    }

    public function destroy(Event $event): RedirectResponse
    {
        // Google Calendar sync disabled (package not installed)
        /*
        if ($event->google_calendar_event_id) {
            try {
                $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                if ($calendarEvent) {
                    $calendarEvent->delete();
                }
            } catch (\Exception $e) {
                Log::error('Gagal menghapus event GCal saat destroy: ' . $e->getMessage());
            }
        }
        */

        $event->delete();
        return redirect()->route('admin.events.index')->with('status', 'Event deleted');
    }

    public function approve(Event $event): RedirectResponse
    {
        try {
            $result = $event->approve();
            if ($result) {

                // Google Calendar sync disabled (package not installed)
                // TODO: Install spatie/laravel-google-calendar if needed
                /*
                try {
                    if ($event->google_calendar_event_id) {
                        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                        $calendarEvent->name = $event->event_name;
                        $calendarEvent->description = $event->event_description;
                        $calendarEvent->startDateTime = $event->start_date;
                        $calendarEvent->endDateTime = $event->end_date;
                        $calendarEvent->location = $event->event_location;
                        $calendarEvent->save();
                    } else {
                        $calendarEvent = GoogleCalendarEvent::create([
                            'name' => $event->event_name,
                            'description' => $event->event_description,
                            'startDateTime' => $event->start_date,
                            'endDateTime' => $event->end_date,
                            'location' => $event->event_location,
                        ]);
                        $event->google_calendar_event_id = $calendarEvent->id;
                        $event->save();
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal sinkronisasi Google Calendar saat approve: ' . $e->getMessage());
                }
                */

                $event->refresh();
                return redirect()->route('admin.events.show', $event)
                    ->with('status', 'Event berhasil disetujui!');
            } else {
                return back()->withErrors(['error' => 'Gagal menyimpan perubahan.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function reject(Event $event): RedirectResponse
    {
        try {
            $result = $event->reject();
            if ($result) {

                // Google Calendar sync disabled (package not installed)
                /*
                if ($event->google_calendar_event_id) {
                    try {
                        $calendarEvent = GoogleCalendarEvent::find($event->google_calendar_event_id);
                        if ($calendarEvent) {
                            $calendarEvent->delete();
                        }
                        $event->google_calendar_event_id = null;
                        $event->save();
                    } catch (\Exception $e) {
                        Log::error('Gagal menghapus event GCal saat reject: ' . $e->getMessage());
                    }
                }
                */

                $event->refresh();
                return redirect()->route('admin.events.show', $event)
                    ->with('status', 'Event berhasil ditolak!');
            } else {
                return back()->withErrors(['error' => 'Gagal menyimpan perubahan.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function editPhotos(Event $event): View
    {
        $event = Event::getEventForAdmin($event->event_id);
        return view('admin.events.edit-photos', compact('event'));
    }

    public function updatePhotos(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'delete_photos' => 'nullable|array',
            'delete_photos.*' => 'integer|exists:event_photos,id',
        ], [
            'photos.*.image' => 'File harus berupa gambar.',
            'photos.*.mimes' => 'Format gambar harus JPEG, PNG, JPG, atau GIF.',
            'photos.*.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        // Delete photos if requested
        if (!empty($validated['delete_photos'])) {
            foreach ($validated['delete_photos'] as $photoId) {
                $photo = EventPhoto::find($photoId);
                if ($photo && $photo->event_id === $event->event_id) {
                    Storage::disk('public')->delete($photo->photo_path);
                    $photo->delete();
                }
            }
        }

        // Add new photos if uploaded
        if ($request->hasFile('photos')) {
            $maxOrder = $event->photos()->max('order') ?? 0;
            $order = $maxOrder + 1;

            foreach ($request->file('photos') as $photo) {
                $filename = 'event_' . $event->event_id . '_' . time() . '_' . $photo->hashName();
                $path = $photo->storeAs(
                    'events/' . $event->event_id,
                    $filename,
                    'public'
                );

                EventPhoto::create([
                    'event_id' => $event->event_id,
                    'photo_path' => $path,
                    'order' => $order,
                ]);

                $order++;
            }
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Foto event berhasil diperbarui.');
    }
}
