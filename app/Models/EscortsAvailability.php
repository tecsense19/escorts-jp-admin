<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscortsAvailability extends Model
{
    use HasFactory;

    protected $table = 'escorts_availability';

    protected $fillable = [
        'user_id',
        'available_date',
        'available_time'
    ];
}
