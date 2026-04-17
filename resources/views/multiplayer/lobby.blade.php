@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <h2 class="text-xl font-bold">⚔ Multiplayer Lobby</h2>

    <!-- Create Match -->
    <div class="bg-gray-800 p-4 rounded">
        <form method="POST" action="{{ route('mp.create') }}">
            @csrf

            <label class="block mb-2">Stake Amount</label>

            <input 
                type="number" 
                name="stake" 
                class="w-full p-2 text-black rounded"
                placeholder="Enter stake"
                required
            >

            <button class="bg-blue-600 mt-3 p-2 w-full rounded">
                Create Match
            </button>
        </form>
    </div>

    <!-- Available Matches -->
    <div class="bg-gray-800 p-4 rounded">

        <h3 class="mb-3">Available Matches</h3>

        @forelse($matches as $match)
            <div class="flex justify-between items-center mb-2 bg-gray-700 p-2 rounded">

                <div>
                    <p>Stake: {{ $match->stake }}</p>
                    <p class="text-sm text-gray-400">Player waiting...</p>
                </div>

                <a href="{{ route('mp.join', $match->id) }}" 
                   class="bg-green-600 px-3 py-1 rounded">
                    Join
                </a>

            </div>
        @empty
            <p>No matches available</p>
        @endforelse

    </div>

</div>

@endsection