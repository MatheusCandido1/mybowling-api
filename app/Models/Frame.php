<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frame extends Model
{
    use HasFactory;

    protected $fillable = [
        'frame_number', 'first_shot', 'second_shot','third_shot','is_split','points','status', 'pins','score','game_id'
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
