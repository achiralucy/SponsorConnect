@extends('layouts.app')

@section('title', 'Profil Panitia')
@section('container_class', 'narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1>Profil Panitia</h1>
            <p>Informasi ini tampil ke sponsor saat mereka meninjau pengajuanmu.</p>
        </div>
    </div>

    <div class="card">

        <form action="{{ route('panitia.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_organisasi">Nama Organisasi / Panitia</label>
                <input type="text" name="nama_organisasi" id="nama_organisasi"
                       value="{{ old('nama_organisasi', $profile->nama_organisasi) }}"
                       class="@error('nama_organisasi') is-invalid @enderror" required>
            </div>

            <div class="form-group">
                <label for="nama_penanggung_jawab">Nama Penanggung Jawab</label>
                <input type="text" name="nama_penanggung_jawab" id="nama_penanggung_jawab"
                       value="{{ old('nama_penanggung_jawab', $profile->nama_penanggung_jawab) }}"
                       class="@error('nama_penanggung_jawab') is-invalid @enderror">
            </div>

            <div class="form-group">
                <label for="kontak">Kontak / No. HP / Email</label>
                <input type="text" name="kontak" id="kontak" value="{{ old('kontak', $profile->kontak) }}"
                       class="@error('kontak') is-invalid @enderror">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="@error('alamat') is-invalid @enderror">{{ old('alamat', $profile->alamat) }}</textarea>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Organisasi</label>
                <textarea name="deskripsi" id="deskripsi" class="@error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $profile->deskripsi) }}</textarea>
            </div>

            <button type="submit" class="btn btn-block">Simpan Perubahan</button>
        </form>
    </div>
@endsection
