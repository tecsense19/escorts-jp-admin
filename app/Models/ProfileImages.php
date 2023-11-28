<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileImages extends Model
{
    use HasFactory;

    protected $tableName = 'profile_images';

    protected $fillable = [
        'user_id',
        'file_path',
        'type'
    ];
}
