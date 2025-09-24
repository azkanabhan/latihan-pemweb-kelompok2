<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRatings extends Model
{
    use HasFactory;

    // Nama tabel yang mau dipakai, karena beda dari konvensi
    protected $table = 'review_ratings';

    protected $primaryKey = 'review_id';

    // Kolom yang boleh diisi
    protected $fillable = [
        'body',
        'rating',
        'user_id',
        'event_id',
    ];

    // Definisikan relasi ke tabel 'users'
    // 'review_rating' BELONGS TO 'user'
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisikan relasi ke tabel 'events'
    // 'review_rating' BELONGS TO 'event'
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
