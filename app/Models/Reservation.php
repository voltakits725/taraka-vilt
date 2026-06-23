<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'table_number',
        'reservation_date',
        'reservation_time',
        'status',
        'guest_count',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
