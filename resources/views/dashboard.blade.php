@extends('layouts.app')

@section('content')

@php use Illuminate\Support\Str; @endphp

<div class="space-y-8 animate-float-up fade-in max-w-4xl mx-auto w-full">

    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-bold tracking-tight text-glow text-white">Dashboard</h2>
        
        <form method="POST" action="{{ url('/disconnect-wallet') }}">
            @csrf
            <button class="px-4 py-2 rounded-full glass-card text-red-400 hover:text-red-300 hover:bg-red-500/10 text-sm font-semibold transition-all">
                Disconnect
            </button>
        </form>
    </div>

    <!-- Player Stats -->
    <div class="glass p-6 sm:p-8 rounded-3xl space-y-6 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl"></div>

        <div class="relative z-10">
            @if($profile)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="glass-card p-4 rounded-2xl flex flex-col items-center justify-center">
                        <span class="text-slate-400 text-sm mb-1 uppercase tracking-wider font-semibold">Level</span> 
                        <span class="font-bold text-2xl text-white">{{ ucfirst($profile->level) }}</span>
                    </div>

                    <div class="glass-card p-4 rounded-2xl flex flex-col items-center justify-center">
                        <span class="text-slate-400 text-sm mb-1 uppercase tracking-wider font-semibold">Score</span> 
                        <span class="font-bold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ $profile->score }}</span>
                    </div>

                    <div class="glass-card p-4 rounded-2xl flex flex-col items-center justify-center">
                        <span class="text-slate-400 text-sm mb-1 uppercase tracking-wider font-semibold">Lifelines</span> 
                        <span class="font-bold text-2xl text-rose-400 flex items-center gap-1">
                            {{ $profile->lifelines }}
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                        </span>
                    </div>

                    <div class="glass-card p-4 rounded-2xl flex flex-col items-center justify-center text-center">
                        <span class="text-slate-400 text-sm mb-1 uppercase tracking-wider font-semibold">Wallet</span>
                        @if($profile->wallet_address)
                            <span class="font-mono text-sm text-emerald-400 bg-emerald-400/10 px-2 py-1 rounded-md mt-1 border border-emerald-400/20">
                                {{ Str::limit($profile->wallet_address, 10) }}
                            </span>
                        @else
                            <button onclick="connectWallet()" class="mt-1 bg-gradient-to-r from-purple-500 to-indigo-600 px-4 py-1.5 rounded-full text-sm font-semibold hover:scale-105 transition-transform shadow-lg shadow-purple-500/20">
                                Connect
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-8 text-slate-400">
                    <p>No profile found. Please connect your wallet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <a href="{{ route('game.start') }}" class="group glass-card p-6 rounded-3xl relative overflow-hidden flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-all duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-16 h-16 rounded-full bg-blue-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500 border border-blue-500/30 shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-1">Start Game</h3>
            <p class="text-sm text-slate-400">Play the single player mode</p>
        </a>

        <a href="{{ route('mp.index') }}" class="group glass-card p-6 rounded-3xl relative overflow-hidden flex flex-col items-center justify-center text-center hover:-translate-y-1 transition-all duration-300">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="w-16 h-16 rounded-full bg-purple-500/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-500 border border-purple-500/30 shadow-[0_0_15px_rgba(168,85,247,0.3)]">
                <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-1">Multiplayer</h3>
            <p class="text-sm text-slate-400">Challenge other players</p>
        </a>
        
        <div class="flex flex-col gap-6">
            <a href="{{ route('leaderboard') }}" class="group glass-card p-4 rounded-2xl relative overflow-hidden flex items-center gap-4 hover:-translate-y-1 transition-all duration-300 flex-1">
                <div class="w-12 h-12 shrink-0 rounded-full bg-amber-500/20 flex items-center justify-center border border-amber-500/30">
                    <svg class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-white">Leaderboard</h3>
                    <p class="text-xs text-slate-400">Top players globally</p>
                </div>
            </a>

            <a href="{{ route('rules') }}" class="group glass-card p-4 rounded-2xl relative overflow-hidden flex items-center gap-4 hover:-translate-y-1 transition-all duration-300 flex-1">
                <div class="w-12 h-12 shrink-0 rounded-full bg-emerald-500/20 flex items-center justify-center border border-emerald-500/30">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <div>
                    <h3 class="font-bold text-white">How to Play</h3>
                    <p class="text-xs text-slate-400">Rules & instructions</p>
                </div>
            </a>
        </div>

    </div>

</div>

<script>
async function connectWallet() {
    if (window.ethereum) {
        try {
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
        } catch (error) {
            console.error("User rejected request", error);
        }
    } else {
        alert('No wallet found. Install MetaMask.');
    }
}
</script>

@endsection