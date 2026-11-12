@extends('layouts.app')

@section('title', 'Tinjau Pengajuan')

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $application->nama_event }}</h1>
            <p>{{ $application->panitia->nama_organisasi ?? $application->panitia->user->name ?? 'Panitia' }}</p>
        </div>
        <a href="{{ route('sponsor.applications.index', ['sponsorship' => $application->sponsorship_id]) }}" class="btn btn-outline">Kembali</a>
    </div>

    <div class="card">
        <div class="application-status-row">
            <div>
                <div class="hint">Sponsorship</div>
                <strong>{{ $application->sponsorship->judul }}</strong>
            </div>
            <span class="badge {{ $application->status === 'diterima' ? 'pn-badge-diterima' : ($application->status === 'ditolak' ? 'pn-badge-ditolak' : 'pn-badge-pending') }}">
                {{ $application->status === 'diterima' ? 'Diterima' : ($application->status === 'ditolak' ? 'Ditolak' : 'Menunggu') }}
            </span>
        </div>

        <div class="detail-grid">
            <div class="detail-block">
                <h3>Tanggal Event</h3>
                <p>{{ $application->tanggal_event->translatedFormat('d F Y') }}</p>
            </div>
            <div class="detail-block">
                <h3>Lokasi</h3>
                <p>{{ $application->lokasi_event }}</p>
            </div>
        </div>

        <div class="detail-block application-description">
            <h3>Deskripsi Event</h3>
            <p>{{ $application->deskripsi_event }}</p>
        </div>

        @if ($application->pesan)
            <div class="detail-block application-description">
                <h3>Pesan dari Panitia</h3>
                <p>{{ $application->pesan }}</p>
            </div>
        @endif

        <div class="detail-block application-description">
            <h3>Proposal</h3>
            <a href="{{ asset('storage/' . $application->proposal) }}" target="_blank" rel="noopener">Buka proposal PDF</a>
        </div>

        @if ($application->catatan_sponsor)
            <div class="pn-note application-note">
                <h3>Catatan Sponsor</h3>
                <p>{{ $application->catatan_sponsor }}</p>
            </div>
        @endif

        @if ($application->status === 'pending')
            <form action="{{ route('sponsor.applications.review', $application->id) }}" method="POST" class="application-review-form" data-review-form data-confirm="Kirim keputusan untuk pengajuan ini? Keputusan tidak dapat diubah setelah dikirim.">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="status">Keputusan</label>
                    <select name="status" id="status" required>
                        <option value="">Pilih keputusan</option>
                        <option value="diterima" {{ old('status') === 'diterima' ? 'selected' : '' }}>Terima pengajuan</option>
                        <option value="ditolak" {{ old('status') === 'ditolak' ? 'selected' : '' }}>Tolak pengajuan</option>
                    </select>
                    @error('status') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label for="catatan_sponsor">Catatan untuk panitia <span class="hint">wajib jika ditolak</span></label>
                    <textarea name="catatan_sponsor" id="catatan_sponsor" maxlength="2000" placeholder="Tulis catatan atau alasan keputusan...">{{ old('catatan_sponsor') }}</textarea>
                    @error('catatan_sponsor') <div class="field-error">{{ $message }}</div> @enderror
                </div>

                <button type="submit" class="btn">Kirim Keputusan</button>
            </form>
        @endif
    </div>
@endsection