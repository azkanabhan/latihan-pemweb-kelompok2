<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventDay;
use App\Models\User;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'event_id',
        'ticket_id',
        'attendee_id',
        'user_id',
        'quantity',
        'method',
        'amount',
        'payment_date',
        'status',
        'external_id',
        'va_number',
        'payment_url',
        'expired_at',
        'event_day_id',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'payment_date' => 'datetime',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function eventDay()
    {
        return $this->belongsTo(EventDay::class, 'event_day_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }
}
