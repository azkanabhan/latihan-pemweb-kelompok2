<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCreator extends Model
{
    use HasFactory;

    protected $table = 'event_creators';
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'email',
        'username',
        'password',
        'age',
    ];

    protected $hidden = [
        'password',
    ];
}