@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto space-y-8 animate-float-up fade-in w-full">

    {{-- Feedback Messages --}}
    @if(session('success'))
        <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 px-6 py-3 rounded-2xl text-center fade-in backdrop-blur-sm shadow-[0_0_15px_rgba(16,185,129,0.2)] font-medium">
            ✨ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-500/20 border border-rose-500/50 text-rose-300 px-6 py-3 rounded-2xl text-center fade-in backdrop-blur-sm shadow-[0_0_15px_rgba(244,63,94,0.2)] font-medium">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Top Stats Bar --}}
    <div class="glass p-5 rounded-3xl flex justify-between items-center text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-pink-500/5"></div>
        
        <div class="relative z-10 text-center px-4">
            <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Level</p>
            <p class="text-xl font-bold text-white">{{ ucfirst($profile->level) }}</p>
        </div>

        <div class="relative z-10 flex-1 px-6 border-x border-white/10">
            <div class="flex justify-between items-end mb-2">
                <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold">Progress</p>
                <p class="text-sm font-bold text-emerald-400">Q{{ $question_number }} <span class="text-slate-500">/ 8</span></p>
            </div>
            {{-- Progress Bar --}}
            <div class="w-full bg-slate-800/50 rounded-full h-2 shadow-inner overflow-hidden">
                <div 
                    class="bg-gradient-to-r from-emerald-400 to-cyan-400 h-2 rounded-full transition-all duration-500 shadow-[0_0_10px_rgba(52,211,153,0.5)]"
                    style="width: {{ ($question_number / 8) * 100 }}%">
                </div>
            </div>
        </div>

        <div class="relative z-10 text-center px-4">
            <p class="text-xs uppercase tracking-wider text-slate-400 font-semibold mb-1">Lives</p>
            <div class="flex justify-center space-x-0.5">
                @for($i = 0; $i < $profile->lifelines; $i++)
                    <svg class="w-5 h-5 text-rose-500 drop-shadow-[0_0_5px_rgba(244,63,94,0.5)] animate-pulse" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                @endfor
                @for($i = $profile->lifelines; $i < 5; $i++)
                    <svg class="w-5 h-5 text-slate-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                @endfor
            </div>
        </div>
    </div>

    {{-- Main Game Area --}}
    <div class="glass-dark p-8 sm:p-12 rounded-[2.5rem] relative overflow-hidden shadow-2xl border border-white/10">
        <!-- Glow effects -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-32 bg-blue-500/20 blur-[50px] rounded-full pointer-events-none"></div>

        {{-- Timer --}}
        <div class="flex justify-center mb-8 relative z-10">
            <div class="relative w-24 h-24 flex items-center justify-center rounded-full glass-card border border-amber-500/30 shadow-[0_0_20px_rgba(245,158,11,0.2)]">
                <svg class="absolute inset-0 w-full h-full -rotate-90 text-transparent" viewBox="0 0 100 100">
                    <circle cx="50" cy="50" r="46" stroke="currentColor" stroke-width="4" fill="none" class="text-amber-500/20"/>
                    <circle id="timer-circle" cx="50" cy="50" r="46" stroke="currentColor" stroke-width="4" fill="none" class="text-amber-400 transition-all duration-1000 ease-linear" stroke-dasharray="289" stroke-dashoffset="0" stroke-linecap="round"/>
                </svg>
                <div class="text-center">
                    <span id="timer" class="text-3xl font-black text-amber-400 text-glow">20</span>
                    <span class="block text-[0.65rem] uppercase font-bold text-amber-500/70 -mt-1 tracking-widest">SEC</span>
                </div>
            </div>
        </div>

        {{-- Question Box --}}
        <div 
            id="question-box"
            class="text-center py-10 px-4 rounded-3xl bg-gradient-to-br from-slate-800 to-slate-900 border border-slate-700/50 shadow-inner mb-8 relative z-10 transition-all duration-300"
        >
            <p class="text-sm uppercase tracking-widest text-slate-400 font-semibold mb-4">Complete the sequence</p>
            <h2 class="text-4xl sm:text-5xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white to-slate-300 tracking-wider">
                {{ $q['pattern'] }}
            </h2>
        </div>

        {{-- Answer Input --}}
        <form id="answer-form" method="POST" action="{{ route('game.submit') }}" class="relative z-10 max-w-sm mx-auto">
            @csrf

            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl blur opacity-30 group-focus-within:opacity-70 transition duration-500"></div>
                <input 
                    type="number" 
                    name="answer"
                    class="relative w-full bg-slate-900/80 border border-white/10 text-white text-center text-3xl font-bold py-4 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500/50 backdrop-blur-sm placeholder:text-slate-600 placeholder:text-xl placeholder:font-medium transition-all"
                    placeholder="?"
                    autocomplete="off"
                    autofocus
                    required
                >
            </div>

            <button class="mt-6 w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white font-bold text-lg py-4 px-8 rounded-2xl shadow-[0_0_20px_rgba(147,51,234,0.4)] hover:shadow-[0_0_30px_rgba(147,51,234,0.6)] transform hover:-translate-y-1 transition-all duration-300">
                Submit Answer
            </button>
        </form>

    </div>
</div>

{{-- Timer Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    let timeLeft = 20;
    const timerEl = document.getElementById('timer');
    const circleEl = document.getElementById('timer-circle');
    const totalDash = 289; // 2 * pi * r (46)
    
    // Focus input on load
    const inputEl = document.querySelector('input[name="answer"]');
    if(inputEl) inputEl.focus();

    const timer = setInterval(() => {
        timeLeft--;
        timerEl.innerText = timeLeft;
        
        // Update circle
        const offset = totalDash - (timeLeft / 20) * totalDash;
        if(circleEl) circleEl.style.strokeDashoffset = offset;
        
        // Color change warning
        if (timeLeft <= 5) {
            timerEl.classList.remove('text-amber-400');
            timerEl.classList.add('text-rose-500', 'animate-pulse');
            if(circleEl) {
                circleEl.classList.remove('text-amber-400');
                circleEl.classList.add('text-rose-500');
            }
        }

        if (timeLeft <= 0) {
            clearInterval(timer);
            document.querySelector('input[name="answer"]').value = "";
            document.getElementById('answer-form').submit();
        }
    }, 1000);

    // Animation Triggers
    const box = document.getElementById('question-box');

    @if(session('success'))
        box.classList.add('glow');
        // Add success color temporarily
        box.classList.replace('from-slate-800', 'from-emerald-900/50');
        box.classList.replace('to-slate-900', 'to-emerald-950/50');
        box.classList.replace('border-slate-700/50', 'border-emerald-500/50');
    @endif

    @if(session('error'))
        box.classList.add('shake');
        // Add error color temporarily
        box.classList.replace('from-slate-800', 'from-rose-900/50');
        box.classList.replace('to-slate-900', 'to-rose-950/50');
        box.classList.replace('border-slate-700/50', 'border-rose-500/50');
    @endif
});
</script>

@endsection