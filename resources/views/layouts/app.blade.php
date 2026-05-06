<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NumberPuzzle') }}</title>

        <!-- Farcaster Frame V2 Meta Tags -->
        <meta property="fc:frame" content='{"version": "next", "imageUrl": "https://reveal-carmaker-goon.ngrok-free.dev/logo.png", "button": {"title": "Play Now", "action": "launch_frame"}}' />


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        
        <style>
            body {
                font-family: 'Outfit', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-100 min-h-screen flex flex-col bg-slate-950">
        <!-- Global Profile Navbar -->
        <header class="w-full glass-dark border-b border-white/10 px-4 py-2.5 flex justify-between items-center z-50 sticky top-0 backdrop-blur-md">
            <a href="{{ auth()->check() ? url('/dashboard') : url('/') }}" class="flex items-center gap-2 group">
                <div class="w-7 h-7 rounded-lg bg-slate-900 border border-white/10 flex items-center justify-center group-hover:border-purple-500/50 transition-all">
                    <img src="{{ asset('logo.png') }}" class="w-5 h-5 object-contain">
                </div>
                <span class="font-black text-xs tracking-tight uppercase text-slate-300">Number<span class="text-purple-400">Puzzle</span></span>
            </a>
            
            @auth
                <div class="flex items-center gap-3">
                    <form action="{{ url('/disconnect-wallet') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 hover:opacity-80 transition-all cursor-pointer group/profile border-none bg-transparent p-0 m-0 outline-none">
                            <div class="flex flex-col items-end">
                                <span class="text-[10px] font-bold text-white leading-none">{{ Auth::user()->name }}</span>
                                <span class="text-[8px] text-slate-500 uppercase tracking-tighter group-hover/profile:text-rose-400 transition-colors">Logout</span>
                            </div>
                            @if(Auth::user()->farcaster_pfp)
                                <img src="{{ Auth::user()->farcaster_pfp }}" class="w-8 h-8 rounded-full border border-purple-500/30 shadow-lg shadow-purple-500/10 group-hover/profile:border-rose-500/50 transition-all">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-800 to-slate-900 border border-white/10 flex items-center justify-center text-xs font-bold text-slate-400 group-hover/profile:border-rose-500/50">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </button>
                    </form>
                </div>
            @endauth
        </header>
        
        <!-- Page Heading -->
        @isset($header)
            <header class="glass-dark border-b border-white/10 z-10 relative">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="flex-1 w-full max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 flex flex-col z-0 relative">
            @yield('content')
        </main>
    </body>
</html>
