<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $casts = [
        'features' => 'array',
        'requirements' => 'array',
        'release_date' => 'date'
    ];

    protected $fillable = [
        'title', 'slug', 'description', 'price', 'discount',
        'developer', 'publisher', 'release_date',
        'features', 'requirements', 'main_image', 'header_image'
    ];
}