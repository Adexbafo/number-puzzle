@extends('layouts.app')

@section('content')

<div class="max-w-md mx-auto space-y-6">

    {{-- 🏆 GAME OVER --}}
    @if($match->status === 'finished')
        <div class="bg-green-700 p-4 rounded text-center">
            <h2 class="text-xl font-bold">🏆 Game Over</h2>

            @if($match->winner == auth()->id())
                <p>You Won 🎉</p>
            @elseif($match->winner)
                <p>You Lost 😢</p>
            @else
                <p>Draw 🤝</p>
            @endif
        </div>
    @endif

    {{-- HEADER --}}
    <h2 class="text-2xl font-bold text-center">⚔ Multiplayer Match</h2>

    {{-- ROUND INFO --}}
    <p class="text-center text-sm text-gray-400">
        Round: {{ $match->round }}/5 | Question: {{ $match->question_number }}/8
    </p>

    {{-- MATCH INFO --}}
    <div class="bg-gray-800 p-4 rounded text-center space-y-2">

        <p class="text-sm text-gray-400">Stake</p>
        <p class="text-lg font-bold text-white">{{ $match->stake }}</p>

        <div class="bg-gray-700 p-3 rounded">
            <p>P1: {{ $match->player_one_score }}</p>
            <p>P2: {{ $match->player_two_score }}</p>
        </div>

        <p class="text-sm text-gray-400">Status</p>
        <p class="text-yellow-400 capitalize">{{ $match->status }}</p>

    </div>

    {{-- FEEDBACK --}}
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

    {{-- TIMER --}}
    @if($match->status === 'active')
        <div class="text-center text-2xl font-bold">
            ⏱ <span id="timer">20</span>
        </div>
    @endif

    {{-- QUESTION --}}
    <div 
        id="question-box"
        class="text-center text-3xl font-bold p-4 rounded text-white bg-gray-700"
    >
        {{ $q['pattern'] ?? 'Waiting for opponent...' }}
    </div>

    {{-- PLAYER STATE --}}
    @php
        $isPlayerOne = auth()->id() == $match->player_one;

        $alreadyAnswered = ($isPlayerOne && $match->player_one_answered) || 
                           (!$isPlayerOne && $match->player_two_answered);
    @endphp

    {{-- WAITING STATE --}}
    @if($match->status === 'active' && $alreadyAnswered)
        <p class="text-center text-yellow-400 text-sm">
            Waiting for opponent...
        </p>
    @endif

    {{-- ANSWER FORM --}}
    @if($match->status === 'active' && !$alreadyAnswered)

        <form id="answer-form" method="POST" action="{{ route('mp.submit', $match->id) }}">
            @csrf

            <input 
                type="number" 
                name="answer" 
                class="p-3 text-black w-full mt-4 rounded"
                required
            >

            <button class="bg-blue-600 p-3 mt-2 w-full rounded hover:bg-blue-700 transition">
                Submit
            </button>
        </form>

    @endif

</div>

{{-- TIMER SCRIPT --}}
@if($match->status === 'active')
<script>
let timeLeft = 20;

const timer = setInterval(() => {
    timeLeft--;
    document.getElementById('timer').innerText = timeLeft;

    if (timeLeft <= 0) {
        clearInterval(timer);

        let form = document.getElementById('answer-form');
        if (form) form.submit();
    }
}, 1000);
</script>
@endif

{{-- FEEDBACK ANIMATION --}}
<script>
@if(session('success'))
    document.getElementById('question-box')?.classList.add('glow');
@endif

@if(session('error'))
    document.getElementById('question-box')?.classList.add('shake');
@endif
</script>

@endsection