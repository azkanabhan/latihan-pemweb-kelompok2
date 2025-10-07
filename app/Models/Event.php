<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';

    protected $fillable = [
        'event_name',
        'event_description',
        'event_location',
        'event_date',
        'event_capacity',
        'events_creators_id'
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(EventCreator::class, 'events_creators_id', 'id');
    }

    public function scopeRequested($query)
    {
        return $query->where('status', 'requested');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

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

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id', 'event_id');
    }

    public function reviews()
    {
        return $this->hasMany(ReviewRating::class, 'event_id', 'event_id');
    }
}
