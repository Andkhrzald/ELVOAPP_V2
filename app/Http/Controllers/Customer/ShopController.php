<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ShopController extends Controller
{
    public function index()
    {
        return view('customer.shop');
    }

    public function checkout()
    {
        return view('customer.checkout');
    }

    public function success()
    {
        return view('customer.success');
    }

    // --- FUNGSI AUTH ---

    public function login()
    {
        return view('customer.auth.login');
    }

    public function register()
    {
        return view('customer.auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Registration successful!');
    }

    public function postLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // LOGIKA PEMISAH: Agar Admin masuk ke tempatnya sendiri
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard'); // Arahkan ke rute dashboard admin kamu
            }

            return redirect()->intended('/'); // Arahkan ke home customer Rehan
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function history()
    {
        // Cari order berdasarkan user_id yang sedang login
        $orders = \App\Models\Order::with('products')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Ubah 'pages.history' jadi 'customer.history'
        return view('customer.history', compact('orders'));
    }
} // <--- KURUNG KURAWAL PENUTUP HARUS DI SINI