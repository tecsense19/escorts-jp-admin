<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'booking_slot';

    protected $fillable = [
        'escort_id',
        'booking_id',
        'booking_date',
        'booking_time'
    ];   
}
