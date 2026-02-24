@extends('layouts.app')

@section('title', 'Create New User - Pitocom Admin')

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
        <h1 class="text-4xl font-black text-slate-900 tracking-tight">Create New <span class="text-sky-500">User</span></h1>
        <p class="text-slate-500 mt-2 font-medium">Menambahkan anggota baru ke dalam ekosistem Pitocom.</p>
        <div class="h-1.5 w-24 bg-sky-500 mt-4 rounded-full"></div>
    </div>

    <div class="bg-white p-10 rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group">
        {{-- Decorative Glow --}}
        <div class="absolute -top-24 -right-24 w-64 h-64 bg-sky-500/5 rounded-full group-hover:scale-150 transition-transform duration-1000"></div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="relative text-left">
            @csrf
            <div class="space-y-8">
                
                {{-- Bagian Identitas --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Full Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Input name..."
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                        @error('name') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="email@example.com"
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                        @error('email') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Bagian Role --}}
                <div>
                    <label for="role" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">User Privilege</label>
                    <div class="relative">
                        <select name="role" id="role" class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all cursor-pointer shadow-inner appearance-none">
                            <option value="user" @selected(old('role') == 'user')>Standard User</option>
                            <option value="admin" @selected(old('role') == 'admin')>System Administrator</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    @error('role') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                </div>

                <hr class="border-slate-50">

                {{-- Bagian Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Security Password</label>
                        <input type="password" name="password" id="password" required placeholder="••••••••"
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                        @error('password') <p class="text-rose-500 text-[10px] font-bold mt-2 ml-1 italic">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 ml-1">Verify Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••"
                               class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-bold text-slate-700 transition-all shadow-inner">
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-6 flex justify-end">
                    <button type="submit" class="w-full md:w-auto px-12 py-5 bg-slate-900 text-white rounded-2xl font-black uppercase tracking-widest hover:bg-sky-500 transition-all shadow-xl shadow-slate-200 active:scale-95 text-xs">
                        Register New Account
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection