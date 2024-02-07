<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'email';


    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'expires_at',
    ];

}
