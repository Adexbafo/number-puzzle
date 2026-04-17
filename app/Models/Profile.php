<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'lifelines',
        'round',
        'score',
        'wallet_address',
        'wallet_nonce'
    ];
}