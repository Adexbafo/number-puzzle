<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'player_one',
        'player_two',
        'stake',
        'status',
        'winner'
    ];

    public function playerOne()
    {
        return $this->belongsTo(User::class, 'player_one');
    }

    public function playerTwo()
    {
        return $this->belongsTo(User::class, 'player_two');
    }
}