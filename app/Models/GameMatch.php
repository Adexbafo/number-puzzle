<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    // Tell Laravel which table to use
    protected $table = 'matches';

    // Optional (recommended)
    protected $fillable = [
        'player_one',
        'player_two',
        'stake',
        'status',
        'winner',
        'started_at',
        'ended_at'
    ];
}