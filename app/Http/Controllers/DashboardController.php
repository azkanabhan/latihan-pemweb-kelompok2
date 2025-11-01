<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventCreator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index(): View
    {
        $user = auth()->user();
        $data = [];

        if ($user->role === 'admin') {
            $data = $this->getAdminDashboardData();
        } elseif ($user->role === 'creator') {
            $data = $this->getCreatorDashboardData($user->id);
        } elseif ($user->role === 'attendee') {
            $data = $this->getAttendeeDashboardData();
        }

        return view('dashboard', $data);
    }

    /**
     * Get data for admin dashboard
     */
    private function getAdminDashboardData(): array
    {
        return [
            'admin_requested_events' => Event::getRequestedEventsForAdmin(25)
        ];
    }

    /**
     * Get data for creator dashboard
     */
    private function getCreatorDashboardData(int $userId): array
    {
        $creatorIds = EventCreator::getCreatorIdsByUserId($userId);

        return [
            'creator_events_requested' => Event::getEventsByCreatorIds($creatorIds, 'requested'),
            'creator_events_approved' => Event::getEventsByCreatorIds($creatorIds, 'approved'),
            'creator_events_rejected' => Event::getEventsByCreatorIds($creatorIds, 'rejected'),
        ];
    }

    /**
     * Get data for attendee dashboard
     */
    private function getAttendeeDashboardData(): array
    {
        // TODO: Implement attendee dashboard data if needed
        return [];
    }
}

