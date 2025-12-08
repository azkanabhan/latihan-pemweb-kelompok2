<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDay extends Model
{
  use HasFactory;

  protected $table = 'event_days';

  protected $fillable = [
    'event_id',
    'start_at',
    'end_at',
    'label',
  ];

  protected $casts = [
    'start_at' => 'datetime',
    'end_at' => 'datetime',
  ];

  public function event()
  {
    return $this->belongsTo(Event::class, 'event_id', 'event_id');
  }

  public function tickets()
  {
    return $this->hasMany(Ticket::class, 'event_day_id', 'id');
  }

  public function payments()
  {
    return $this->hasMany(Payment::class, 'event_day_id', 'id');
  }

  public function ticketHolders()
  {
    return $this->hasMany(TicketHolder::class, 'event_day_id', 'id');
  }
}
