<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_date', 'total_score', 'ball_id', 'location_id', 'group_id', 'user_id', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ball()
    {
        return $this->belongsTo(Ball::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function frames()
    {
        return $this->hasMany(Frame::class);
    }

    protected function setGameDateAttribute($value)
    {
        $this->attributes['game_date'] = date('Y-m-d', strtotime($value));
    }

    public function scopeOfBall($query, $ball_id) {
        if($ball_id) {
            return $query->where('ball_id', $ball_id);
        }
        return $query;
    }

    public function scopeOfLocation($query, $location_id) {
        if($location_id) {
            return $query->where('location_id', $location_id);
        }
        return $query;
    }

    public function scopeOfStartDate($query, $start_date) {
        if($start_date) {
            return $query->where('game_date', '>=', $start_date);
        }
        return $query;
    }

    public function scopeOfEndDate($query, $end_date) {
        if($end_date) {
            return $query->where('game_date', '<=', $end_date);
        }
        return $query;
    }

    public function scopeOfLoggedUser($query) {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeOfStatus($query, $status) {
        return $query->where('status', $status);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }
}
