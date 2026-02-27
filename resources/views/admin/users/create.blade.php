@extends('layouts.app')

@section('title', 'Create New User - Pitocom Admin')

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; } 
        
        /* Animasi Section Reveal */
        .reveal-section {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .reveal-section.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-[1600px] mx-auto px-6 sm:px-10 py-10" 
     x-data="{ 
        initObserver() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) entry.target.classList.add('visible');
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.reveal-section').forEach(el => observer.observe(el));
        } 
     }" x-init="initObserver()">

    {{-- Breadcrumb & Header --}}
    <div class="reveal-section mb-12 text-left">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-sky-500 transition-colors mb-6 group">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to User Registry
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter">Add <span class="text-sky-500">New User.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1 text-left">Mendaftarkan anggota baru ke dalam ekosistem eksklusif Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full"></div>
    </div>

    {{-- Form Container --}}
    <div class="reveal-section bg-white p-12 lg:p-16 rounded-[3.5rem] shadow-sm border border-slate-100 relative overflow-hidden group mb-20" style="transition-delay: 150ms">
        {{-- Decorative Glow --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="relative text-left">
            @csrf
            
            <div class="space-y-16">
                {{-- 1. Identity Section --}}
                <section>
                    <div class="flex items-center mb-10 text-left">
                        <div class="bg-slate-900 text-white p-3 rounded-2xl mr-4 shadow-lg text-left">
                            <svg class="w-6 h-6 text-left" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Personal Identity</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-left">
                        <div class="text-left text-left">
                            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Input name..."
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-base">
                            @error('name') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div class="text-left text-left">
                            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="email@example.com"
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-base text-left text-left">
                            @error('email') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest text-left text-left">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                {{-- 2. Role Assignment Section --}}
                <section>
                    <div class="flex items-center mb-10 text-left">
                        <div class="bg-sky-100 text-sky-600 p-3 rounded-2xl mr-4 shadow-sm border border-sky-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Privilege Settings</h2>
                    </div>

                    <div class="text-left text-left">
                        <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left">User Privilege</label>
                        <div class="relative">
                            <select name="role" id="role" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all cursor-pointer shadow-inner appearance-none">
                                <option value="user" @selected(old('role') == 'user')>Standard Member</option>
                                <option value="admin" @selected(old('role') == 'admin')>System Administrator</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center px-8 pointer-events-none text-slate-400">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        @error('role') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest">{{ $message }}</p> @enderror
                    </div>
                </section>

                <hr class="border-slate-50 text-left">

                {{-- 3. Security Section --}}
                <section>
                    <div class="flex items-center mb-10 text-left">
                        <div class="bg-rose-100 text-rose-600 p-3 rounded-2xl mr-4 shadow-sm border border-rose-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em] text-left">Security Access</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="text-left text-left">
                            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left">Security Password</label>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-left text-left">
                            @error('password') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest text-left text-left">{{ $message }}</p> @enderror
                        </div>
                        <div class="text-left text-left">
                            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1 text-left text-left">Verify Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-left text-left">
                        </div>
                    </div>
                </section>

                {{-- Action Buttons --}}
                <div class="pt-10 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em] text-left">Pitocom Access Control System v2.6</p>
                    <button type="submit" class="w-full sm:w-auto px-12 py-5 bg-slate-900 text-white rounded-[1.8rem] font-black uppercase tracking-[0.15em] hover:bg-sky-500 transition-all shadow-2xl shadow-slate-200 active:scale-95 text-xs text-left">
                        Register New Member
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection