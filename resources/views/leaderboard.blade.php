@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-10 pb-20">

    {{-- Title & Reward Pool Header --}}
    <div class="text-center space-y-6 fade-in-up">
        <h2 class="text-4xl sm:text-6xl font-black tracking-tight text-white uppercase">
            Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-500">Leaderboard</span>
        </h2>
        
        <div class="glass-dark border border-amber-500/30 rounded-3xl p-8 shadow-2xl relative overflow-hidden inline-block w-full max-w-2xl">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-amber-500/10 rounded-full blur-[100px]"></div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="text-slate-400 text-xs font-bold uppercase tracking-[0.3em] mb-2">Current Weekly Reward Pool</span>
                <div class="text-5xl sm:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-200 via-yellow-400 to-orange-500 mb-2" id="reward-pool-lb">
                    10,000 $FARB
                </div>
                <p class="text-slate-500 text-sm italic">Distributed to the Top 100 Professional players every Sunday</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-12">
        
        {{-- PROFESSIONAL --}}
        <div class="space-y-6 fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center border border-purple-500/30 shadow-lg shadow-purple-500/20">
                    <span class="text-xl">🔥</span>
                </div>
                <h3 class="text-2xl font-black text-white uppercase tracking-tight">Professional <span class="text-purple-400">Elite</span></h3>
            </div>

            <div class="glass-dark border border-white/10 rounded-[2rem] overflow-hidden shadow-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-widest border-b border-white/10">
                            <th class="px-6 py-5">Rank</th>
                            <th class="px-6 py-5">Challenger</th>
                            <th class="px-6 py-5 text-right">Sequence Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($proLeaders as $index => $leader)
                        <tr class="group hover:bg-white/5 transition-colors duration-300">
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center w-10 h-10 rounded-xl font-black text-lg {{ $index < 3 ? 'bg-gradient-to-br from-amber-400 to-orange-600 text-white shadow-lg' : 'bg-slate-900/50 text-slate-500 border border-white/5' }}">
                                    @if($index == 0) 1
                                    @elseif($index == 1) 2
                                    @elseif($index == 2) 3
                                    @else {{ $index + 1 }}
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 border border-white/10 flex items-center justify-center text-sm font-bold text-white uppercase">
                                        {{ substr($leader->user->name, 0, 1) }}
                                    </div>
                                    <span class="text-white font-bold group-hover:text-purple-400 transition-colors">{{ $leader->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right">
                                <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">
                                    {{ number_format($leader->score) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-20 text-center text-slate-500 italic">No professional challengers yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- AMATEUR --}}
        <div class="space-y-6 fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center gap-3 px-2">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center border border-blue-500/30">
                    <span class="text-xl">🧩</span>
                </div>
                <h3 class="text-2xl font-black text-white uppercase tracking-tight">Amateur <span class="text-blue-400">Rising</span></h3>
            </div>

            <div class="glass-dark border border-white/10 rounded-[2rem] overflow-hidden opacity-80">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/5 text-slate-500 text-xs font-bold uppercase tracking-widest border-b border-white/10">
                            <th class="px-6 py-4">Rank</th>
                            <th class="px-6 py-4">Player</th>
                            <th class="px-6 py-4 text-right">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse($amateurLeaders as $index => $leader)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-slate-500 text-sm">#{{ $index + 1 }}</span>
                            </td>
                            <td class="px-6 py-4 text-white font-semibold">{{ $leader->user->name }}</td>
                            <td class="px-6 py-4 text-right text-white font-black">{{ number_format($leader->score) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-10 text-center text-slate-600 italic">The training grounds are empty.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const display = document.getElementById('reward-pool-lb');
    if (window.getRewardPoolBalance) {
        const balance = await window.getRewardPoolBalance();
        display.innerText = Number(balance).toLocaleString() + ' $FARB';
    }
});
</script>

@endsection