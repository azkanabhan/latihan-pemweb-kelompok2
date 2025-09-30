<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'fk_event_id',
        'fk_attendee_id',
        'qr_code',
        'price',
        'status'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'fk_event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'fk_attendee_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
