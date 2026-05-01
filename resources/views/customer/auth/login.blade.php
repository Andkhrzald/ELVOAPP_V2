@extends('layouts.customer')

@section('content')
<section class="min-h-screen flex items-center justify-center px-8 bg-[#0a0a0a]">
    <div class="w-full max-w-md" data-aos="fade-up">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-black italic uppercase tracking-tighter text-white">Welcome back.</h2>
            <p class="text-[10px] text-gray-500 uppercase tracking-[0.4em] mt-4 font-bold">Sign in to your account</p>
        </div>

        {{-- UPDATE: Action diarahkan ke route login, method POST --}}
        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf {{-- WAJIB ADA agar tidak error 419 --}}

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Email Address</label>
                {{-- UPDATE: Tambahkan name="email" agar data terbaca --}}
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
                @error('email')
                    <span class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Password</label>
                {{-- UPDATE: Tambahkan name="password" --}}
                <input type="password" name="password" required
                    class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
            </div>

            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                <label class="flex items-center space-x-2 cursor-pointer group">
                    <input type="checkbox" name="remember" class="hidden">
                    <div class="w-3 h-3 border border-white/20 group-hover:border-white transition-all"></div>
                    <span class="text-gray-500 group-hover:text-white transition-all">Remember me</span>
                </label>
                <a href="#" class="text-gray-500 hover:text-white transition-all">Forgot Password?</a>
            </div>

            <div class="pt-6">
                {{-- Pastikan ini button type="submit" --}}
                <button type="submit" class="w-full bg-white text-black py-5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all italic">
                    Sign In
                </button>
            </div>
        </form>

        <p class="text-center mt-10 text-[10px] text-gray-600 uppercase tracking-widest">
            Don't have an account? <a href="{{ route('register') }}" class="text-white font-black underline underline-offset-4">Join Us</a>
        </p>
    </div>
</section>
@endsection