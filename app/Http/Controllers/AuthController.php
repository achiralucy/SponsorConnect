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
            'name'             => 'required|string|max:255',
            'email'            => 'required|string|email|max:255|unique:users',
            'password'         => 'required|string|min:8|confirmed',
            'role'             => 'required|in:sponsor,panitia',
            'alasan_mendaftar' => 'required|string|max:1000',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'alasan_mendaftar'  => $request->alasan_mendaftar,
            'status_verifikasi' => 'pending',
        ]);

        if ($user->role === 'sponsor') {
            DB::table('sponsor_profiles')->insert([
                'user_id'         => $user->id,
                'nama_perusahaan' => $user->name,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        } elseif ($user->role === 'panitia') {
            DB::table('panitia_profiles')->insert([
                'user_id'         => $user->id,
                'nama_organisasi' => $user->name,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.status');
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

            $user = Auth::user();

            if (
                $user->status_verifikasi === 'pending' ||
                $user->status_verifikasi === 'ditolak'
            ) {
                return redirect()->route('verification.status');
            }

            if ($user->status_verifikasi === 'terverifikasi') {
                return redirect()->route('dashboard');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Status akun tidak valid. Silakan hubungi Admin.',
            ]);
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

    public function verificationStatus()
    {
        $user = Auth::user();

        return view('auth.verification-status', compact('user'));
    }
}
