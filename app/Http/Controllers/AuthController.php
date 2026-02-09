<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tambahkan fungsi untuk menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // 2. Tambahkan fungsi untuk menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    // 3. Pastikan fungsi login sudah ada
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ]);
    }

    // 4. Pastikan fungsi register sudah ada
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat, silakan login!');
    }

    // 5. Fungsi Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}