<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\GameFeature;
use App\Models\Screenshot;
use App\Models\SystemRequirement;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'developer_id',
        'publisher_id',
        'release_date',
        'main_image',
        'original_price',
        'current_price',
        'discount_percent'
    ];

    protected $casts = [
        'release_date' => 'date',
    ];

    public function developer()
    {
        return $this->belongsTo(Developer::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }

    public function game_features()
    {
        return $this->hasMany(GameFeature::class);
    }

    public function screenshots()
    {
        return $this->hasMany(Screenshot::class);
    }

     public function requirements()
    {
        // Mapea al modelo SystemRequirement, FK game_id
        return $this->hasMany(SystemRequirement::class, 'game_id', 'id');
    }
    public function tags()
    {
           return $this->belongsToMany(
        Tag::class,     // modelo relacionado
        'game_tags',    // tabla pivot
        'game_id',      // FK en pivot que apunta a this->id
        'tag_id'        // FK en pivot que apunta a tags.id
    );
    }
    public function carts()
{
    return $this->belongsToMany(User::class, 'cart_items')
        ->withPivot('quantity');
}
public function users()
{
    return $this->belongsToMany(User::class, 'cart_items')
        ->withPivot('quantity', 'created_at', 'updated_at');
}
}