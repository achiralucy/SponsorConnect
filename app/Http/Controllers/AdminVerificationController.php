<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminVerificationController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.verifications.index', compact('users'));
    }

    public function approve(User $user): RedirectResponse
    {
        if ($user->status_verifikasi !== 'pending') {
            return redirect()->route('admin.verifications.index')
                ->with('error', 'Akun ini sudah diproses sebelumnya.');
        }

        $user->update([
            'status_verifikasi' => 'terverifikasi',
            'catatan_verifikasi' => null,
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Akun {$user->name} berhasil disetujui.");
    }

    public function reject(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'catatan_verifikasi' => 'required|string|max:2000',
        ]);

        if ($user->status_verifikasi !== 'pending') {
            return redirect()->route('admin.verifications.index')
                ->with('error', 'Akun ini sudah diproses sebelumnya.');
        }

        $user->update([
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => $data['catatan_verifikasi'],
        ]);

        return redirect()->route('admin.verifications.index')
            ->with('success', "Pendaftaran {$user->name} ditolak.");
    }
}
