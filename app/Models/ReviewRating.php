<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'body',
        'rating',
        'created_at',
        'updated_at'
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
