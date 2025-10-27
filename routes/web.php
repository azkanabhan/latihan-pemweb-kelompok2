<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// SEMUA LOGIKA DASHBOARD SEKARANG ADA DI SINI
Route::get('/dashboard', function () {
    $user = auth()->user();
    $data = []; // Variabel untuk menampung data

    if ($user->role === 'admin') {
        $events = App\Models\Event::with('creator.user')
            ->requested()
            ->orderByDesc('event_date')
            ->limit(25)
            ->get();
        $data['admin_requested_events'] = $events;

    } else if ($user->role === 'creator') {
        $creator = App\Models\EventCreator::where('user_id', $user->id)->first();
        $creatorIds = $creator ? [$creator->id] : [];
        $idsForQuery = array_values(array_unique(array_merge($creatorIds, [$user->id])));
        
        $creator_events = App\Models\Event::with('creator')
            ->whereIn('events_creators_id', $idsForQuery)
            ->orderBy('event_date', 'asc')
            ->get();
        $data['creator_events'] = $creator_events;

    } else if ($user->role === 'attendee') {
        // Nanti Anda bisa tambahkan kueri untuk attendee di sini
        // $data['joined_events'] = ...
    }

    // Kirim view DENGAN DATA yang sesuai
    return view('dashboard', $data);
    
})->middleware(['auth', 'verified'])->name('dashboard');


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
    Route::get('creator/events/{id}/participants',
        [App\Http\Controllers\Creator\EventController::class, 'showParticipants'])
        ->name('creator.events.participants');
});

// ALIAS AGAR ROUTE creator.dashboard TETAP BISA DIPAKAI DI VIEW
RouteFacade::get('/creator/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified', 'role:creator'])->name('creator.dashboard');

// RUTE UNTUK ATTENDEE (SELAIN DASHBOARD)
RouteFacade::middleware(['auth', 'role:attendee'])->group(function () {
    // Attendee: view joined events and tickets
    // Route::get('attendee/events', ...);
    // Route::get('attendee/tickets', ...);
});


