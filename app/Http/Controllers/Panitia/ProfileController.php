<?php

namespace App\Http\Controllers\Panitia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends PanitiaController
{
    public function show(): View
    {
        $profile = $this->profile();

        return view('panitia.profile', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_organisasi'       => 'required|string|max:255',
            'nama_penanggung_jawab' => 'nullable|string|max:255',
            'kontak'                => 'nullable|string|max:255',
            'alamat'                => 'nullable|string|max:1000',
            'deskripsi'             => 'nullable|string|max:3000',
        ], [
            'required'   => ':attribute wajib diisi.',
            'max.string' => ':attribute maksimal :max karakter.',
        ]);

        $this->profile()->update($data);

        return back()->with('success', 'Profil panitia berhasil diperbarui.');
    }
}