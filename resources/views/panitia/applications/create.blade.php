@extends('layouts.app')

@section('title', 'Ajukan Sponsorship')
@section('container_class', 'narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1>Ajukan Sponsorship</h1>
            <p>{{ $sponsorship->judul }} &middot; {{ $sponsorship->sponsor->nama_perusahaan ?? 'Sponsor' }}</p>
        </div>
    </div>

    <div class="card pn-reminder">
        <strong>Batas pengajuan: {{ $sponsorship->batas_pengajuan->translatedFormat('d F Y') }}</strong>
        <div class="pn-muted">Persyaratan: {{ \Illuminate\Support\Str::limit($sponsorship->persyaratan, 200) }}</div>
    </div>

    <div class="card">
        <form action="{{ route('panitia.sponsorships.apply.store', $sponsorship->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('panitia.applications.form')

            <div class="btn-group">
                <button type="submit" class="btn">Kirim Pengajuan</button>
                <a href="{{ route('panitia.sponsorships.show', $sponsorship->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection
