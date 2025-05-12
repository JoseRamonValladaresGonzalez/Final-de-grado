<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameFeature extends Model
{
    use HasFactory;

    protected $fillable = ['game_id', 'feature_text'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}