<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameSession;
use App\Models\Profile; // Added this import

class DashboardController extends Controller
{
    public function index()
    {
        // Get user with profile to prevent N+1 query issues
        $user = auth()->user()->load('profile');

        // Ensure profile ALWAYS exists
        // Using the relationship ensures the user_id is set correctly
        if (!$user->profile) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'level' => 'amateur',
                'lifelines' => 5,
                'score' => 0,
                'round' => 1
            ]);
        } else {
            $profile = $user->profile;
        }

        // Check for an ongoing game
        $activeSession = GameSession::where('user_id', $user->id)
            ->where('is_active', true)
            ->latest()
            ->first();

        return view('dashboard', [
            'profile' => $profile,
            'activeSession' => $activeSession
        ]);
    }
}