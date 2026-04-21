<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'NumberPuzzle') }} - The Ultimate Brain Teaser</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        
        <style>
            body { font-family: 'Outfit', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-100 min-h-screen flex flex-col relative overflow-hidden">
        
        <!-- Background elements -->
        <div class="absolute inset-0 bg-slate-950 z-0"></div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-purple-600/30 rounded-full blur-[100px] z-0 animate-float"></div>
        <div class="absolute top-40 -right-40 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px] z-0 animate-float" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-40 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-emerald-600/20 rounded-full blur-[150px] z-0 animate-float" style="animation-delay: 4s;"></div>

        <div class="relative z-10 flex flex-col min-h-screen">
            <!-- Navbar -->
            <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center fade-in-up">
                <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                    <div class="w-12 h-12 rounded-xl bg-slate-900/50 border border-white/10 flex items-center justify-center shadow-2xl group-hover:scale-110 transition-all duration-300">
                        <img src="{{ asset('logo.png') }}" alt="NumberPuzzle Logo" class="w-10 h-10 object-contain">
                    </div>
                    <span class="text-2xl font-black tracking-tighter text-white group-hover:text-purple-400 transition-colors">Number<span class="text-slate-400">Puzzle</span></span>
                </a>
                
                @auth
                    <nav class="flex gap-4">
                        <a href="{{ url('/dashboard') }}" class="glass-card px-5 py-2 rounded-full text-sm font-semibold hover:text-white hover:bg-white/20 transition-all">
                            Dashboard
                        </a>
                    </nav>
                @endauth
            </header>

            <!-- Hero Section -->
            <main class="flex-1 flex flex-col items-center justify-center text-center px-4 sm:px-6 w-full max-w-5xl mx-auto -mt-16">
                
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full glass-dark border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-widest mb-8 fade-in-up" style="animation-delay: 0.1s;">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    Multiplayer Beta Live
                </div>

                <h1 class="text-5xl sm:text-7xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-br from-white via-slate-200 to-slate-500 tracking-tight mb-6 fade-in-up leading-tight" style="animation-delay: 0.2s;">
                    Crack the code.<br>Claim the <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-cyan-400">victory.</span>
                </h1>
                
                <p class="mt-4 text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10 fade-in-up font-medium" style="animation-delay: 0.3s;">
                    Test your logic, spot the patterns, and climb the global leaderboard. The ultimate sequence solving challenge awaits.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto fade-in-up" style="animation-delay: 0.4s;">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white rounded-2xl font-bold text-lg shadow-[0_0_30px_rgba(147,51,234,0.4)] hover:shadow-[0_0_40px_rgba(147,51,234,0.6)] hover:-translate-y-1 transition-all duration-300">
                            Enter Dashboard
                        </a>
                    @else
                        <button id="connect-btn" onclick="loginWithWallet()" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white rounded-2xl font-bold text-lg shadow-[0_0_30px_rgba(147,51,234,0.4)] hover:shadow-[0_0_40px_rgba(147,51,234,0.6)] hover:-translate-y-1 transition-all duration-300">
                            Connect Wallet to Play
                        </button>
                        <a href="{{ route('rules') }}" class="w-full sm:w-auto px-8 py-4 glass-card text-white rounded-2xl font-bold text-lg hover:-translate-y-1 transition-all duration-300 flex items-center justify-center">
                            How to Play
                        </a>
                    @endauth
                </div>

                <!-- Floating Game Element preview -->
                <div class="mt-20 relative w-full max-w-3xl fade-in-up" style="animation-delay: 0.6s;">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-transparent z-10 h-full w-full pointer-events-none"></div>
                    
                    <div class="glass-dark border border-white/10 rounded-t-3xl p-8 flex flex-col items-center justify-center transform perspective-1000 rotate-x-12 scale-105 shadow-2xl opacity-80">
                        <div class="flex gap-4 mb-8">
                            <div class="w-16 h-16 rounded-2xl glass flex items-center justify-center text-2xl font-black">2</div>
                            <div class="w-16 h-16 rounded-2xl glass flex items-center justify-center text-2xl font-black">4</div>
                            <div class="w-16 h-16 rounded-2xl glass flex items-center justify-center text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-blue-400">8</div>
                            <div class="w-16 h-16 rounded-2xl bg-white/5 border border-dashed border-white/30 flex items-center justify-center text-2xl font-black text-white/30">?</div>
                        </div>
                    </div>
                </div>

            </main>
        </div>

        <script>
        async function loginWithWallet() {
            const btn = document.getElementById('connect-btn');
            const originalText = btn.innerText;
            btn.innerText = 'Connecting...';
            btn.disabled = true;

            try {
                if (typeof window.ethereum !== 'undefined') {
                    console.log('Ethereum provider detected.');
                    
                    const accounts = await window.ethereum.request({
                        method: 'eth_requestAccounts'
                    });

                    const wallet = accounts[0];
                    console.log('Connected wallet:', wallet);

                    const response = await fetch('/auth/wallet', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({ wallet })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        window.location.href = '/dashboard';
                    } else {
                        alert('Failed to log in on the server: ' + (data.error || 'Unknown error'));
                        btn.innerText = originalText;
                        btn.disabled = false;
                    }
                } else {
                    alert('No Web3 wallet (like MetaMask or Rabby) found. Please ensure your extension is enabled.');
                    btn.innerText = originalText;
                    btn.disabled = false;
                }
            } catch (error) {
                console.error("User rejected request or error occurred", error);
                alert("Connection failed: " + error.message);
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }
        </script>
    </body>
</html>
