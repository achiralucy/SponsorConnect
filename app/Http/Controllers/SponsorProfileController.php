<?php

namespace App\Http\Controllers;

use App\Models\SponsorProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorProfileController extends Controller
{
    public function show()
    {
        $profile = $this->profile();

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

        $profile = $this->profile();
        $oldLogo = $profile->logo;

        if ($request->hasFile('logo')) {
            $profile->logo = $request->file('logo')->store("logos/{$profile->user_id}", 'public');
        }

        $profile->fill($request->only([
            'nama_perusahaan',
            'bidang_usaha',
            'deskripsi',
            'kontak',
            'alamat',
        ]))->save();

        if ($oldLogo && $oldLogo !== $profile->logo
            && ! SponsorProfile::where('logo', $oldLogo)->where('id', '<>', $profile->id)->exists()) {
            Storage::disk('public')->delete($oldLogo);
        }

        return back()->with('success', 'Profil sponsor berhasil diperbarui.');
    }

    private function profile(): SponsorProfile
    {
        $profile = auth()->user()->sponsorProfile;
        abort_unless($profile, 403, 'Profil sponsor tidak ditemukan.');

        return $profile;
    }
}