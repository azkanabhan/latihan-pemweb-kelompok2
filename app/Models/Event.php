<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Attendee;
use App\Models\EventCreator;
use App\Models\Ticket;
use App\Models\ReviewRating;

class Event extends Model
{
    use HasFactory;

    // Primary key custom
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_description',
        'event_location',
        'event_date',
        'event_capacity',
        'events_creators_id',
        'status',
        'approved_at',
        'rejected_at'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relasi ke pembuat event
    public function creator()
    {
        return $this->belongsTo(EventCreator::class, 'events_creators_id', 'id');
    }

    // Relasi ke tiket
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'event_id');
    }

    // Relasi ke pembayaran
    public function payments()
    {
        return $this->hasMany(Payment::class, 'event_id', 'event_id');
    }

    // Relasi ke tiket
    public function ticket_holders()
    {
        return $this->hasMany(TicketHolder::class, 'event_id', 'event_id');
    }

    // Relasi ke ulasan
    public function reviews()
    {
        return $this->hasMany(ReviewRating::class, 'event_id', 'event_id');
    }

    // Scope status event
    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Route model binding key
    public function getRouteKeyName()
    {
        return 'event_id';
    }

    // Metode approve/reject
    public function approve(): bool
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->rejected_at = null;
        return $this->save();
    }

    public function reject(): bool
    {
        $this->status = 'rejected';
        $this->rejected_at = now();
        $this->approved_at = null;
        return $this->save();
    }

    // 🔥 Relasi Many-to-Many ke Attendee
    public function attendees()
    {
        return $this->belongsToMany(
            Attendee::class,   // Model tujuan
            'attendee_event',  // Nama tabel pivot
            'event_id',        // FK pivot -> events
            'attendee_id',     // FK pivot -> attendees
            'event_id',        // PK di tabel events
            'id'               // PK di tabel attendees
        );
    }

    // Accessor: total booked capacity (sum of successful payments quantity)
    public function getBookedCapacityAttribute()
    {
        // Consider payments with status 'active' or 'used' as confirmed bookings
        if ($this->relationLoaded('payments')) {
            return $this->payments->whereIn('status', 'paid')->sum('quantity');
        }

        return $this->payments()->whereIn('status', 'paid')->sum('quantity');
    }

    // Accessor: availability status ('open' when there are seats left)
    public function getAvailabilityStatusAttribute()
    {
        $booked = $this->booked_capacity ?? 0;
        $capacity = $this->event_capacity ?? 0;

        return ($capacity > $booked) ? 'open' : 'closed';
    }

    // Query Methods - semua query dipindahkan ke sini
    
    /**
     * Get approved events that are upcoming (event_date >= today)
     * Used for welcome page
     */
    public static function getApprovedUpcomingEvents()
    {
        return self::with(['tickets', 'payments'])
            ->approved()
            ->whereDate('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->get();
    }

    /**
     * Get requested events for admin dashboard
     * Used in admin dashboard
     */
    public static function getRequestedEventsForAdmin($limit = 25)
    {
        return self::with(['creator.user' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->requested()
            ->orderByDesc('event_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get events by creator IDs with ticket holders count
     * Used in creator dashboard
     */
    public static function getEventsByCreatorIds(array $creatorIds, string $status = 'requested')
    {
        $query = self::with('creator')
            ->withCount('ticket_holders')
            ->whereIn('events_creators_id', $creatorIds);

        // Apply status scope
        if ($status === 'requested') {
            $query->requested();
        } elseif ($status === 'approved') {
            $query->approved();
        } elseif ($status === 'rejected') {
            $query->rejected();
        }

        return $query->orderBy('event_date', 'asc')->get();
    }

    /**
     * Get events with filters for admin events index
     * Used in admin events management page
     */
    public static function getEventsWithFilters(string $tab = 'requested', ?string $search = null, int $perPage = 10)
    {
        $query = self::with(['creator.user' => function($query) {
            $query->select('id', 'name', 'email');
        }]);

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('event_name', 'like', "%{$search}%")
                  ->orWhere('event_location', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($tab === 'approved') {
            $query->approved();
        } else {
            $query->requested();
        }

        return $query->orderByDesc('event_date')->paginate($perPage)->withQueryString();
    }

    /**
     * Get event with full relations for detail page
     * Used in event detail/show pages
     */
    public static function getEventWithFullRelations($eventId)
    {
        return self::with(['tickets', 'ticket_holders.attendee.user'])
            ->findOrFail($eventId);
    }

    /**
     * Get event with admin relations
     * Used in admin event detail page
     */
    public static function getEventForAdmin($eventId)
    {
        return self::with(['creator.user', 'tickets', 'ticket_holders.attendee.user'])
            ->findOrFail($eventId);
    }

    /**
     * Get paginated ticket holders for event detail
     * Used in creator event detail page
     */
    public function getPaginatedTicketHolders($perPage = 10)
    {
        // Validate and normalize perPage
        if ($perPage === 'all') {
            $totalCount = $this->ticket_holders()->count();
            $perPage = $totalCount > 0 ? $totalCount : 10;
        } else {
            $perPage = (int) $perPage;
            if (!in_array($perPage, [10, 20, 50])) {
                $perPage = 10;
            }
        }

        $ticketHolders = $this->ticket_holders()
            ->with(['attendee.user'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $ticketHolders->appends(request()->query());

        return $ticketHolders;
    }
}
