@extends('layouts.app')

@section('content')

@php use Illuminate\Support\Str; @endphp

<div class="space-y-4">

    <h2 class="text-xl font-bold">Dashboard</h2>

    <!-- Player Stats -->
    <div class="bg-gray-800 p-4 rounded space-y-2">

    @if($profile)
        <p>
        <span class="text-gray-400">Level:</span> 
        <span class="font-bold">{{ $profile->level }}</span>
    </p>

    <p>
        <span class="text-gray-400">Score:</span> 
        <span class="font-bold">{{ $profile->score }}</span>
    </p>

    <p>
        <span class="text-gray-400">Lifelines:</span> 
        <span class="font-bold text-yellow-400">{{ $profile->lifelines }}</span>
    </p>


       @if($profile->wallet_address)

    <p class="text-green-400 text-sm">
        Wallet Connected: {{ Str::limit($profile->wallet_address, 10) }}
    </p>

     @else

    <button onclick="connectWallet()" class="bg-purple-600 p-3 rounded w-full mt-2">
        Connect Wallet
    </button>

    @endif

    @else
        <p>No profile found</p>
    @endif

</div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <a href="{{ route('game.start') }}" class="bg-blue-600 p-4 rounded">
            ▶ Start Game
        </a>

        <a href="{{ route('leaderboard') }}" class="bg-green-600 p-4 rounded">
            🏆 Leaderboard
        </a>

        <a href="{{ route('rules') }}" class="bg-yellow-600 p-4 rounded">
            📜 How to Play
        </a>

        <a href="{{ route('mp.index') }}" class="bg-purple-600 p-4 rounded">
            ⚔ Multiplayer
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="bg-red-600 p-4 rounded w-full">Logout</button>
        </form>

    </div>

</div>

<script>
async function connectWallet() {
    if (window.ethereum) {
        const accounts = await window.ethereum.request({
            method: 'eth_requestAccounts'
        });

        const wallet = accounts[0];

        await fetch('/connect-wallet', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ wallet })
        });

        location.reload();
    } else {
        alert('No wallet found. Install MetaMask.');
    }
}
</script>

@endsection