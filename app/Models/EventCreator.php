<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCreator extends Model
{
    use HasFactory;

    protected $table = 'event_creators';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'age',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'events_creators_id', 'id');
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}