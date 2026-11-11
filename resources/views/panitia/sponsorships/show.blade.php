@extends('layouts.app')

@section('title', $sponsorship->judul)

@section('content')
    <div class="page-head">
        <div>
            <h1>{{ $sponsorship->judul }}</h1>
            <span class="badge {{ $sponsorship->isOpen() ? 'badge-open' : 'badge-closed' }}">
                {{ $sponsorship->status_label }}
            </span>
        </div>
        <div class="btn-group">
            @if ($myApplication)
                <a href="{{ route('panitia.applications.show', $myApplication->id) }}" class="btn">Lihat Pengajuanku</a>
            @elseif ($sponsorship->isOpen())
                <a href="{{ route('panitia.sponsorships.apply', $sponsorship->id) }}" class="btn">Ajukan Sponsorship</a>
            @endif
            <a href="{{ route('panitia.sponsorships.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>

    <div class="card">
        <div class="meta">
            <span>Sponsor: <strong>{{ $sponsorship->sponsor->nama_perusahaan ?? '-' }}</strong></span>
            <span>Batas pengajuan: <strong>{{ $sponsorship->batas_pengajuan->translatedFormat('d F Y') }}</strong></span>
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
    </div>

    @if ($sponsorship->sponsor)
        <div class="card" style="margin-top:1rem">
            <h2>Tentang Sponsor</h2>
            @if (!empty($sponsorship->sponsor->logo))
                <img src="{{ asset('storage/' . $sponsorship->sponsor->logo) }}" alt="Logo sponsor" class="pn-logo-lg">
            @endif
            <div class="meta">
                @if ($sponsorship->sponsor->bidang_usaha)
                    <span>Bidang usaha: <strong>{{ $sponsorship->sponsor->bidang_usaha }}</strong></span>
                @endif
                @if ($sponsorship->sponsor->kontak)
                    <span>Kontak: <strong>{{ $sponsorship->sponsor->kontak }}</strong></span>
                @endif
            </div>
            @if ($sponsorship->sponsor->deskripsi)
                <div class="detail-block">
                    <p>{{ $sponsorship->sponsor->deskripsi }}</p>
                </div>
            @endif
        </div>
    @endif
@endsection
