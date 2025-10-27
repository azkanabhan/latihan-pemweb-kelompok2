<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use App\Models\User;

class Attendee extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'attendees';

    // Primary key khusus
    protected $primaryKey = 'attendee_id';
    public $incrementing = true;

    // Tipe kolom PK (karena pakai unsignedBigInteger)
    protected $keyType = 'int';

    protected $fillable = [
        'username',  // pastikan ada di migrasi
        'email',     // pastikan ada di migrasi
        'password',  // pastikan ada di migrasi
        'age',
        'user_id'    // FK ke tabel users
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * 🔹 Relasi Many-to-Many dengan Event
     * Setiap peserta bisa ikut banyak event,
     * dan setiap event bisa punya banyak peserta.
     */
    public function events()
    {
        return $this->belongsToMany(
            Event::class,       // Model tujuan
            'attendee_event',   // Nama pivot table
            'attendee_id',      // FK pivot -> attendees
            'event_id',         // FK pivot -> events
            'attendee_id',      // PK di attendees
            'event_id'          // PK di events
        );
    }

    /**
     * 🔹 Relasi ke User
     * Setiap attendee terkait ke satu akun user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
