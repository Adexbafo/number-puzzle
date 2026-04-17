<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameMatch;
use App\Services\GameService;

class MultiplayerController extends Controller
{
    public function index()
    {
        $matches = GameMatch::where('status', 'waiting')->get();

        return view('multiplayer.lobby', compact('matches'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'stake' => 'required|numeric|min:1'
        ]);

        GameMatch::create([
            'player_one' => auth()->id(),
            'stake' => $request->stake,
            'status' => 'waiting'
        ]);

        return redirect('/multiplayer');
    }

    public function join($id)
    {
        $match = GameMatch::findOrFail($id);

        if ($match->player_one == auth()->id()) {
            return back()->with('error', 'You cannot join your own match');
        }

        if ($match->player_two) {
            return back()->with('error', 'Match already full');
        }

        $match->update([
            'player_two' => auth()->id(),
            'status' => 'active',
            'started_at' => now()
        ]);

        return redirect('/multiplayer/match/' . $match->id);
    }

    public function play($id, GameService $service)
    {
        $match = GameMatch::findOrFail($id);

        if (!in_array(auth()->id(), [$match->player_one, $match->player_two])) {
            abort(403);
        }

        if ($match->status !== 'active') {
            return redirect('/multiplayer');
        }

        if (!session()->has('mp_question')) {
            $question = $service->generateQuestion();

            session([
                'mp_question' => $question,
                'mp_answer' => $question['answer'],
                'mp_started_at' => now()
            ]);
        } else {
            $question = session('mp_question');
        }

        return view('multiplayer.play', [
            'match' => $match,
            'q' => $question
        ]);
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|numeric'
        ]);

        $match = GameMatch::findOrFail($id);

        if (!in_array(auth()->id(), [$match->player_one, $match->player_two])) {
            abort(403);
        }

        $correct = session('mp_answer');

        if (!$correct) {
            return redirect('/multiplayer');
        }

        if (now()->diffInSeconds(session('mp_started_at')) > 20) {
            return $this->loseMatch($match);
        }

        if ($request->answer != $correct) {
            return $this->loseMatch($match);
        }

        return redirect()->back();
    }

    private function loseMatch($match)
    {
        $userId = auth()->id();

        $winner = ($match->player_one == $userId)
            ? $match->player_two
            : $match->player_one;

        $match->update([
            'status' => 'finished',
            'winner' => $winner,
            'ended_at' => now()
        ]);

        return redirect('/multiplayer')->with('success', 'Match ended');
    }
}