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
        'available_time',
        'start_time',
        'end_time'
    ];

    public function scopeOneHour($query) {
        return $query->whereRaw('TIME_TO_SEC(end_time) - TIME_TO_SEC(start_time) = ?', [3600]);
    }

    public function scopeTwoHours($query) {
        return $query->whereRaw('TIME_TO_SEC(end_time) - TIME_TO_SEC(start_time) = ?', [7200]);
    }

    public function scopeConvertToOneHourSlots($query) {
        $oneHourDuration = 60 * 60; // 1 hour in seconds

        return $query->whereRaw('TIME_TO_SEC(TIMEDIFF(end_time, start_time)) = ?', [$oneHourDuration]);
    }

    public function scopeConvertToTwoHourSlots($query) {
        $oneHourDuration = 60 * 60; // 1 hour in seconds
        $twoHourDuration = 2 * $oneHourDuration; // 2 hours in seconds

        return $query->whereRaw('TIME_TO_SEC(TIMEDIFF(end_time, start_time)) = ?', [$twoHourDuration]);
    }
}
