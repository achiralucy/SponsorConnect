<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:sponsor,panitia',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'status_verifikasi' => 'pending',
        ]);

        if ($user->role === 'sponsor') {
            DB::table('sponsor_profiles')->insert([
                'user_id'          => $user->id,
                'nama_perusahaan' => $user->name,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        } elseif ($user->role === 'panitia') {
            DB::table('panitia_profiles')->insert([
                'user_id'         => $user->id,
                'nama_organisasi' => $user->name,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun Anda menunggu verifikasi Admin.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}