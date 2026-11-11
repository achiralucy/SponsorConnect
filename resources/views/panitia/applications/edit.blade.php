@extends('layouts.app')

@section('title', 'Edit Pengajuan')
@section('container_class', 'narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1>Edit Pengajuan</h1>
            <p>{{ $application->sponsorship->judul ?? '-' }} &middot; {{ $application->sponsorship->sponsor->nama_perusahaan ?? 'Sponsor' }}</p>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('panitia.applications.update', $application->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('panitia.applications.form')

            <div class="btn-group">
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="{{ route('panitia.applications.show', $application->id) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection
