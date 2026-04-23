<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FarcasterAuthController extends Controller
{
    /**
     * Handle Farcaster authentication callback.
     */
    public function authenticate(Request $request)
    {
        $data = $request->validate([
            'fid' => 'required|numeric',
            'username' => 'nullable|string',
            'displayName' => 'nullable|string',
            'pfpUrl' => 'nullable|string',
            'verifications' => 'nullable|array', // Contains connected wallet addresses
        ]);

        $fid = $data['fid'];
        $username = $data['username'] ?? 'User_' . $fid;
        $displayName = $data['displayName'] ?? $username;
        $pfpUrl = $data['pfpUrl'] ?? null;
        
        // Use the first verified wallet address if available, or a fallback
        $walletAddress = !empty($data['verifications']) ? strtolower($data['verifications'][0]) : null;

        // Find or create user by FID
        $user = User::where('farcaster_fid', $fid)->first();

        if (!$user) {
            // Try to find by wallet if no FID match
            if ($walletAddress) {
                $user = User::where('wallet_address', $walletAddress)->first();
            }
        }

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $displayName,
                'email' => "fid_{$fid}@farcaster.local",
                'password' => bcrypt(Str::random(16)),
                'wallet_address' => $walletAddress,
                'farcaster_fid' => $fid,
                'farcaster_fname' => $username,
                'farcaster_pfp' => $pfpUrl,
            ]);
            
            // Ensure profile exists
            $user->profile()->create([
                'wallet_address' => $walletAddress,
                'level' => 'Beginner',
                'lifelines' => 3,
                'score' => 0
            ]);
        } else {
            // Update existing user
            $user->update([
                'farcaster_fid' => $fid,
                'farcaster_fname' => $username,
                'farcaster_pfp' => $pfpUrl,
            ]);
            
            if ($walletAddress && !$user->wallet_address) {
                $user->update(['wallet_address' => $walletAddress]);
            }
        }

        // Log the user in
        Auth::login($user);

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }
}
