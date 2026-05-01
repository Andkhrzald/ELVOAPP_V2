@extends('layouts.customer')

@section('content')
<section class="min-h-screen flex items-center justify-center px-8 bg-[#0a0a0a] pt-20">
    <div class="w-full max-w-md" data-aos="fade-up">
        <div class="text-center mb-12">
            <h2 class="text-5xl font-black italic uppercase tracking-tighter text-white">Join Us.</h2>
            <p class="text-[10px] text-gray-500 uppercase tracking-[0.4em] mt-4 font-bold">Create your identity at ELVO.</p>
        </div>

        <form action="#" class="space-y-6">
            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Full Name</label>
                <input type="text" class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm uppercase">
            </div>

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Email</label>
                <input type="email" class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
            </div>

            <div class="group">
                <label class="text-[10px] font-black uppercase tracking-widest text-gray-500 group-focus-within:text-white transition-all">Password</label>
                <input type="password" class="w-full bg-transparent border-b-2 border-white/10 py-3 text-white focus:outline-none focus:border-white transition-all font-bold tracking-widest text-sm">
            </div>

            <div class="pt-6">
                <button class="w-full bg-white text-black py-5 rounded-full text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all italic">
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