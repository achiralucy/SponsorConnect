<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:100',
            'role' => 'nullable|in:sponsor,panitia,admin',
            'status' => 'nullable|in:pending,terverifikasi,ditolak',
        ]);

        $users = User::query()
            ->where('status_verifikasi', '!=', 'ditolak')
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, fn ($query, $role) => $query->where('role', $role))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status_verifikasi', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'filters'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is(auth()->user())) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        $hasSponsorshipHistory = $user->sponsorProfile?->sponsorships()->exists() ?? false;
        $hasApplicationHistory = $user->panitiaProfile?->applications()->exists() ?? false;

        if ($hasSponsorshipHistory || $hasApplicationHistory) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Akun ini memiliki riwayat sponsorship atau pengajuan sehingga tidak dapat dihapus.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun {$name} berhasil dihapus.");
    }
}
