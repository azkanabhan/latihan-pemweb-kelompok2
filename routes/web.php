<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Route;

Route::get('/', [EventController::class, 'index'])->name('events.index');

// Public event details
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Payment routes
Route::post('/payment/create-va', [PaymentController::class, 'createVa'])->name('payment.create-va');
Route::post('/payment/check-attendees', [PaymentController::class, 'checkAttendees'])->name('payment.check-attendees');
Route::get('/payment/check-status/{vaNumber}', [PaymentController::class, 'checkStatus'])->name('payment.check-status');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);
Route::get('/payment/success/{externalId?}', [PaymentController::class, 'showSuccess'])->name('payment.success');

// Webhook route (no CSRF protection needed - excluded in middleware)
Route::post('/webhook/payment', [App\Http\Controllers\WebhookController::class, 'handle'])
    ->name('webhook.payment')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// RUTE UNTUK ADMIN (SELAIN DASHBOARD)
RouteFacade::middleware(['auth', 'role:admin'])->group(function () {
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

// RUTE UNTUK CREATOR (SELAIN DASHBOARD)
RouteFacade::middleware(['auth', 'role:creator'])->group(function () {
    // Creator: CRUD events owned by self
    Route::post('creator/events', [App\Http\Controllers\Creator\EventController::class, 'store'])->name('creator.events.store');
    Route::get(
        'creator/events/{id}/detail',
        [App\Http\Controllers\Creator\EventController::class, 'showParticipants']
    )
        ->name('creator.events.detail');
});

// ALIAS AGAR ROUTE creator.dashboard TETAP BISA DIPAKAI DI VIEW
RouteFacade::get('/creator/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified', 'role:creator'])->name('creator.dashboard');

// RUTE UNTUK ATTENDEE (SELAIN DASHBOARD)
RouteFacade::middleware(['auth', 'role:attendee'])->group(function () {
    Route::get('/attendee', function () {
        return view('dashboard');
    })->name('attendee.dashboard');

    // Attendee: view tickets
    Route::get('/attendee/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/attendee/tickets/{ticketHolderId}/qrcode', [TicketController::class, 'showQrCode'])->name('tickets.qrcode');
});
