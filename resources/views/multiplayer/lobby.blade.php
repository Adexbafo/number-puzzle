@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-12 pb-20">

    {{-- Hero/Title Section --}}
    <div class="text-center space-y-4 fade-in-up">
        <h2 class="text-4xl sm:text-5xl font-black tracking-tight text-white">
            Battle <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">Lobby</span>
        </h2>
        <p class="text-slate-400 max-w-lg mx-auto">
            Challenge players worldwide. Stake your tokens, solve the sequence, and claim the reward pool.
        </p>
    </div>

    {{-- Feedback Messages --}}
    @if(session('success'))
        <div class="glass-dark border border-emerald-500/30 p-4 rounded-2xl text-center text-emerald-400 font-semibold animate-pulse">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="glass-dark border border-red-500/30 p-4 rounded-2xl text-center text-red-400 font-semibold">
            {{ session('error') }}
        </div>
    @endif

    {{-- Top Row: Stats & Action --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Create Match Card --}}
        <div class="md:col-span-2 glass-dark border border-white/10 rounded-3xl p-8 shadow-2xl relative overflow-hidden group">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-purple-600/20 rounded-full blur-[80px] group-hover:bg-purple-600/30 transition-all"></div>
            
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-purple-500 flex items-center justify-center text-sm">➕</span>
                Host New Match
            </h3>

            <form method="POST" action="{{ route('mp.create') }}" class="flex flex-col sm:flex-row gap-4">
                @csrf
                <div class="relative flex-1">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">$FARB</span>
                    <input 
                        type="number" 
                        name="stake" 
                        placeholder="Stake Amount"
                        class="w-full bg-slate-900/50 border border-white/10 rounded-2xl py-4 pl-16 pr-4 text-white font-bold focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all outline-none"
                        required
                        min="1"
                    >
                </div>

                <button class="px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-purple-900/20 hover:shadow-purple-900/40 hover:-translate-y-0.5 active:scale-95 transition-all whitespace-nowrap">
                    Create Challenge
                </button>
            </form>
            
            <p class="mt-4 text-xs text-slate-500 italic">
                * Match is active once a second player joins. Winner takes the total pool minus 5% treasury fee.
            </p>
        </div>

        {{-- Quick Stats --}}
        <div class="glass-dark border border-white/10 rounded-3xl p-8 flex flex-col justify-center items-center text-center">
            <div class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Live Matches</div>
            <div class="text-5xl font-black text-white mb-1">{{ $matches->count() }}</div>
            <div class="w-12 h-1 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full mb-4"></div>
            <p class="text-slate-400 text-sm">Join an existing battle below</p>
        </div>
    </div>

    {{-- Match List Section --}}
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-lg font-bold text-white uppercase tracking-widest text-slate-400">Available Battles</h3>
            <span class="text-xs text-slate-500">Auto-refreshing...</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($matches as $match)
                <div class="glass-dark border border-white/10 rounded-3xl p-6 hover:border-purple-500/50 transition-all duration-300 group hover:-translate-y-1 flex flex-col justify-between">
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="space-y-1">
                                <p class="text-[10px] text-slate-500 font-black uppercase tracking-tighter">Prize Pool</p>
                                <p class="text-2xl font-black text-white leading-none">
                                    {{ $match->stake * 2 }} <span class="text-xs text-purple-400">$FARB</span>
                                </p>
                            </div>
                            <div class="px-2 py-1 rounded-md {{ $match->status === 'waiting' ? 'bg-blue-500/10 text-blue-400' : 'bg-emerald-500/10 text-emerald-400' }} text-[10px] font-bold uppercase tracking-widest border border-current opacity-70">
                                {{ $match->status }}
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500">Opponent:</span>
                                <span class="text-slate-200 font-medium">
                                    {{ substr(\App\Models\User::find($match->player_one)?->wallet_address ?? 'Anon Player', 0, 6) }}...
                                </span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-slate-500">Entry Fee:</span>
                                <span class="text-slate-200 font-medium">{{ $match->stake }} $FARB</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        @if($match->player_one == auth()->id() || $match->player_two == auth()->id())
                            <a 
                                href="{{ route('mp.play', $match->id) }}" 
                                class="block text-center py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold transition-all"
                            >
                                Resume Battle
                            </a>
                        @elseif($match->status === 'waiting')
                            <a 
                                href="{{ route('mp.join', $match->id) }}" 
                                class="block text-center py-3 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white rounded-xl font-bold shadow-lg shadow-emerald-900/20 transition-all"
                            >
                                Join Challenge
                            </a>
                        @else
                            <div class="text-center py-3 bg-slate-900/50 text-slate-500 rounded-xl font-bold border border-white/5 italic text-sm">
                                Match Full
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 glass-dark border border-dashed border-white/10 rounded-3xl flex flex-col items-center justify-center text-center space-y-4">
                    <div class="w-16 h-16 rounded-full bg-slate-900/50 flex items-center justify-center text-2xl">🌵</div>
                    <div class="space-y-1">
                        <p class="text-white font-bold text-lg">The lobby is empty.</p>
                        <p class="text-slate-500 text-sm">Be the first to create a match and challenge others!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection