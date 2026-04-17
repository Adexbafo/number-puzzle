<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = [
        'user_id',
        'mode',
        'round',
        'question_number',
        'is_active',
        'started_at'
    ];
}