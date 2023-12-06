<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteEscorts extends Model
{
    use HasFactory;

    protected $table = 'favourite_escorts';

    protected $fillable = [
        'user_id',
        'escort_id',
        'is_favourite'
    ];
}
