<?php

namespace App\Http\Controllers;

use App\Models\SponsorProfile;
use App\Models\SponsorshipApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SponsorApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => 'nullable|in:pending,diterima,ditolak',
            'sponsorship' => 'nullable|integer',
        ]);

        $applications = $this->ownedApplications()
            ->with(['sponsorship', 'panitia.user'])
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['sponsorship'] ?? null, fn ($query, $id) => $query->where('sponsorship_id', $id))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('sponsor.applications.index', compact('applications', 'filters'));
    }

    public function show(string $id): View
    {
        $application = $this->ownedApplications()
            ->with(['sponsorship', 'panitia.user'])
            ->findOrFail($id);

        return view('sponsor.applications.show', compact('application'));
    }

    public function review(Request $request, string $id): RedirectResponse
    {
        $application = $this->ownedApplications()->findOrFail($id);

        if ($application->status !== SponsorshipApplication::STATUS_PENDING) {
            return redirect()->route('sponsor.applications.show', $application->id)
                ->with('error', 'Pengajuan ini sudah diproses dan tidak dapat diubah lagi.');
        }

        $data = $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'catatan_sponsor' => 'nullable|string|required_if:status,ditolak|max:2000',
        ]);

        $application->update([
            'status' => $data['status'],
            'catatan_sponsor' => $data['catatan_sponsor'] ?? null,
        ]);

        $message = $data['status'] === SponsorshipApplication::STATUS_ACCEPTED
            ? 'Pengajuan berhasil diterima.'
            : 'Pengajuan berhasil ditolak.';

        return redirect()->route('sponsor.applications.show', $application->id)
            ->with('success', $message);
    }

    private function ownedApplications()
    {
        $profile = auth()->user()->sponsorProfile;
        abort_unless($profile instanceof SponsorProfile, 403, 'Profil sponsor tidak ditemukan.');

        return SponsorshipApplication::whereHas(
            'sponsorship',
            fn ($query) => $query->where('sponsor_id', $profile->id)
        );
    }
}