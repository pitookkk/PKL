@extends('layouts.app')

@section('title', 'Register an Account')

@section('content')
<div class="flex items-center justify-center min-h-[60vh]">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-[2rem] shadow-md">
        <h2 class="text-3xl font-bold text-center text-sky-600">Create an Account</h2>
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name"
                       class="w-full px-3 py-2 mt-1 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                       class="w-full px-3 py-2 mt-1 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="w-full px-3 py-2 mt-1 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
            </div>
             <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="w-full px-3 py-2 mt-1 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-sky-500 focus:border-sky-500">
            </div>
            <div>
                <button type="submit" class="w-full px-4 py-2 font-semibold text-white bg-sky-500 rounded-md hover:bg-sky-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500">
                    Register
                </button>
            </div>
        </form>
         <p class="text-sm text-center text-slate-600">
            Already have an account? <a href="{{ route('login') }}" class="font-medium text-sky-600 hover:underline">Login here</a>
        </p>
    </div>
</div>
@endsection
