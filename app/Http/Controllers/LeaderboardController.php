<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
{
    // 🟣 Professional leaderboard (Top 100 ONLY)
    $proLeaders = \App\Models\Leaderboard::with('user.profile')
        ->whereHas('user.profile', function ($q) {
            $q->where('level', 'professional');
        })
        ->orderByDesc('score')
        ->take(100)
        ->get();

    // 🟢 Amateur leaderboard (no limit)
    $amateurLeaders = \App\Models\Leaderboard::with('user.profile')
        ->whereHas('user.profile', function ($q) {
            $q->where('level', 'amateur');
        })
        ->orderByDesc('score')
        ->get();

    return view('leaderboard', [
        'proLeaders' => $proLeaders,
        'amateurLeaders' => $amateurLeaders
    ]);
}

    public function update()
    {
        $user = auth()->user();
        $profile = $user->profile;

        Leaderboard::updateOrCreate(
            ['user_id' => $user->id],
            ['score' => $profile->score]
        );
    }
}