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

    // Primary key: the migrations create an `id` column, so use that.
    protected $primaryKey = 'id';
    public $incrementing = true;

    // Tipe kolom PK
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
        // Use conventional pivot mapping: pivot.attendee_id references attendees.id
        return $this->belongsToMany(Event::class, 'attendee_event', 'attendee_id', 'event_id');
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
