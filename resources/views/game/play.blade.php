@extends('layouts.app')

@section('content')

<div class="max-w-md mx-auto space-y-6">

    {{-- Feedback --}}
    @if(session('success'))
        <div class="bg-green-600 p-2 rounded text-center fade-in">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-600 p-2 rounded text-center fade-in">
            {{ session('error') }}
        </div>
    @endif

    {{-- Player Stats --}}
    <div class="bg-gray-800 p-4 rounded text-center space-y-3 shadow-lg text-white">

        {{-- Level --}}
        <div>
            <p class="text-sm text-gray-400">Level</p>
            <p class="text-lg font-bold">{{ $profile->level }}</p>
        </div>

        {{-- Round --}}
        <div>
            <p class="text-sm text-gray-400">Round</p>
            <p class="text-lg font-bold">{{ $round }}</p>
        </div>

        {{-- Question --}}
        <div>
            <p class="text-sm text-gray-400">Question</p>
            <p class="text-lg font-bold">{{ $question_number }}/8</p>

            {{-- Progress Bar --}}
            <div class="w-full bg-gray-700 rounded h-2 mt-2">
                <div 
                    class="bg-green-500 h-2 rounded transition-all duration-300"
                    style="width: {{ ($question_number / 8) * 100 }}%">
                </div>
            </div>
        </div>

        {{-- Lifelines --}}
        <div>
            <p class="text-sm text-gray-400">Lifelines</p>

            <div class="flex justify-center space-x-1 mt-1">
                @for($i = 0; $i < $profile->lifelines; $i++)
                    <span class="text-red-500 text-xl">❤️</span>
                @endfor

                @for($i = $profile->lifelines; $i < 5; $i++)
                    <span class="text-gray-600 text-xl">🤍</span>
                @endfor
            </div>
        </div>

    </div>

    {{-- Timer --}}
    <div class="text-center text-2xl font-bold">
        ⏱ <span id="timer">20</span>
    </div>

    {{-- Question --}}
    <div 
    id="question-box"
    class="text-center text-3xl font-bold p-4 rounded bg-gray-800 text-white"
>
        {{ $q['pattern'] }}
    </div>

    {{-- Answer --}}
    <form id="answer-form" method="POST" action="{{ route('game.submit') }}">
        @csrf

        <input 
            type="number" 
            name="answer"
            class="p-3 text-black rounded w-full"
            placeholder="Enter missing number"
            required
        >

        <button class="bg-blue-600 p-3 mt-4 rounded w-full hover:bg-blue-700 transition">
            Submit
        </button>
    </form>

</div>

{{-- Timer Script --}}
<script>
let timeLeft = 20;

const timer = setInterval(() => {
    timeLeft--;
    document.getElementById('timer').innerText = timeLeft;

    if (timeLeft <= 0) {
        clearInterval(timer);
        document.querySelector('input[name="answer"]').value = "";
        document.getElementById('answer-form').submit();
    }
}, 1000);
</script>

{{-- Animation Trigger --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('question-box');

    @if(session('success'))
        box.classList.add('glow');
    @endif

    @if(session('error'))
        box.classList.add('shake');
    @endif
});
</script>

@endsection