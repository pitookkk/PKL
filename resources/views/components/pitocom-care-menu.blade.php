<div x-data="{ open: false }" class="fixed bottom-28 right-8 z-[60]">
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 scale-90"
         class="absolute bottom-full right-0 mb-6 w-72 bg-white rounded-[2rem] shadow-2xl border border-slate-100 p-3 space-y-1 overflow-hidden"
         @click.away="open = false"
         style="display: none;">
        
        {{-- Label Menu --}}
        <div class="px-4 py-2 mb-2 border-b border-slate-50">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Quick Access</p>
        </div>

        {{-- Link ke Dashboard Pitocom Care --}}
        <a href="{{ route('pitocom-care.dashboard') }}" class="flex items-center w-full px-5 py-4 text-sm font-black text-slate-700 rounded-2xl hover:bg-slate-50 hover:text-sky-500 transition-all group/item">
            <div class="bg-sky-100 text-sky-500 p-2.5 rounded-xl mr-4 group-hover/item:bg-sky-500 group-hover/item:text-white transition-all shadow-sm shadow-sky-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            My Pitocom Care
        </a>

        {{-- Link ke Wishlist --}}
        <a href="{{ route('wishlist.index') }}" class="flex items-center w-full px-5 py-4 text-sm font-black text-slate-700 rounded-2xl hover:bg-slate-50 hover:text-rose-500 transition-all group/item">
            <div class="bg-rose-100 text-rose-500 p-2.5 rounded-xl mr-4 group-hover/item:bg-rose-500 group-hover/item:text-white transition-all shadow-sm shadow-rose-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            My Wishlist
        </a>
    </div>

    <button @click="open = !open"
            class="relative flex items-center justify-center w-16 h-16 bg-slate-900 text-white rounded-2xl shadow-[0_15px_35px_rgba(15,23,42,0.3)] hover:bg-sky-500 hover:shadow-sky-200 transition-all duration-300 transform active:scale-90 group overflow-hidden border-2 border-slate-800">
        
        {{-- Decorative Glow --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-white/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity "></div>
        
        {{-- Menu Icon --}}
        <svg x-show="!open" 
             x-transition:enter="transition duration-300 transform" 
             x-transition:enter-start="rotate-90 opacity-0"
             class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 12h16m-7-4v8m-4-4h8"></path>
        </svg>

        {{-- Close Icon --}}
        <svg x-show="open" 
             x-transition:enter="transition duration-300 transform" 
             x-transition:enter-start="-rotate-90 opacity-0"
             class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>