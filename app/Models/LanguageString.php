<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageString extends Model
{
    use HasFactory;

    protected $table = 'language_string';

    protected $fillable = [
        'lang_key',
        'lang_value'
    ];
}
