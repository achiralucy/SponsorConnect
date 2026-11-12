@extends('layouts.app')

@section('title', 'Profil Sponsor')
@section('container_class', 'narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1>Profil Sponsor</h1>
            <p>Informasi ini tampil ke panitia saat mereka melihat sponsorship milikmu.</p>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('sponsor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_perusahaan">Nama Perusahaan / UMKM</label>
                <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $profile->nama_perusahaan ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="bidang_usaha">Bidang Usaha</label>
                <input type="text" name="bidang_usaha" id="bidang_usaha" value="{{ old('bidang_usaha', $profile->bidang_usaha ?? '') }}">
            </div>

            <div class="form-group">
                <label for="kontak">Kontak / No. HP / Email</label>
                <input type="text" name="kontak" id="kontak" value="{{ old('kontak', $profile->kontak ?? '') }}">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Profil</label>
                <textarea name="deskripsi" id="deskripsi">{{ old('deskripsi', $profile->deskripsi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="logo">Logo Perusahaan</label>
                <input type="file" name="logo" id="logo" accept="image/*">
                @if ($profile->logo_url)
                    <p class="hint">Logo saat ini:</p>
                    <img src="{{ $profile->logo_url }}" alt="Logo {{ $profile->nama_perusahaan }}" class="sponsor-profile-logo">
                @endif
            </div>

            <button type="submit" class="btn btn-block">Simpan Perubahan</button>
        </form>
    </div>
@endsection