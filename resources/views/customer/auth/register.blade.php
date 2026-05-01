@extends('layouts.customer')

@section('content')
<section class="min-h-screen flex items-center justify-center px-8 bg-[#0a0a0a] pt-20">
    <div class="w-full max-w-md" data-aos="fade-up">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-black italic uppercase tracking-tighter text-white">Join Us.</h2>
            <p class="text-[10px] text-gray-500 uppercase tracking-[0.4em] mt-4 font-bold">Create your identity at ELVO.</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-6">
            @csrf {{-- WAJIB: Untuk keamanan Laravel --}}

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Full Name</label>
                {{-- UPDATE: Tambahkan name="name" dan value old --}}
                <input type="text" name="name" value="{{ old('name') }}" required autofocus 
                    class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm uppercase">
                @error('name')
                    <span class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</span>
                @enderror
            </div>

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Email</label>
                {{-- UPDATE: Tambahkan name="email" --}}
                <input type="email" name="email" value="{{ old('email') }}" required 
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
                @error('password')
                    <span class="text-[10px] text-red-500 font-bold uppercase mt-1">{{ $message }}</span>
                @enderror
            </div>

            {{-- UPDATE: Laravel butuh Password Confirmation untuk Register --}}
            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Confirm Password</label>
                <input type="password" name="password_confirmation" required 
                    class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full bg-white text-black py-5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all italic">
                    Create Account
                </button>
            </div>
        </form>

        <p class="text-center mt-10 text-[10px] text-gray-600 uppercase tracking-widest pb-10">
            Already a member? <a href="{{ route('login') }}" class="text-white font-black underline underline-offset-4">Login</a>
        </p>
    </div>
</section>
@endsection