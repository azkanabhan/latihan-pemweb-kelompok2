<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EventDay;

class TicketHolder extends Model
{
    use HasFactory;

    protected $primaryKey = 'ticket_holder_id';

    protected $fillable = [
        'attendee_id',
        'event_id',
        'event_day_id',
        'qr_code',
        'status',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id', 'id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }

    public function eventDay()
    {
        return $this->belongsTo(EventDay::class, 'event_day_id', 'id');
    }
}
