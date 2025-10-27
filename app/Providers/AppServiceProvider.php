<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Provide creator_events to the creator dashboard view if not already provided by route/controller
        View::composer('dashboards.creator', function ($view) {
            $data = $view->getData();
            if (array_key_exists('creator_events', $data)) {
                return; // already provided
            }

            if (! auth()->check()) {
                $view->with('creator_events', collect());
                return;
            }

            $userId = auth()->id();
            $creatorIds = \App\Models\EventCreator::where('user_id', $userId)->pluck('id');
            $ids = $creatorIds->merge([$userId])->unique()->values()->toArray();

            if (! empty($ids)) {
                $events = \App\Models\Event::with('creator')
                    ->whereIn('events_creators_id', $ids)
                    ->orderBy('event_date', 'asc')
                    ->get();
            } else {
                $events = collect();
            }

            $view->with('creator_events', $events);
        });
    }
}
