<?php

namespace App\Models;

use Database\Seeders\ReviewsRatingsTableSeeder;
use Database\Seeders\TicketsTableSeeder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'event_description',
        'event_location',
        'event_date',
        'event_capacity',
        'event_creator_id'
    ];

    public function creator()
    {
        return $this->belongsTo(EventCreator::class, 'event_creator_id');
    }

    public function tickets()
    {
        return $this->hasMany(TicketsTableSeeder::class);
    }

    public function reviews()
    {
        return $this->hasMany(ReviewsRatingsTableSeeder::class);
    }
}
