<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS for generated URLs when behind ngrok or when app URL is https
        $appUrl = config('app.url');
        if ((is_string($appUrl) && str_starts_with($appUrl, 'https://')) || str_contains($appUrl ?? '', 'ngrok')) {
            URL::forceScheme('https');
        }

        // Provide creator_events to the creator dashboard view if not already provided by route/controller
        View::composer('dashboards.creator', function ($view) {
            $data = $view->getData();
            // Check if already provided by route (new structure with separate status)
            if (array_key_exists('creator_events_requested', $data) || 
                array_key_exists('creator_events_approved', $data) || 
                array_key_exists('creator_events_rejected', $data)) {
                return; // already provided
            }

            if (! auth()->check()) {
                $view->with('creator_events_requested', collect());
                $view->with('creator_events_approved', collect());
                $view->with('creator_events_rejected', collect());
                return;
            }

            $userId = auth()->id();
            $creatorIds = \App\Models\EventCreator::where('user_id', $userId)->pluck('id');
            $ids = $creatorIds->merge([$userId])->unique()->values()->toArray();

            if (! empty($ids)) {
                $view->with('creator_events_requested', \App\Models\Event::with('creator')
                    ->whereIn('events_creators_id', $ids)
                    ->requested()
                    ->orderBy('event_date', 'asc')
                    ->get());
                
                $view->with('creator_events_approved', \App\Models\Event::with('creator')
                    ->whereIn('events_creators_id', $ids)
                    ->approved()
                    ->orderBy('event_date', 'asc')
                    ->get());
                
                $view->with('creator_events_rejected', \App\Models\Event::with('creator')
                    ->whereIn('events_creators_id', $ids)
                    ->rejected()
                    ->orderBy('event_date', 'asc')
                    ->get());
            } else {
                $view->with('creator_events_requested', collect());
                $view->with('creator_events_approved', collect());
                $view->with('creator_events_rejected', collect());
            }
        });
    }
}
