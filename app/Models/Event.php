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
        return $this->belongsTo(EventCreator::class, 'events_creators_id', 'user_id');
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

    // Metode approve/reject
    public function approve(): void
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->rejected_at = null;
        $this->save();
    }

    public function reject(): void
    {
        $this->status = 'rejected';
        $this->rejected_at = now();
        $this->approved_at = null;
        $this->save();
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
}
