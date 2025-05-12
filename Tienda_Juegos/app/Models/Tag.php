<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['tag_name'];

    public function games()
    {
           return $this->belongsToMany(
        Game::class,
        'game_tags',
        'tag_id',
        'game_id'
    );
    }
}