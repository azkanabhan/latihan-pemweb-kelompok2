<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'date',
        'capacity',
        'img_thumbnail',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    /**
     * Get the tickets for the event.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'events_id');
    }
}
