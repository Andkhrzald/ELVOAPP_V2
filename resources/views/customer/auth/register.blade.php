@extends('layouts.customer')

@section('content')
<section class="relative min-h-screen flex items-center justify-center px-6 overflow-hidden bg-[#0a0a0a] pt-20">

    {{-- Floating Nebula Orbs --}}
    <div class="orb-1 absolute top-1/4 -left-20 w-96 h-96 rounded-full bg-elvo-primary/20 blur-[120px] pointer-events-none"></div>
    <div class="orb-2 absolute bottom-1/4 -right-20 w-80 h-80 rounded-full bg-elvo-secondary/15 blur-[100px] pointer-events-none"></div>
    <div class="orb-3 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] rounded-full bg-elvo-rose/10 blur-[150px] pointer-events-none"></div>

    {{-- Grid overlay --}}
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHZpZXdCb3g9IjAgMCA0MCA0MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMCAwaDQwdjQwSDB6IiBmaWxsPSJub25lIiBzdHJva2U9InJnYmEoMjU1LDI1NSwyNTUsMC4wMykiIHN0cm9rZS13aWR0aD0iMSIvPjwvc3ZnPg==')] opacity-30 pointer-events-none"></div>

    <div class="relative w-full max-w-md" data-aos="fade-up" data-aos-duration="800">

        {{-- Brand --}}
        <div class="text-center mb-10">
            <h1 class="text-6xl font-black italic tracking-tighter text-white">ELVO.</h1>
            <div class="mt-3 h-px w-12 mx-auto bg-elvo-primary/60"></div>
            <p class="text-[9px] text-gray-500 uppercase tracking-[0.5em] mt-4 font-bold">Create Identity</p>
        </div>

        {{-- Glass Card --}}
        <div class="relative bg-white/[0.03] backdrop-blur-xl border border-white/[0.06] rounded-3xl p-8 md:p-10 shadow-2xl">

            <form action="{{ route('register') }}" method="POST" class="space-y-4" id="register-form">
                @csrf

                {{-- Name --}}
                <div class="group">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-elvo-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="Full name"
                            class="w-full bg-white/[0.04] border border-white/[0.06] rounded-2xl py-3.5 pl-12 pr-4 text-sm text-white outline-none transition-all duration-300 placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06] focus:shadow-[0_0_0_3px_rgba(124,109,240,0.1)] uppercase">
                    </div>
                    @error('name')
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-3 h-3 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="text-[10px] text-red-500 font-bold uppercase tracking-wider">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="group">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-elvo-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="Email address"
                            class="w-full bg-white/[0.04] border border-white/[0.06] rounded-2xl py-3.5 pl-12 pr-4 text-sm text-white outline-none transition-all duration-300 placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06] focus:shadow-[0_0_0_3px_rgba(124,109,240,0.1)]">
                    </div>
                    @error('email')
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-3 h-3 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="text-[10px] text-red-500 font-bold uppercase tracking-wider">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="group">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 group-focus-within:text-elvo-primary transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" name="password" id="reg-password" required placeholder="Password"
                            class="w-full bg-white/[0.04] border border-white/[0.06] rounded-2xl py-3.5 pl-12 pr-12 text-sm text-white outline-none transition-all duration-300 placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06] focus:shadow-[0_0_0_3px_rgba(124,109,240,0.1)]">
                        <button type="button" id="toggle-reg-password" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-300 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" id="reg-eye-icon"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    </div>
                    <div class="mt-2 h-1 rounded-full bg-white/[0.06] overflow-hidden">
                        <div id="strength-bar" class="password-strength-bar h-full rounded-full password-strength-0"></div>
                    </div>
                    <div id="strength-label" class="text-[9px] text-gray-600 font-bold uppercase tracking-wider mt-1.5 h-4"></div>
                    @error('password')
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-3 h-3 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span class="text-[10px] text-red-500 font-bold uppercase tracking-wider">{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="group">
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 transition-colors" id="confirm-icon-wrapper">
                            <svg class="w-4 h-4" id="confirm-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" name="password_confirmation" id="reg-confirm" required placeholder="Confirm password"
                            class="w-full bg-white/[0.04] border border-white/[0.06] rounded-2xl py-3.5 pl-12 pr-4 text-sm text-white outline-none transition-all duration-300 placeholder:text-gray-600 focus:border-elvo-primary/50 focus:bg-white/[0.06] focus:shadow-[0_0_0_3px_rgba(124,109,240,0.1)]">
                    </div>
                    <div id="confirm-feedback" class="text-[9px] font-bold uppercase tracking-wider mt-1.5 h-4"></div>
                </div>

                {{-- Terms --}}
                <label class="flex items-start gap-3 cursor-pointer group">
                    <div class="relative mt-0.5 shrink-0">
                        <input type="checkbox" name="terms" id="terms" class="sr-only peer" required>
                        <div class="w-4 h-4 rounded-md border border-white/20 peer-checked:bg-elvo-primary peer-checked:border-elvo-primary transition-all group-hover:border-white/40"></div>
                        <svg class="absolute inset-0 w-4 h-4 text-white opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span class="text-[10px] text-gray-500 leading-relaxed group-hover:text-gray-300 transition-colors">
                        I agree to the
                        <a href="#" class="text-white underline underline-offset-2 hover:text-elvo-primary transition-colors font-bold">Terms</a>
                        &amp;
                        <a href="#" class="text-white underline underline-offset-2 hover:text-elvo-primary transition-colors font-bold">Privacy Policy</a>
                    </span>
                </label>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit" id="register-submit"
                        class="relative w-full bg-white text-black py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] hover:bg-gray-200 transition-all duration-300 disabled:opacity-60 disabled:cursor-not-allowed italic overflow-hidden">
                        <span id="reg-btn-text">Create Account</span>
                        <svg id="reg-btn-spinner" class="hidden animate-spin h-4 w-4 mx-auto text-black" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </button>
                </div>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-white/[0.06]"></div></div>
                    <div class="relative flex justify-center"><span class="px-4 bg-[#0d0d0d] text-[8px] text-gray-600 uppercase tracking-[0.3em] font-bold">or continue with</span></div>
                </div>

                {{-- Social --}}
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="flex items-center justify-center gap-2 py-3 rounded-2xl border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.06] hover:border-white/20 transition-all text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        Google
                    </button>
                    <button type="button" class="flex items-center justify-center gap-2 py-3 rounded-2xl border border-white/[0.06] bg-white/[0.02] hover:bg-white/[0.06] hover:border-white/20 transition-all text-gray-500 hover:text-white text-[10px] font-bold uppercase tracking-widest">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.302 3.438 9.8 8.205 11.387.6.113.82-.26.82-.578 0-.286-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.73.083-.73 1.205.085 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.418-1.305.762-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.3 24 12c0-6.627-5.373-12-12-12"/></svg>
                        Github
                    </button>
                </div>
            </form>
        </div>

        {{-- Login link --}}
        <p class="text-center mt-8 text-[10px] text-gray-600 uppercase tracking-widest font-bold">
            Already a member?
            <a href="{{ route('login') }}" class="text-white hover:text-elvo-primary underline underline-offset-4 transition-colors">Login</a>
        </p>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // ── Password Toggle ──
    document.getElementById('toggle-reg-password')?.addEventListener('click', function() {
        const input = document.getElementById('reg-password');
        const icon = document.getElementById('reg-eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l22 22"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
        }
    });

    // ── Password Strength Meter ──
    const passwordInput = document.getElementById('reg-password');
    const strengthBar = document.getElementById('strength-bar');
    const strengthLabel = document.getElementById('strength-label');

    passwordInput?.addEventListener('input', function() {
        const val = this.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
        if (/\d/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;

        strengthBar.className = 'password-strength-bar h-full rounded-full password-strength-' + score;
        const labels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
        const colors = ['', 'text-red-500', 'text-yellow-500', 'text-green-500', 'text-elvo-emerald'];
        strengthLabel.textContent = labels[score];
        strengthLabel.className = 'text-[9px] font-bold uppercase tracking-wider mt-1.5 h-4 ' + (score > 0 ? colors[score] : 'text-gray-600');
    });

    // ── Real-time Confirm Match ──
    const confirmInput = document.getElementById('reg-confirm');
    const confirmFeedback = document.getElementById('confirm-feedback');
    const confirmIcon = document.getElementById('confirm-icon');
    const confirmWrapper = document.getElementById('confirm-icon-wrapper');

    confirmInput?.addEventListener('input', function() {
        const match = this.value === passwordInput.value;
        if (this.value.length === 0) {
            confirmFeedback.textContent = '';
            confirmIcon.setAttribute('stroke', 'currentColor');
            confirmWrapper.className = 'absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 transition-colors';
        } else if (match) {
            confirmFeedback.textContent = '✓ Passwords match';
            confirmFeedback.className = 'text-[9px] font-bold uppercase tracking-wider mt-1.5 h-4 text-elvo-emerald';
            confirmIcon.setAttribute('stroke', '#4ade80');
            confirmWrapper.className = 'absolute left-4 top-1/2 -translate-y-1/2 text-elvo-emerald transition-colors';
        } else {
            confirmFeedback.textContent = '✗ Passwords do not match';
            confirmFeedback.className = 'text-[9px] font-bold uppercase tracking-wider mt-1.5 h-4 text-red-500';
            confirmIcon.setAttribute('stroke', '#ef4444');
            confirmWrapper.className = 'absolute left-4 top-1/2 -translate-y-1/2 text-red-500 transition-colors';
        }
    });

    // ── Loading State on Submit ──
    document.getElementById('register-form')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('register-submit');
        const text = document.getElementById('reg-btn-text');
        const spinner = document.getElementById('reg-btn-spinner');
        btn.disabled = true;
        text.classList.add('hidden');
        spinner.classList.remove('hidden');
    });
</script>
@endpush
