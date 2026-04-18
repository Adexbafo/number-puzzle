<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameMatch;
use App\Services\GameService;

class MultiplayerController extends Controller
{
    public function index()
{
    $matches = GameMatch::whereIn('status', ['waiting', 'active'])
        ->latest()
        ->get();

    return view('multiplayer.lobby', compact('matches'));
}

    public function create(Request $request)
    {
        $request->validate([
            'stake' => 'required|numeric|min:0'
        ]);

        // Prevent duplicate matches
        $existing = GameMatch::where(function ($q) {
            $q->where('player_one', auth()->id())
              ->orWhere('player_two', auth()->id());
        })
        ->whereIn('status', ['waiting', 'active'])
        ->first();

        if ($existing) {
            return back()->with('error', 'You already have an active match!');
        }

        GameMatch::create([
            'player_one' => auth()->id(),
            'stake' => $request->stake,
            'status' => 'waiting'
        ]);

        return back()->with('success', 'Match created!');
    }

    public function join($id)
    {
        $match = GameMatch::findOrFail($id);

        if ($match->player_two) {
            return back()->with('error', 'Match already full');
        }

        $match->update([
            'player_two' => auth()->id(),
            'status' => 'active',
            'started_at' => now()
        ]);

        return redirect()->route('mp.play', $match->id);
    }

    public function play($id, GameService $service)
    {
        $match = GameMatch::findOrFail($id);

        if ($match->status !== 'active') {
            return view('multiplayer.play', [
                'match' => $match,
                'q' => null
            ]);
        }

        if (!$match->current_question && 
            !$match->player_one_answered && 
            !$match->player_two_answered) {

            $question = $service->generateQuestion();

            $match->current_question = json_encode($question);
            $match->save();
}

        if (!$match->current_question) {
            $question = $service->generateQuestion();

            $match->current_question = json_encode($question);
            $match->save();
        } else {
            $question = json_decode($match->current_question, true);
        }

        return view('multiplayer.play', [
            'match' => $match,
            'q' => $question
        ]);
    }

    public function submit(Request $request, $id)
{
    $match = GameMatch::findOrFail($id);

    $request->validate([
        'answer' => 'required|numeric'
    ]);

    $question = json_decode($match->current_question, true);

    if (!$question) {
        return back()->with('error', 'No question found');
    }

    $correct = $question['answer'];
    $isPlayerOne = auth()->id() == $match->player_one;

    // ✅ MARK PLAYER AS ANSWERED
    if ($isPlayerOne) {
        if ($match->player_one_answered) {
            return back()->with('error', 'Already answered');
        }

        $match->player_one_answered = true;

        if ($request->answer == $correct) {
            $match->player_one_score += 10;
            session()->flash('success', 'Correct!');
        } else {
            session()->flash('error', 'Wrong!');
        }

    } else {
        if ($match->player_two_answered) {
            return back()->with('error', 'Already answered');
        }

        $match->player_two_answered = true;

        if ($request->answer == $correct) {
            $match->player_two_score += 10;
            session()->flash('success', 'Correct!');
        } else {
            session()->flash('error', 'Wrong!');
        }
    }

    // 🔥 ONLY MOVE WHEN BOTH PLAYERS ANSWERED
    if ($match->player_one_answered && $match->player_two_answered) {

        $match->question_number++;

        if ($match->question_number > 8) {
            $match->round++;
            $match->question_number = 1;
        }

        // RESET FOR NEXT QUESTION
        $match->player_one_answered = false;
        $match->player_two_answered = false;

        $match->current_question = null;
    }

    // 🔥 END GAME
    if ($match->round > 5) {
        $this->decideWinner($match);
        return redirect()->route('mp.index')->with('success', 'Match finished!');
    }

    $match->save();

    return redirect()->route('mp.play', $match->id);
}

    private function decideWinner($match)
{
    if ($match->player_one_score > $match->player_two_score) {
        $match->winner = $match->player_one;
    } elseif ($match->player_two_score > $match->player_one_score) {
        $match->winner = $match->player_two;
    } else {
        $match->winner = null;
    }

    $match->status = 'finished';
    $match->ended_at = now();

    // 💰 PAYOUT (SIMULATION)
    if ($match->winner) {
        $winnerProfile = \App\Models\Profile::where('user_id', $match->winner)->first();

        if ($winnerProfile) {
            $winnerProfile->score += ($match->stake * 2); // winner takes all
            $winnerProfile->save();
        }
    }

    $match->save();
}
}