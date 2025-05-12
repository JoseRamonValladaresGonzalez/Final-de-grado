<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'requirement_type',
        'os',
        'processor',
        'memory',
        'graphics',
        'directx',
        'storage'
    ];

    protected $casts = [
        'requirement_type' => 'string'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}