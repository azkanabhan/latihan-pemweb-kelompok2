<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

RouteFacade::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', function () {
        return view('dashboard');
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
    // Route::resource('admin/events', Admin\EventController::class);
});

RouteFacade::middleware(['auth', 'role:creator'])->group(function () {
    Route::get('/creator', function () {
        return view('dashboard');
    })->name('creator.dashboard');

    // Creator: CRUD events owned by self, view attendees, view balance
    // Route::resource('creator/events', Creator\EventController::class);
    // Route::get('creator/events/{event}/attendees', ...);
    // Route::get('creator/balance', ...);
});

RouteFacade::middleware(['auth', 'role:attendee'])->group(function () {
    Route::get('/attendee', function () {
        return view('dashboard');
    })->name('attendee.dashboard');

    // Attendee: view joined events and tickets
    // Route::get('attendee/events', ...);
    // Route::get('attendee/tickets', ...);
});
