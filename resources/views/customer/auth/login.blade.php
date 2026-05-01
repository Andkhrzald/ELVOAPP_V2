@extends('layouts.customer')

@section('content')
@if(session('success'))
<div class="mb-8 p-4 bg-white/5 border border-white/20 rounded-2xl" data-aos="fade-down">
    <p class="text-[10px] text-white font-bold uppercase tracking-[0.3em] text-center">
        {{ session('success') }}
    </p>
</div>
@endif

@if($errors->any())
<div class="mb-8 p-4 bg-red-500/10 border border-red-500/20 rounded-2xl">
    <p class="text-[10px] text-red-500 font-bold uppercase tracking-[0.3em] text-center">
        {{ $errors->first() }}
    </p>
</div>
@endif
<section class="min-h-screen flex items-center justify-center px-8 bg-[#0a0a0a]">
    <div class="w-full max-w-md" data-aos="fade-up">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-black italic uppercase tracking-tighter text-white">Login.</h2>
            <p class="text-[10px] text-gray-500 uppercase tracking-[0.4em] mt-4 font-bold">Access your ELVO. account</p>
        </div>

        <form action="#" class="space-y-8">
            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Email Address</label>
                <input type="email" class="w-full bg-transparent border-b-2 border-white/10 py-4 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
            </div>

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Password</label>
                <input type="password" class="w-full bg-transparent border-b-2 border-white/10 py-4 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
            </div>

            <div class="pt-6">
                <button class="w-full bg-white text-black py-5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all italic">
                    Sign In
                </button>
            </div>
        </form>

        <p class="text-center mt-12 text-[10px] text-gray-600 uppercase tracking-widest">
            Don't have an account? <a href="{{ route('register') }}" class="text-white font-black underline underline-offset-4">Register here</a>
        </p>
    </div>
</section>
@endsection