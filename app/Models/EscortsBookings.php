<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscortsBookings extends Model
{
    use HasFactory;

    protected $table = 'escorts_bookings';

    protected $fillable = [
        'escort_id',
        'user_id',
        'hotel_name',
        'room_number',
        'selected_word',
        'hourly_price',
        'booking_price'
    ];

    public function bookingSlots()
    {
        return $this->hasMany(BookingSlot::class, 'booking_id', 'id');
    }

    public function getUsers()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getEscorts()
    {
        return $this->hasOne(User::class, 'id', 'escort_id');
    }
}
