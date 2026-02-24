<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Pitocom - Your PC Building Partner')</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .nav-link-hover { position: relative; padding-bottom: 4px; }
        .nav-link-hover::after {
            content: ''; position: absolute; width: 0; height: 2px; bottom: 0; left: 50%;
            background: linear-gradient(90deg, #0ea5e9, #6366f1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateX(-50%); border-radius: 100px;
        }
        .nav-link-hover:hover::after { width: 80%; }

        /* Animasi Text Hero (Daya Tarik Konsumen) */
        @keyframes textReveal {
            0% { transform: translateY(100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        .reveal-text { display: inline-block; animation: textReveal 1s cubic-bezier(0.77, 0, 0.175, 1) forwards; }

        /* Animasi Entri Halaman */
        @keyframes fadeInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1) forwards; }

        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-slate-800 antialiased overflow-x-hidden">

    {{-- Navbar Premium --}}
    <header class="glass-nav sticky top-0 z-[100] border-b border-white/20">
        <nav x-data="{ open: false, atTop: true }" 
             @scroll.window="atTop = (window.pageYOffset > 20 ? false : true)"
             :class="atTop ? 'py-6' : 'py-3 shadow-xl shadow-slate-900/5'"
             class="max-w-[1600px] mx-auto px-6 sm:px-10 flex justify-between items-center transition-all duration-500">
            
            {{-- Logo --}}
            <a href="{{ route('welcome') }}" class="flex items-center space-x-3 group shrink-0">
                <div class="bg-slate-900 p-2 rounded-[1.2rem] group-hover:rotate-[15deg] group-hover:scale-110 transition-all duration-500 shadow-lg text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-900">PITO<span class="text-sky-500">COM</span></span>
            </a>
            
            {{-- Nav Center --}}
            <div class="hidden lg:flex items-center space-x-10 flex-grow justify-center pl-10">
                <a href="{{ route('products.index') }}" class="nav-link-hover text-[13px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition shrink-0">Inventory</a>
                <a href="{{ route('pc-builder.index') }}" class="nav-link-hover text-[13px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition shrink-0">Builder</a>
                <a href="{{ route('community-builds.index') }}" class="nav-link-hover text-[13px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition shrink-0">Community</a>
                
                {{-- Categories Dropdown - FIX: Menambahkan py-4 sebagai "jembatan" kursor --}}
                <div class="relative py-4" x-data="{ drop: false }" @mouseenter="drop = true" @mouseleave="drop = false">
                    <button class="nav-link-hover text-[13px] font-black uppercase tracking-widest text-slate-500 hover:text-slate-900 transition flex items-center shrink-0 focus:outline-none">
                        Categories 
                        <svg class="h-3 w-3 ml-2 transition-transform duration-300" :class="drop ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    
                    {{-- Dropdown Menu Box --}}
                    <div x-show="drop" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         class="absolute top-full -left-10 w-64 bg-white/95 backdrop-blur-md rounded-[2rem] shadow-2xl border border-slate-100 py-6 mt-0 z-50 text-left">
                        <div class="px-6 mb-4">
                            <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.2em]">Select Hardware</span>
                        </div>
                        <a href="{{ route('products.index', ['category' => 'gaming-pc']) }}" class="flex items-center px-6 py-3 text-sm font-bold text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-3"></span> Gaming Systems
                        </a>
                        <a href="{{ route('products.index', ['category' => 'workstation']) }}" class="flex items-center px-6 py-3 text-sm font-bold text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-3"></span> Workstations
                        </a>
                        <a href="{{ route('products.index', ['category' => 'custom-parts']) }}" class="flex items-center px-6 py-3 text-sm font-bold text-slate-600 hover:bg-sky-50 hover:text-sky-600 transition">
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-3"></span> Custom Parts
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Actions --}}
            <div class="hidden lg:flex items-center space-x-6 shrink-0">
                <form action="{{ route('products.index') }}" method="GET" class="relative group">
                    <input type="search" name="search" placeholder="Search components..." 
                           class="w-40 xl:w-56 pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-[1.2rem] focus:ring-2 focus:ring-sky-500/20 focus:bg-white transition-all text-xs font-bold text-slate-600">
                    <svg class="w-4 h-4 absolute left-3.5 top-3 text-slate-400 group-focus-within:text-sky-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>

                @guest
                    <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-sky-500 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-slate-900 text-white px-8 py-3 rounded-[1.2rem] text-xs font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl active:scale-95">Join Us</a>
                @endguest
                
                @auth
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('cart.index') }}" class="relative p-3 bg-white border border-slate-100 rounded-[1.2rem] text-slate-400 hover:text-sky-500 hover:shadow-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                            @if(session('cart')) <span class="absolute -top-1 -right-1 bg-rose-500 text-white text-[9px] font-black rounded-full w-5 h-5 flex items-center justify-center border-2 border-white">{{ count(session('cart')) }}</span> @endif
                        </a>
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="w-10 h-10 rounded-[1rem] bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 hover:bg-sky-500 hover:text-white transition-all overflow-hidden group">
                            <svg class="w-5 h-5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" class="p-2 text-rose-300 hover:text-rose-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

            {{-- Mobile Nav Toggle --}}
            <button @click="open = !open" class="lg:hidden p-3 bg-slate-100 rounded-2xl text-slate-600 hover:bg-sky-500 hover:text-white transition-all">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </nav>
    </header>

    <main class="min-h-screen"> @yield('content') </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 pt-24 pb-12 text-white overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-500 via-indigo-500 to-sky-500"></div>
        <div class="max-w-[1600px] mx-auto px-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-20 text-left">
                <div class="space-y-8">
                    <a href="#" class="text-3xl font-black tracking-tighter">PITO<span class="text-sky-400">COM</span></a>
                    <p class="text-slate-400 leading-relaxed font-medium">Handcrafted hardware for elite builders. Kualitas terbaik sejak 2026.</p>
                </div>
                <div class="text-left">
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-10 text-slate-500">Resources</h4>
                    <ul class="space-y-5 text-sm font-bold text-slate-400">
                        <li><a href="#" class="hover:text-sky-400 transition-colors text-left">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors text-left">Cara Rakit PC</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors text-left">Garansi Resmi</a></li>
                    </ul>
                </div>
                <div class="lg:col-span-2 text-left">
                    <h4 class="text-xs font-black uppercase tracking-[0.3em] mb-10 text-slate-500">Newsletter</h4>
                    <form class="flex space-x-3">
                        <input type="email" placeholder="Email address" class="bg-white/5 border border-white/10 rounded-2xl px-6 py-4 flex-1 text-sm focus:ring-2 focus:ring-sky-500 transition-all outline-none">
                        <button class="bg-sky-500 hover:bg-sky-400 text-white font-black uppercase tracking-widest text-[11px] px-8 rounded-2xl transition-all">Subscribe</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
    
    @stack('scripts')
</body>
</html>