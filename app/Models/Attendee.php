<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $table = 'attendees';
    // Primary key is 'id' by default

    protected $fillable = [
        'user_id',
    ];
}
