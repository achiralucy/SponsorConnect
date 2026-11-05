<?php

namespace App\Http\Controllers;

use App\Models\Sponsorship;
use Illuminate\Http\Request;

/**
 * CRUD sponsorship milik Sponsor yang sedang login.
 * Route-nya dilindungi middleware role:sponsor (lihat routes/web.php).
 */
class SponsorshipController extends Controller
{
    /** Daftar sponsorship milik sponsor yang login. */
    public function index()
    {
        // Tutup otomatis sponsorship yang batas pengajuannya sudah lewat.
        Sponsorship::closeExpired();

        $sponsorships = $this->ownedSponsorships()
            ->withCount('applications')
            ->latest()
            ->paginate(10);

        return view('sponsor.sponsorships.index', compact('sponsorships'));
    }

    public function create()
    {
        return view('sponsor.sponsorships.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'benefit'         => 'required|string',
            'persyaratan'     => 'required|string',
            'batas_pengajuan' => 'required|date|after_or_equal:today',
        ]);

        $this->sponsorProfile()->sponsorships()->create($data + [
            'status' => Sponsorship::STATUS_OPEN,
        ]);

        return redirect()->route('sponsor.sponsorships.index')
            ->with('success', 'Sponsorship berhasil dibuat dan sudah dibuka untuk panitia.');
    }

    public function show(string $id)
    {
        $sponsorship = $this->ownedSponsorships()
            ->withCount('applications')
            ->findOrFail($id);

        return view('sponsor.sponsorships.show', compact('sponsorship'));
    }

    public function edit(string $id)
    {
        $sponsorship = $this->ownedSponsorships()->findOrFail($id);

        return view('sponsor.sponsorships.edit', compact('sponsorship'));
    }

    public function update(Request $request, string $id)
    {
        $sponsorship = $this->ownedSponsorships()->findOrFail($id);

        $data = $request->validate([
            'judul'           => 'required|string|max:255',
            'deskripsi'       => 'required|string',
            'benefit'         => 'required|string',
            'persyaratan'     => 'required|string',
            'batas_pengajuan' => 'required|date',
            'status'          => 'required|in:open,closed',
        ]);

        // Sponsorship hanya boleh berstatus "open" kalau batas pengajuannya belum lewat.
        // (Kalau tidak, langsung ditutup lagi oleh penutupan otomatis.)
        if ($data['status'] === Sponsorship::STATUS_OPEN
            && $data['batas_pengajuan'] < today()->toDateString()) {
            return back()->withInput()->withErrors([
                'batas_pengajuan' => 'Batas pengajuan sudah lewat. Pilih tanggal hari ini atau sesudahnya untuk membuka sponsorship, atau ubah status jadi Ditutup.',
            ]);
        }

        $sponsorship->update($data);

        return redirect()->route('sponsor.sponsorships.show', $sponsorship)
            ->with('success', 'Sponsorship berhasil diperbarui.');
    }

    /** Tutup sponsorship secara manual (tombol "Tutup"). */
    public function close(string $id)
    {
        $sponsorship = $this->ownedSponsorships()->findOrFail($id);
        $sponsorship->update(['status' => Sponsorship::STATUS_CLOSED]);

        return back()->with('success', 'Sponsorship ditutup. Panitia tidak bisa mengajukan lagi.');
    }

    public function destroy(string $id)
    {
        $sponsorship = $this->ownedSponsorships()->findOrFail($id);

        // Jangan hapus kalau sudah ada pengajuan, supaya riwayat panitia tidak hilang.
        if ($sponsorship->applications()->exists()) {
            return back()->with('error',
                'Sponsorship ini sudah punya pengajuan, jadi tidak bisa dihapus. Gunakan tombol "Tutup" saja.');
        }

        $sponsorship->delete();

        return redirect()->route('sponsor.sponsorships.index')
            ->with('success', 'Sponsorship berhasil dihapus.');
    }

    /** Profil sponsor milik user yang login. */
    private function sponsorProfile()
    {
        $profile = auth()->user()->sponsorProfile;
        abort_unless($profile, 403, 'Profil sponsor tidak ditemukan.');

        return $profile;
    }

    /**
     * Query sponsorship yang HANYA milik sponsor ini.
     * Karena semua aksi lewat sini, sponsor lain yang menebak id di URL dapat 404.
     */
    private function ownedSponsorships()
    {
        return $this->sponsorProfile()->sponsorships();
    }
}