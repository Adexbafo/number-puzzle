<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameSession;
use App\Services\GameService;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function start()
{
    $user = auth()->user();

    // Close any active session
    GameSession::where('user_id', $user->id)
        ->where('is_active', true)
        ->update([
            'is_active' => false,
            'ended_at' => now()
        ]);

    // 🔥 RESET PROFILE (VERY IMPORTANT)
    $profile = $user->profile;
    $profile->update([
        'score' => 0,
        'lifelines' => 5,
        'round' => 1
    ]);

    // Create new session
    GameSession::create([
        'user_id' => $user->id,
        'mode' => 'single',
        'round' => 1,
        'question_number' => 1,
        'is_active' => true,
        'started_at' => now()
    ]);

    return redirect()->route('game.play');
}

    public function play(GameService $service)
    {
        // ALWAYS fetch latest fresh session
        $session = GameSession::where('user_id', auth()->id())
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$session) {
            return redirect()->route('game.start');
        }

        // End game at 80 rounds
        if ($session->round > 80) {
            $session->update([
                'is_active' => false,
                'ended_at' => now()
            ]);

            return redirect()->route('dashboard')->with('success', 'Game completed!');
        }

        // Generate question
        $question = $service->generateQuestion();

        // Store answer securely in session
        session([
            'answer' => $question['answer'],
            'started_at' => now()
        ]);

        return view('game.play', [
            'q' => $question,
            'round' => $session->round,
            'question_number' => $session->question_number,
            'profile' => auth()->user()->profile
        ]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'answer' => 'required|numeric'
        ]);

        // ALWAYS get latest session
        $session = GameSession::where('user_id', auth()->id())
            ->where('is_active', true)
            ->latest()
            ->first();

        if (!$session) {
            return redirect()->route('game.start');
        }

        $profile = auth()->user()->profile;
        $correct = session('answer');
        $startTime = session('started_at');

        if (!$correct || !$startTime) {
            return redirect()->route('game.play');
        }

        DB::transaction(function () use ($request, $session, $profile, $correct, $startTime) {

            // Check expiry (20 seconds)
            $isExpired = now()->diffInSeconds($startTime) > 20;

            // Evaluate answer
            if ($isExpired || $request->answer != $correct) {
                $profile->lifelines = max(0, $profile->lifelines - 1);
                session()->flash('error', 'Wrong answer!');
            } else {
                $profile->score += 10;
                session()->flash('success', 'Correct!');
            }

            // 🔥 CRITICAL FIX — ALWAYS INCREMENT
            $session->question_number += 1;

            // 🔥 CORRECT ROUND LOGIC
            if ($session->question_number > 8) {
                $session->round += 1;
                $session->question_number = 1;
            }

            // Level system
            $this->handleLevel($profile, $session);

            // Game over check
            if ($profile->lifelines <= 0) {
                $session->is_active = false;
                $session->ended_at = now();
            }

            // Sync profile
            $profile->round = $session->round;

            // SAVE (VERY IMPORTANT)
            $profile->save();
            $session->save();
        });

        session()->forget(['answer', 'started_at']);

        if (auth()->user()->profile->lifelines <= 0) {
            app(\App\Http\Controllers\LeaderboardController::class)->update();

           return redirect()->route('dashboard')->with('error', 'Game Over!');
        }

        return redirect()->route('game.play');
    }

    /*
    |--------------------------------------------------------------------------
    | LEVEL SYSTEM
    |--------------------------------------------------------------------------
    */

    private function handleLevel($profile, $session)
    {
        if ($profile->level === 'amateur') {
            if ($profile->round >= 10 && $profile->lifelines >= 5) {
                $this->resetProgress($profile, $session, 'professional');
            }
        } elseif ($profile->level === 'professional') {
            if ($profile->round < 30 && $profile->lifelines < 2) {
                $this->resetProgress($profile, $session, 'amateur');
            }
        }
    }

    private function resetProgress($profile, $session, $newLevel)
    {
        $profile->level = $newLevel;
        $profile->round = 1;
        $profile->lifelines = 5;

        $session->round = 1;
        $session->question_number = 1;
    }
}