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

</div>

@endsection