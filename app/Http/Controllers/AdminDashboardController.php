<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total' => User::count(),
            'pending' => User::where('status_verifikasi', 'pending')->count(),
            'verified' => User::where('status_verifikasi', 'terverifikasi')->count(),
            'rejected' => User::where('status_verifikasi', 'ditolak')->count(),
            'sponsors' => User::where('role', 'sponsor')
                ->where('status_verifikasi', 'terverifikasi')
                ->count(),
            'panitia' => User::where('role', 'panitia')
                ->where('status_verifikasi', 'terverifikasi')
                ->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
