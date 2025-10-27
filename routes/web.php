<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $events = App\Models\Event::with(['tickets', 'payments'])
        ->approved()
        ->whereDate('event_date', '>=', now()->toDateString())
        ->orderBy('event_date', 'asc')
        ->get();

    return view('welcome', compact('events'));
})->name('events.index');

// Public event details
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Payment routes
Route::post('/payment/create-va', [PaymentController::class, 'createVa'])->name('payment.create-va');
Route::get('/payment/check-status/{vaNumber}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');

// Webhook route (no CSRF protection needed - excluded in middleware)
Route::post('/webhook/payment', [App\Http\Controllers\WebhookController::class, 'handle'])
    ->name('webhook.payment')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

RouteFacade::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        // fetch recent requested events for admin dashboard
        $events = App\Models\Event::with('creator.user')
            ->requested()
            ->orderByDesc('event_date')
            ->limit(25)
            ->get();

        return view('dashboard', ['admin_requested_events' => $events]);
    })->name('admin.dashboard');

    // Admin: CRUD users (creator/attendee) & events
    Route::resource('admin/users', App\Http\Controllers\Admin\UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'store' => 'admin.users.store',
        'show' => 'admin.users.show',
        'edit' => 'admin.users.edit',
        'update' => 'admin.users.update',
        'destroy' => 'admin.users.destroy',
    ]);
    Route::resource('admin/events', App\Http\Controllers\Admin\EventController::class)->except(['create', 'store'])->names([
        'index' => 'admin.events.index',
        'show' => 'admin.events.show',
        'edit' => 'admin.events.edit',
        'update' => 'admin.events.update',
        'destroy' => 'admin.events.destroy',
    ]);
    Route::post('admin/events/{event}/approve', [App\Http\Controllers\Admin\EventController::class, 'approve'])->name('admin.events.approve');
    Route::post('admin/events/{event}/reject', [App\Http\Controllers\Admin\EventController::class, 'reject'])->name('admin.events.reject');
});

RouteFacade::middleware(['auth', 'role:creator'])->group(function () {
    Route::get('/creator', function () {
        $user = auth()->user();
        $events = collect();

        if ($user) {
            // Get all event_creator ids that belong to the authenticated user
            $creatorIds = App\Models\EventCreator::where('user_id', $user->id)->pluck('id');

            // Fallback: some records may have events_creators_id set directly to the user's id
            // Merge the user's id so we cover both normal FK mapping (event_creator.id) and
            // the case where events_creators_id was manually set to the users.id value.
            $ids = $creatorIds->merge([$user->id])->unique()->values()->toArray();

            if (! empty($ids)) {
                // Fetch events whose events_creators_id is one of the ids (creator row ids or user id fallback)
                $events = App\Models\Event::with('creator')
                    ->whereIn('events_creators_id', $ids)
                    ->orderBy('event_date', 'asc')
                    ->get();
            }
        }

        return view('dashboard', ['creator_events' => $events]);
    })->name('creator.dashboard');

    // Creator: CRUD events owned by self, view attendees, view balance
    // Create event (creator)
    Route::post('creator/events', [App\Http\Controllers\Creator\EventController::class, 'store'])->name('creator.events.store');
    // Route::get('creator/events/{event}/attendees', ...);
    // Route::get('creator/balance', ...);
});

RouteFacade::middleware(['auth', 'role:attendee'])->group(function () {
    Route::get('/attendee', function () {
        return view('dashboard');
    })->name('attendee.dashboard');

    // Attendee: view tickets
    Route::get('/attendee/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/attendee/tickets/{paymentId}/qrcode', [TicketController::class, 'showQrCode'])->name('tickets.qrcode');
});
