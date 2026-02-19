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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .nav-link-hover {
            position: relative;
        }
        .nav-link-hover::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #0ea5e9;
            transition: width 0.3s ease;
        }
        .nav-link-hover:hover::after {
            width: 100%;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    {{-- Navbar --}}
    <header class="glass-nav sticky top-0 z-50 border-b border-slate-200/50">
        <nav x-data="{ open: false, atTop: true }" 
             @scroll.window="atTop = (window.pageYOffset > 10 ? false : true)"
             class="container mx-auto px-6 py-4 flex justify-between items-center transition-all duration-300">
            
            {{-- Logo --}}
            <a href="{{ route('welcome') }}" class="flex items-center space-x-2 group">
                <div class="bg-sky-500 p-1.5 rounded-[2rem] group-hover:rotate-12 transition-transform">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/></svg>
                </div>
                <span class="text-2xl font-black tracking-tighter text-slate-900">PITO<span class="text-sky-500">COM</span></span>
            </a>
            
            {{-- Desktop Nav --}}
            <div class="hidden lg:flex items-center space-x-8">
                <a href="{{ route('products.index') }}" class="nav-link-hover text-sm font-semibold text-slate-600 hover:text-sky-600 transition">All Products</a>
                
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="nav-link-hover text-sm font-semibold text-slate-600 hover:text-sky-600 transition flex items-center">
                        Categories <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full -left-4 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-3 mt-1 z-50">
                        <a href="#" class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-600 transition">Gaming PC</a>
                        <a href="#" class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-600 transition">Workstation</a>
                        <a href="#" class="block px-5 py-2.5 text-sm text-slate-700 hover:bg-sky-50 hover:text-sky-600 transition">Custom Parts</a>
                    </div>
                </div>

                {{-- Search Bar --}}
                <form action="{{ route('products.index') }}" method="GET" class="relative group">
                    <input type="search" name="search" placeholder="Cari komponen..." 
                           class="w-48 xl:w-64 pl-10 pr-4 py-2 bg-slate-100 border-none rounded-xl focus:ring-2 focus:ring-sky-500 focus:bg-white transition-all text-sm">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-slate-400 group-focus-within:text-sky-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            <div class="hidden lg:flex items-center space-x-5">
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 hover:text-sky-600 transition">Login</a>
                    <a href="{{ route('register') }}" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-sky-600 transition-all shadow-lg shadow-slate-200 active:scale-95">Register</a>
                @endguest
                
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-sky-600 transition">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-600 transition">Logout</button>
                    </form>
                @endauth

                {{-- Cart Button --}}
                <a href="{{ route('cart.index') }}" class="relative p-2 bg-white border border-slate-200 rounded-xl text-slate-600 hover:border-sky-500 hover:text-sky-500 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    @if(session('cart'))
                        <span class="absolute -top-1 -right-1 bg-sky-500 text-white text-[10px] font-bold rounded-[2rem] px-1.5 py-0.5 animate-bounce">{{ count(session('cart')) }}</span>
                    @endif
                </a>
            </div>

            {{-- Mobile Nav Toggle --}}
            <button @click="open = !open" class="lg:hidden p-2 text-slate-600">
                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </nav>

        {{-- Mobile Nav Menu --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="lg:hidden px-6 pb-6 space-y-4 bg-white border-t border-slate-100">
            <a href="{{ route('products.index') }}" class="block py-3 text-base font-bold text-slate-700 border-b border-slate-50">All Products</a>
            @guest
                <a href="{{ route('login') }}" class="block py-3 text-base font-bold text-slate-700">Login</a>
                <a href="{{ route('register') }}" class="block w-full text-center bg-sky-500 text-white py-3 rounded-xl font-bold">Register</a>
            @endguest
            @auth
                <a href="{{ route('dashboard') }}" class="block py-3 text-base font-bold text-slate-700">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left py-3 text-base font-bold text-red-500">Logout</button>
                </form>
            @endauth
        </div>
    </header>

    {{-- Main Content --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-slate-900 pt-20 pb-10 text-white overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-500 via-indigo-500 to-sky-500"></div>
        
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                {{-- Brand Info --}}
                <div class="space-y-6">
                    <a href="#" class="text-3xl font-black tracking-tighter">PITO<span class="text-sky-400">COM</span></a>
                    <p class="text-slate-400 leading-relaxed">
                        Kami adalah partner terpercaya untuk membangun PC impian Anda. Menghadirkan kualitas hardware terbaik sejak 2026.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-sky-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-pink-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-lg font-bold mb-6">Quick Links</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Cara Rakit PC</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Garansi Resmi</a></li>
                    </ul>
                </div>

                {{-- Categories --}}
                <div>
                    <h4 class="text-lg font-bold mb-6">Kategori</h4>
                    <ul class="space-y-4 text-slate-400">
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Processor</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Graphic Cards</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Pre-built Systems</a></li>
                        <li><a href="#" class="hover:text-sky-400 transition-colors">Storage Solutions</a></li>
                    </ul>
                </div>

                {{-- Newsletter --}}
                <div>
                    <h4 class="text-lg font-bold mb-6">Dapatkan Promo</h4>
                    <p class="text-sm text-slate-400 mb-4">Berlangganan untuk mendapatkan update harga terbaru.</p>
                    <form class="flex flex-col space-y-3">
                        <input type="email" placeholder="Email Anda" class="bg-slate-800 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-sky-500">
                        <button class="bg-sky-500 hover:bg-sky-400 text-white font-bold py-3 rounded-xl transition-all active:scale-95">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="mt-20 border-t border-slate-800 pt-8 flex flex-col md:flex-row justify-between items-center text-slate-500 text-sm">
                <p>&copy; {{ date('Y') }} Pitocom. Made with ❤️ for PC Enthusiast.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg" class="h-4 grayscale hover:grayscale-0 transition opacity-50 hover:opacity-100" alt="PayPal">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4 grayscale hover:grayscale-0 transition opacity-50 hover:opacity-100" alt="Visa">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6 grayscale hover:grayscale-0 transition opacity-50 hover:opacity-100" alt="Mastercard">
                </div>
            </div>
        </div>
    </footer>
    
    {{-- Floating WhatsApp Button --}}
    @if(!request()->is('admin/*'))
        @include('components.whatsapp-button')
    @endif
    
    @stack('scripts')
</body>
</html>