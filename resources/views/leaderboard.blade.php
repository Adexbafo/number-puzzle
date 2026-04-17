@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto space-y-8">

    <h2 class="text-2xl font-bold text-center">🏆 Leaderboard</h2>

    {{-- PROFESSIONAL --}}
    <div class="bg-gray-800 p-4 rounded shadow-lg">
        <h3 class="text-lg font-bold text-purple-400 mb-3">
            🔥 Professional (Top 100 - Rewarded)
        </h3>

        <table class="w-full text-left">
            <tr class="text-gray-400 border-b border-gray-600">
                <th>#</th>
                <th>User</th>
                <th>Score</th>
            </tr>

            @foreach($proLeaders as $index => $leader)
            <tr class="fade-in border-b border-gray-700 hover:bg-gray-700 transition">
                <td>
                    @if($index == 0) 🥇
                    @elseif($index == 1) 🥈
                    @elseif($index == 2) 🥉
                    @else {{ $index + 1 }}
                      @endif
                </td>
                <td class="text-white font-medium">
                 {{ $leader->user->name }}
                </td>
                <td class="text-green-400 font-bold">{{ $leader->score }}</td>
            </tr>
            @endforeach
        </table>
    </div>

    {{-- AMATEUR --}}
    <div class="bg-gray-800 p-4 rounded shadow-lg">
        <h3 class="text-lg font-bold text-yellow-400 mb-3">
            🧩 Amateur Players
        </h3>

        <table class="w-full text-left">
            <tr class="text-gray-400 border-b border-gray-600">
                <th>#</th>
                <th>User</th>
                <th>Score</th>
            </tr>

            @foreach($amateurLeaders as $index => $leader)
            <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                <td>
                @if($index == 0) 🥇
                @elseif($index == 1) 🥈
                @elseif($index == 2) 🥉
                @else {{ $index + 1 }}
                   @endif
                </td>
                <td class="text-white font-medium">
                 {{ $leader->user->name }}
                </td>
                <td class="text-white">{{ $leader->score }}</td>
            </tr>
            @endforeach
        </table>
    </div>

</div>

@endsection