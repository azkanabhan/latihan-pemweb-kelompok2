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

    // Query Methods - semua query dipindahkan ke sini

    /**
     * Get EventCreator by user_id
     * Used to find creator by user ID
     */
    public static function getByUserId($userId)
    {
        return self::where('user_id', $userId)->first();
    }

    /**
     * Get EventCreator IDs by user_id
     * Used to get creator IDs for querying events
     */
    public static function getCreatorIdsByUserId($userId)
    {
        $creator = self::getByUserId($userId);
        return $creator ? [$creator->id] : [];
    }

    /**
     * Get all creators with user info for admin edit
     * Used in admin event edit page
     */
    public static function getAllCreatorsWithUser()
    {
        return self::with('user:id,name,email')->orderBy('id')->get(['id', 'user_id']);
    }
}