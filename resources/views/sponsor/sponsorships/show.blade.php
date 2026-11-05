@extends('layouts.app')

@section('title', $sponsorship->judul)

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $sponsorship->judul }}</h1>
            <span class="badge {{ $sponsorship->status === 'open' ? 'badge-open' : 'badge-closed' }}">
                {{ $sponsorship->status_label }}
            </span>
        </div>
        <div class="btn-group">
            <a href="{{ route('sponsor.sponsorships.edit', $sponsorship) }}" class="btn">Edit</a>
            <a href="{{ route('sponsor.sponsorships.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>

    <div class="card">
        <div class="meta">
            <span>Batas pengajuan: <strong>{{ $sponsorship->batas_pengajuan->translatedFormat('d F Y') }}</strong></span>
            <span>Pengajuan masuk: <strong>{{ $sponsorship->applications_count }}</strong></span>
            <span>Dibuat: <strong>{{ $sponsorship->created_at->translatedFormat('d M Y') }}</strong></span>
        </div>

        <div class="detail-block" style="margin-bottom:1rem">
            <h3>Deskripsi</h3>
            <p>{{ $sponsorship->deskripsi }}</p>
        </div>

        <div class="detail-grid">
            <div class="detail-block">
                <h3>Benefit</h3>
                <p>{{ $sponsorship->benefit }}</p>
            </div>
            <div class="detail-block">
                <h3>Persyaratan</h3>
                <p>{{ $sponsorship->persyaratan }}</p>
            </div>
        </div>

        <div class="btn-group" style="margin-top:1.25rem">
            @if ($sponsorship->status === 'open')
                <form action="{{ route('sponsor.sponsorships.close', $sponsorship) }}" method="POST"
                      onsubmit="return confirm('Tutup sponsorship ini? Panitia tidak bisa mengajukan lagi.')">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warn">Tutup Sponsorship</button>
                </form>
            @endif
            <form action="{{ route('sponsor.sponsorships.destroy', $sponsorship) }}" method="POST"
                  onsubmit="return confirm('Hapus sponsorship ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
        </div>
    </div>

   
@endsection