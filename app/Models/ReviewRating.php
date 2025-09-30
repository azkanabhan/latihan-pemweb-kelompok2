<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRating extends Model
{
    use HasFactory;

    protected $table = 'reviews_ratings';
    protected $primaryKey = 'review_id';

    protected $fillable = [
        'attendee_id',
        'event_id',
        'body',
        'rating',
        'created_at',
        'updated_at'
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id', 'attendee_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id', 'event_id');
    }
}
