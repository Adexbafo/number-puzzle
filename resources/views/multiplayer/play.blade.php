@extends('layouts.app')

@section('content')

<div class="text-center space-y-6">

    <h2 class="text-xl font-bold">⚔ Match #{{ $match->id }}</h2>

    <!-- Timer -->
    <div class="text-2xl font-bold">
        ⏱ <span id="timer">20</span>
    </div>

    <!-- Question -->
    <div class="text-3xl font-bold">
        {{ $q['pattern'] }}
    </div>

    <!-- Answer Form -->
    <form id="mp-form" method="POST" action="{{ route('mp.submit', $match->id) }}">
        @csrf

        <!-- Honeypot -->
        <input type="text" name="hidden_field" style="display:none">

        <input 
            type="number"
            name="answer"
            autocomplete="off"
            class="p-3 text-black rounded w-full"
            placeholder="Enter missing number"
            required
        >

        <button class="bg-blue-600 p-3 mt-4 rounded w-full">
            Submit
        </button>
    </form>

</div>

<script>
let timeLeft = 20;

const timer = setInterval(() => {
    timeLeft--;
    document.getElementById('timer').innerText = timeLeft;

    if (timeLeft <= 0) {
        clearInterval(timer);
        document.getElementById('mp-form').submit();
    }
}, 1000);
</script>

@endsection