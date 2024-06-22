<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_access', 'user_id', 'city', 'state'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
