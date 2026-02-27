@extends('layouts.app')

@section('title', 'Edit Profile - Pitocom')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-10">
    <div class="mb-10 animate-fade-in-up">
        <h1 class="text-4xl font-black text-slate-900 tracking-tight text-left">Edit Profile</h1>
        <p class="text-slate-500 font-medium text-left">Perbarui informasi profil Anda di sini.</p>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-slate-100 text-left">
            <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PATCH')

                {{-- Name --}}
                <div class="space-y-2">
                    <label for="name" class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all @error('name') ring-2 ring-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="space-y-2">
                    <label for="email" class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all @error('email') ring-2 ring-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone Number --}}
                <div class="space-y-2">
                    <label for="phone_number" class="block text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}"
                           class="w-full px-6 py-4 bg-slate-50 border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all @error('phone_number') ring-2 ring-red-500 @enderror">
                    @error('phone_number')
                        <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="border-slate-100 my-8">

                <div class="bg-slate-50 p-6 rounded-[2rem] space-y-4">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Change Password (optional)</p>
                    
                    {{-- New Password --}}
                    <div class="space-y-2">
                        <label for="password" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em]">New Password</label>
                        <input type="password" name="password" id="password"
                               class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all @error('password') ring-2 ring-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.1em]">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                               class="w-full px-6 py-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-sky-500 font-medium transition-all">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <button type="submit" class="flex-1 py-4 bg-slate-900 text-white rounded-2xl font-black text-sm hover:bg-sky-500 transition-all active:scale-95 shadow-xl shadow-slate-200">
                        SAVE CHANGES
                    </button>
                    <a href="{{ route('dashboard') }}" class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all active:scale-95 text-center">
                        CANCEL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush
