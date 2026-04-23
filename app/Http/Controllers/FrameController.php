<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrameController extends Controller
{
    /**
     * Show the Farcaster Frame for a specific game/match.
     */
    public function show($id)
    {
        // In a real app, you'd fetch the match status from the DB
        // For now, we'll mock some data to show "delivered status"
        $status = "Game #{$id} is Active";
        $image = url("/api/frame-image?text=" . urlencode($status)); // A mock image generator
        
        return view('frame', [
            'id' => $id,
            'title' => 'NumberPuzzle - Game Status',
            'status' => $status,
            'image' => "https://placehold.co/1200x630/0f172a/ffffff?text=NumberPuzzle+Match+{$id}+Status:+Delivered", // Placeholder for now
            'postUrl' => url("/f/{$id}/click"),
        ]);
    }

    /**
     * Handle button clicks from the Frame.
     */
    public function handleClick(Request $request, $id)
    {
        // Logic for when a user clicks a button in the Frame
        // e.g., refreshing status or joining the game
        return $this->show($id);
    }
}
