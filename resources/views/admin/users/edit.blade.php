@extends('layouts.app')

@section('title', 'Edit User - Pitocom Admin')

@section('content')
    {{-- Load Font Premium --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style> body { font-family: 'Plus Jakarta Sans', sans-serif; } </style>

    {{-- Navbar Admin --}}
    @include('admin.partials.nav')
    
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    {{-- Breadcrumb & Header --}}
    <div class="mb-10 text-left animate-fade-in-up">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-xs font-black text-slate-400 uppercase tracking-widest hover:text-sky-500 transition-colors mb-4 group">
            <svg class="w-4 h-4 mr-2 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            Back to User List
        </a>
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Edit <span class="text-sky-500">User</span></h1>
        <p class="text-slate-500 mt-2 font-medium">Memperbarui data profil, peran, dan status membership pelanggan: <span class="font-bold text-slate-700 italic">{{ $user->name }}</span></p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
        {{-- Decorative Glow --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>

        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="relative text-left">
            @csrf
            @method('PUT')
            
            <div class="space-y-10">
                {{-- Bagian Identitas Utama --}}
                <section>
                    <div class="flex items-center mb-6">
                        <div class="bg-sky-100 text-sky-600 p-2 rounded-xl mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Account Identity</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                            @error('name') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner text-left">
                            @error('email') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
                
                <hr class="border-slate-50">

                {{-- Bagian Role & Membership --}}
                <section>
                    <div class="flex items-center mb-6">
                        <div class="bg-amber-100 text-amber-600 p-2 rounded-xl mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Privileges & Status</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">User Role</label>
                            <select name="role" id="role" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all cursor-pointer shadow-inner">
                                <option value="user" @selected(old('role', $user->role) == 'user')>User Access</option>
                                <option value="admin" @selected(old('role', $user->role) == 'admin')>Full Admin Access</option>
                            </select>
                            @error('role') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="membership_level" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Membership Level</label>
                            <select name="membership_level" id="membership_level" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all cursor-pointer shadow-inner">
                                <option value="Silver" @selected(old('membership_level', $user->membership_level) == 'Silver')>Silver Tier</option>
                                <option value="Gold" @selected(old('membership_level', $user->membership_level) == 'Gold')>Gold Tier</option>
                                <option value="Platinum" @selected(old('membership_level', $user->membership_level) == 'Platinum')>Platinum Tier</option>
                            </select>
                            @error('membership_level') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <section>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="points" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Loyalty Points</label>
                            <input type="number" name="points" id="points" value="{{ old('points', $user->points) }}" required 
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-sky-600 transition-all shadow-inner">
                            @error('points') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="total_spent" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Total Lifetime Spent (IDR)</label>
                            <input type="number" step="0.01" name="total_spent" id="total_spent" value="{{ old('total_spent', $user->total_spent) }}" required 
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-emerald-500 transition-all shadow-inner">
                            @error('total_spent') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <hr class="border-slate-50">

                {{-- Bagian Keamanan (Password) --}}
                <section>
                    <div class="flex items-center mb-6">
                        <div class="bg-rose-100 text-rose-600 p-2 rounded-xl mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest text-left">Security Update <span class="text-[10px] text-slate-400 normal-case italic">(Optional)</span></h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 text-left">New Password</label>
                            <input type="password" name="password" id="password" placeholder="••••••••"
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                            @error('password') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic text-left">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1 text-left">Confirm New Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                   class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                        </div>
                    </div>
                </section>

                <div class="pt-10 border-t border-slate-50 flex flex-col md:flex-row items-center justify-between gap-4">
                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic text-left">Dibuat pada {{ $user->created_at->format('d M Y') }}</p>
                    <button type="submit" class="w-full md:w-auto px-12 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl shadow-slate-200 active:scale-95 text-xs">
                        Update User Account
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection