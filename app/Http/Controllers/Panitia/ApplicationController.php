<?php

namespace App\Http\Controllers\Panitia;

use App\Models\Sponsorship;
use App\Models\SponsorshipApplication;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ApplicationController extends PanitiaController
{
        public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => 'nullable|in:pending,diterima,ditolak',
        ]);

        $applications = $this->ownedApplications()
            ->with('sponsorship.sponsor')
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('panitia.applications.index', compact('applications', 'filters'));
    }

    public function edit(string $id): View|RedirectResponse
    {
        $application = $this->ownedApplications()->with('sponsorship.sponsor')->findOrFail($id);

        if ($blocked = $this->notEditable($application)) {
            return $blocked;
        }

        return view('panitia.applications.edit', compact('application'));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $application = $this->ownedApplications()->findOrFail($id);

        if ($blocked = $this->notEditable($application)) {
            return $blocked;
        }

        $data = $request->validate($this->rules(proposalRequired: false), $this->messages());

        $values      = Arr::except($data, ['proposal']);
        $oldProposal = null;

        if ($request->hasFile('proposal')) {
            $oldProposal        = $application->proposal;
            $values['proposal'] = $request->file('proposal')->store('proposals', 'public');
        }

        $application->update($values);

        if ($oldProposal) {
            Storage::disk('public')->delete($oldProposal);
        }

        return redirect()->route('panitia.applications.show', $application->id)
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $application = $this->ownedApplications()->findOrFail($id);

        if ($blocked = $this->notEditable($application, 'dibatalkan')) {
            return $blocked;
        }

        $proposal = $application->proposal;
        $application->delete();

        if ($proposal) {
            Storage::disk('public')->delete($proposal);
        }

        return redirect()->route('panitia.applications.index')
            ->with('success', 'Pengajuan dibatalkan. Kamu bisa mengajukan lagi selama sponsorship masih dibuka.');
    }

    public function show(string $id): View
    {
        $application = $this->ownedApplications()
            ->with('sponsorship.sponsor')
            ->findOrFail($id);

        return view('panitia.applications.show', compact('application'));
    }

    public function create(string $id): View|RedirectResponse
    {
        $sponsorship = Sponsorship::with('sponsor')->findOrFail($id);

        if ($blocked = $this->cannotApply($sponsorship)) {
            return $blocked;
        }

        return view('panitia.applications.create', compact('sponsorship'));
    }

    public function store(Request $request, string $id): RedirectResponse
    {
        $sponsorship = Sponsorship::findOrFail($id);

        if ($blocked = $this->cannotApply($sponsorship)) {
            return $blocked;
        }

        $data = $request->validate($this->rules(proposalRequired: true), $this->messages());

        $path = $request->file('proposal')->store('proposals', 'public');

        try {
            $application = $this->profile()->applications()->create(
                Arr::except($data, ['proposal']) + [
                    'sponsorship_id' => $sponsorship->id,
                    'proposal'       => $path,
                    'status'         => SponsorshipApplication::STATUS_PENDING,
                ]
            );
        } catch (UniqueConstraintViolationException) {
            Storage::disk('public')->delete($path);

            return redirect()->route('panitia.sponsorships.index')
                ->with('error', 'Kamu sudah pernah mengajukan ke sponsorship ini.');
        }

        return redirect()->route('panitia.applications.show', $application->id)
            ->with('success', 'Pengajuan berhasil dikirim. Pantau statusnya di halaman ini.');
    }

    private function ownedApplications()
    {
        return $this->profile()->applications();
    }

    private function cannotApply(Sponsorship $sponsorship): ?RedirectResponse
    {
        if (! $sponsorship->isOpen()) {
            return redirect()->route('panitia.sponsorships.index')
                ->with('error', 'Sponsorship ini sudah ditutup atau melewati batas pengajuan.');
        }

        $existing = $this->ownedApplications()
            ->where('sponsorship_id', $sponsorship->id)
            ->first();

        if ($existing) {
            return redirect()->route('panitia.applications.show', $existing->id)
                ->with('error', 'Kamu sudah mengajukan ke sponsorship ini. Satu panitia hanya bisa mengajukan satu kali.');
        }

        return null;
    }

        private function notEditable(SponsorshipApplication $application, string $aksi = 'diedit'): ?RedirectResponse
    {
        if ($application->status !== SponsorshipApplication::STATUS_PENDING) {
            return redirect()->route('panitia.applications.show', $application->id)
                ->with('error', "Pengajuan yang sudah diproses sponsor tidak bisa {$aksi}.");
        }

        return null;
    }

    private function rules(bool $proposalRequired): array
    {
        return [
            'nama_event'      => 'required|string|max:255',
            'deskripsi_event' => 'required|string|max:5000',
            'tanggal_event'   => 'required|date|after_or_equal:today',
            'lokasi_event'    => 'required|string|max:255',
            'pesan'           => 'nullable|string|max:2000',
            'proposal'        => [
                $proposalRequired ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'mimetypes:application/pdf',
                'max:5120',
            ],
        ];
    }

    private function messages(): array
    {
        return [
            'required'                     => ':attribute wajib diisi.',
            'max.string'                   => ':attribute maksimal :max karakter.',
            'date'                         => ':attribute harus berupa tanggal yang valid.',
            'tanggal_event.after_or_equal' => 'Tanggal event tidak boleh sebelum hari ini.',
            'proposal.required'            => 'Proposal wajib diunggah dalam format PDF.',
            'proposal.file'                => 'Proposal harus berupa file.',
            'proposal.uploaded'            => 'Proposal gagal diunggah. Pastikan ukuran file tidak melebihi batas server.',
            'proposal.mimes'               => 'Proposal harus berupa file PDF.',
            'proposal.mimetypes'           => 'Proposal harus berupa file PDF.',
            'proposal.max'                 => 'Ukuran proposal maksimal 5 MB.',
        ];
    }
}