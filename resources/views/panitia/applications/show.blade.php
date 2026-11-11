@extends('layouts.app')

@section('title', $application->nama_event)

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $application->nama_event }}</h1>
            @include('panitia.partials.status-badge', ['status' => $application->status])
        </div>
        <div class="btn-group">
            @if ($application->status === 'pending')
                <a href="{{ route('panitia.applications.edit', $application->id) }}" class="btn">Edit</a>
            @endif
            <a href="{{ route('panitia.applications.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>

    @if ($application->catatan_sponsor)
        <div class="card pn-note">
            <h3>Catatan dari Sponsor</h3>
            <p>{{ $application->catatan_sponsor }}</p>
        </div>
    @endif

    <div class="card" @if ($application->catatan_sponsor) style="margin-top:1rem" @endif>
        <div class="meta">
            <span>Sponsorship:
                <strong>{{ $application->sponsorship->judul ?? '-' }}</strong>
                ({{ $application->sponsorship->sponsor->nama_perusahaan ?? '-' }})
            </span>
            <span>Dikirim: <strong>{{ $application->created_at->translatedFormat('d M Y, H:i') }}</strong></span>
        </div>

        <div class="detail-grid">
            <div class="detail-block">
                <h3>Tanggal Event</h3>
                <p>{{ $application->tanggal_event->translatedFormat('d F Y') }}</p>
            </div>
            <div class="detail-block">
                <h3>Lokasi Event</h3>
                <p>{{ $application->lokasi_event }}</p>
            </div>
        </div>

        <div class="detail-block" style="margin-bottom:1rem">
            <h3>Deskripsi Event</h3>
            <p>{{ $application->deskripsi_event }}</p>
        </div>

        @if ($application->pesan)
            <div class="detail-block" style="margin-bottom:1rem">
                <h3>Pesan untuk Sponsor</h3>
                <p>{{ $application->pesan }}</p>
            </div>
        @endif

        <div class="detail-block">
            <h3>Proposal</h3>
            <p><a href="{{ asset('storage/' . $application->proposal) }}" target="_blank" rel="noopener">Buka proposal (PDF)</a></p>
        </div>

        @if ($application->status === 'pending')
            <div class="btn-group" style="margin-top:1.25rem">
                <form action="{{ route('panitia.applications.destroy', $application->id) }}" method="POST"
                      onsubmit="return confirm('Batalkan pengajuan ini? Datanya akan dihapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Batalkan Pengajuan</button>
                </form>
            </div>
        @endif
    </div>
@endsection
