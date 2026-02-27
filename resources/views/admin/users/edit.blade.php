@extends('layouts.app')

@section('title', 'Edit User - Pitocom Admin')

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
            Back to Registry
        </a>
        <h1 class="text-5xl font-black text-slate-900 tracking-tighter">Edit <span class="text-sky-500">Member.</span></h1>
        <p class="text-slate-500 mt-3 font-medium text-lg italic pl-1">Modifikasi data profil, hak akses, dan loyalitas: <span class="font-black text-slate-900 not-italic uppercase tracking-tight ml-1">{{ $user->name }}</span></p>
        <div class="h-1.5 w-24 bg-sky-500 mt-6 rounded-full"></div>
    </div>

    {{-- Form Container --}}
    <div class="reveal-section bg-white p-12 lg:p-16 rounded-[3.5rem] shadow-sm border border-slate-100 relative overflow-hidden group mb-20" style="transition-delay: 150ms">
        {{-- Decorative Glow --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="relative text-left">
            @csrf
            @method('PUT')
            
            <div class="space-y-16">
                {{-- 1. Account Identity Section --}}
                <section>
                    <div class="flex items-center mb-10">
                        <div class="bg-slate-900 text-white p-3 rounded-2xl mr-4 shadow-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">Core Identity</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Full Member Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-base">
                            @error('name') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-inner text-base">
                            @error('email') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
                
                {{-- 2. Privileges & Financials Section --}}
                <section>
                    <div class="flex items-center mb-10">
                        <div class="bg-amber-100 text-amber-600 p-3 rounded-2xl mr-4 shadow-sm border border-amber-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">Privileges & Financials</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div>
                            <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Access Role</label>
                            <select name="role" id="role" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all cursor-pointer shadow-inner appearance-none">
                                <option value="user" @selected(old('role', $user->role) == 'user')>User Account</option>
                                <option value="admin" @selected(old('role', $user->role) == 'admin')>Root Admin</option>
                            </select>
                        </div>

                        <div>
                            <label for="membership_level" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Membership Tier</label>
                            <select name="membership_level" id="membership_level" class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all cursor-pointer shadow-inner appearance-none">
                                <option value="Silver" @selected(old('membership_level', $user->membership_level) == 'Silver')>Silver Tier</option>
                                <option value="Gold" @selected(old('membership_level', $user->membership_level) == 'Gold')>Gold Tier</option>
                                <option value="Platinum" @selected(old('membership_level', $user->membership_level) == 'Platinum')>Platinum Tier</option>
                            </select>
                        </div>

                        <div>
                            <label for="points" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Loyalty Points</label>
                            <input type="number" name="points" id="points" value="{{ old('points', $user->points) }}" required 
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-sky-600 transition-all shadow-inner text-base">
                        </div>

                        <div>
                            <label for="total_spent" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Total Spent (IDR)</label>
                            <input type="number" step="0.01" name="total_spent" id="total_spent" value="{{ old('total_spent', $user->total_spent) }}" required 
                                   class="w-full px-8 py-5 bg-slate-50 border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-emerald-600 transition-all shadow-inner text-base">
                        </div>
                    </div>
                </section>

                {{-- 3. Security Section --}}
                <section class="bg-slate-50/50 p-10 rounded-[2.5rem] border border-slate-100">
                    <div class="flex items-center mb-10">
                        <div class="bg-rose-100 text-rose-600 p-3 rounded-2xl mr-4 shadow-sm border border-rose-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h2 class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">Security Update</h2>
                            <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mt-1">Biarkan kosong jika tidak ingin mengubah password</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div>
                            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">New Access Key</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                   class="w-full px-8 py-5 bg-white border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-sm">
                            @error('password') <p class="text-rose-500 text-[10px] font-black mt-3 ml-2 uppercase tracking-widest">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 ml-1">Verify Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                   class="w-full px-8 py-5 bg-white border-none rounded-3xl focus:ring-2 focus:ring-sky-500 font-black text-slate-700 transition-all shadow-sm">
                        </div>
                    </div>
                </section>

                {{-- Action Buttons --}}
                <div class="pt-6 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="flex flex-col text-left">
                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Registration Log</span>
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-tighter">Registered since {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-6 w-full md:w-auto">
                        <a href="{{ route('admin.users.index') }}" class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-900 transition-colors">Discard</a>
                        <button type="submit" class="flex-1 md:flex-none px-12 py-5 bg-slate-900 text-white rounded-[1.8rem] font-black uppercase tracking-[0.15em] hover:bg-sky-500 transition-all shadow-2xl shadow-slate-200 active:scale-95 text-xs">
                            Sync User Account
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection