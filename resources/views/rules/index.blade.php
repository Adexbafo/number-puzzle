@extends('layouts.app')

@section('content')

<div class="space-y-4">

    <h1 class="text-xl font-bold">How to Play</h1>

    <ul class="list-disc pl-5 space-y-2">
        <li>Each round contains 8 questions</li>
        <li>You have 20 seconds per question</li>
        <li>Find the missing number in the sequence</li>
        <li>Wrong answer reduces your lifeline</li>
    </ul>

    <h2 class="text-lg font-bold mt-4">Levels</h2>

    <ul class="list-disc pl-5 space-y-2">
        <li>Amateur → Professional after 10 rounds without losing lifelines</li>
        <li>Professional players appear on leaderboard</li>
        <li>Top 100 players receive rewards weekly</li>
    </ul>

    <div class="pt-6">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl font-bold text-sm border border-white/10 transition-all duration-300">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Back to Home
        </a>
    </div>

</div>


@endsection