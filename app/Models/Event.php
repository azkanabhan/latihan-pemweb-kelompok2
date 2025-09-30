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
        'user_id'
    ];

    public function creator()
    {
        return $this->belongsTo(EventCreator::class, 'user_id', 'user_id');
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
