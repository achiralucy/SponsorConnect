<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SponsorProfileController extends Controller
{
    public function show()
    {
        $profile = DB::table('sponsor_profiles')
            ->where('user_id', auth()->id())
            ->first();

        return view('sponsor.profile', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha'   => 'nullable|string|max:255',
            'deskripsi'      => 'nullable|string',
            'kontak'         => 'nullable|string|max:255',
            'alamat'         => 'nullable|string',
            'logo'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $profile = DB::table('sponsor_profiles')
            ->where('user_id', auth()->id())
            ->first();

        $logoPath = $profile ? $profile->logo : null;

        if ($request->hasFile('logo')) {
            if ($logoPath && Storage::disk('public')->exists($logoPath)) {
                Storage::disk('public')->delete($logoPath);
            }
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        DB::table('sponsor_profiles')
            ->where('user_id', auth()->id())
            ->update([
                'nama_perusahaan' => $request->nama_perusahaan,
                'bidang_usaha'   => $request->bidang_usaha,
                'deskripsi'      => $request->deskripsi,
                'kontak'         => $request->kontak,
                'alamat'         => $request->alamat,
                'logo'           => $logoPath,
                'updated_at'     => now(),
            ]);

        return back()->with('success', 'Profil sponsor berhasil diperbarui.');
    }
}