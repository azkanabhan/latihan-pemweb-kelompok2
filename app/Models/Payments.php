<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    // Nama tabel yang mau dipakai, karena beda dari konvensi (plural)
    protected $table = 'payments';

    // Kolom yang boleh diisi (mass assignment)
    protected $fillable = [
        'amount',
        'method',
        'payment_date',
        'fk_atendee_id', // Ini juga perlu dimasukin
    ];

    // Definisikan relasi ke tabel 'attendees'
    // 'payment' BELONGS TO 'attendee'
    public function attendee()
    {
        return $this->belongsTo(Attendees::class, 'fk_atendee_id');
    }
}
