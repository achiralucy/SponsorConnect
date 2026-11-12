<?php

namespace App\Http\Controllers\Panitia;

use App\Models\Sponsorship;
use App\Models\SponsorProfile;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SponsorshipBrowseController extends PanitiaController
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q'       => 'nullable|string|max:100',
            'sponsor' => 'nullable|integer',
            'urutan'  => 'nullable|in:terbaru,deadline',
        ]);

        $panitiaId = $this->profile()->id;

        $sponsorships = Sponsorship::open()
            ->with('sponsor')
            ->withExists(['applications as sudah_diajukan' => fn ($q) => $q->where('panitia_id', $panitiaId)])
            ->when($filters['q'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->whereLike('judul', "%{$search}%")
                        ->orWhereLike('deskripsi', "%{$search}%")
                        ->orWhereLike('benefit', "%{$search}%")
                        ->orWhereHas('sponsor', fn ($q) => $q->whereLike('nama_perusahaan', "%{$search}%"));
                });
            })
            ->when($filters['sponsor'] ?? null, fn ($query, $id) => $query->where('sponsor_id', $id))
            ->when(
                ($filters['urutan'] ?? 'terbaru') === 'deadline',
                fn ($query) => $query->orderBy('batas_pengajuan'),
                fn ($query) => $query->latest()
            )
            ->paginate(9)
            ->withQueryString();

        $sponsors = SponsorProfile::whereHas('sponsorships', fn ($q) => $q->open())
            ->orderBy('nama_perusahaan')
            ->get(['id', 'nama_perusahaan']);

        return view('panitia.sponsorships.index', compact('sponsorships', 'sponsors', 'filters'));
    }

    public function show(string $id): View
    {
        $sponsorship = Sponsorship::with('sponsor')->findOrFail($id);

        $myApplication = $this->profile()
            ->applications()
            ->where('sponsorship_id', $sponsorship->id)
            ->first();

        abort_unless($sponsorship->isOpen() || $myApplication, 404);

        return view('panitia.sponsorships.show', compact('sponsorship', 'myApplication'));
    }
}