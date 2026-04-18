@extends('layouts.app')

@section('content')

<div class="max-w-md mx-auto space-y-6">

    {{-- Title --}}
    <h2 class="text-2xl font-bold text-center">⚔ Multiplayer Lobby</h2>

    {{-- Feedback --}}
    @if(session('success'))
        <div class="bg-green-600 p-2 rounded text-center">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 p-2 rounded text-center">
            {{ session('error') }}
        </div>
    @endif

    {{-- Create Match --}}
    <form method="POST" action="{{ route('mp.create') }}" class="space-y-2">
        @csrf

        <input 
            type="number" 
            name="stake" 
            placeholder="Enter stake amount"
            class="p-3 text-black rounded w-full"
            required
        >

        <button class="bg-blue-600 p-3 rounded w-full hover:bg-blue-700 transition">
            ➕ Create Match
        </button>
    </form>

    {{-- Match List --}}
    <div class="space-y-3">

        @forelse($matches as $match)

<div class="bg-gray-800 p-4 rounded flex justify-between items-center shadow">

    {{-- Left: Info --}}
    <div>
        <p class="text-sm text-gray-400">Stake</p>
        <p class="font-bold text-white">{{ $match->stake }}</p>

        <p class="text-xs text-gray-500">
            Status: {{ $match->status }}
        </p>

        {{-- 🏆 WINNER UI --}}
        @if($match->status === 'finished')

            <div class="mt-2">
                <p class="text-green-400 font-bold text-sm">🏆 Match Finished</p>

                @if($match->winner)
                    <p class="text-white text-xs">
                        Winner: {{ \App\Models\User::find($match->winner)?->name ?? 'Unknown' }}
                    </p>
                @else
                    <p class="text-gray-400 text-xs">Draw</p>
                @endif
            </div>

        @endif
    </div>

    {{-- Right: Action --}}
    <div>
        @if($match->player_one == auth()->id() || $match->player_two == auth()->id())

            <a 
                href="{{ route('mp.play', $match->id) }}" 
                class="bg-blue-600 px-4 py-2 rounded hover:bg-blue-700 transition"
            >
                ▶ Resume
            </a>

        @elseif($match->status === 'waiting')

            <a 
                href="{{ route('mp.join', $match->id) }}" 
                class="bg-green-600 px-4 py-2 rounded hover:bg-green-700 transition"
            >
                Join
            </a>

        @else

            <span class="text-gray-400 text-sm">
                In Progress
            </span>

        @endif
    </div>

</div> {{-- ✅ THIS WAS MISSING --}}

@empty

            <div class="text-center text-gray-400 bg-gray-800 p-4 rounded">
                No matches yet. Create one 🚀
            </div>

        @endforelse

    </div>

</div>

@endsection