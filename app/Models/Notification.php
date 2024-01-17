<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'read_at','expo_push_notifications_id', 'user_id', 'type', 'support_id', 'author'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }


}
