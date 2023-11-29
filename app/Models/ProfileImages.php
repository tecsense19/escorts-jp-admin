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

    // Accessor for the file_path attribute
    public function getFilePathAttribute($value)
    {
        // You can modify the image path or add any logic before returning it
        return $value ? url('/') . '/' . $value : ''; // Modify the path as needed
    }
}
