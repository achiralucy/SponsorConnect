@extends('layouts.app')

@section('title', 'Beranda Admin')

@section('content')
    <div class="page-head">
        <div>
            <h1>Beranda Admin</h1>
            <p>Ringkasan akun dan akses cepat pengelolaan SponsorConnect.</p>
        </div>
    </div>

    <section class="admin-stats" aria-label="Ringkasan akun">
        <div class="card admin-stat">
            <span>Total Pengguna</span>
            <strong>{{ $stats['total'] }}</strong>
        </div>
        <div class="card admin-stat">
            <span>Menunggu Verifikasi</span>
            <strong>{{ $stats['pending'] }}</strong>
        </div>
        <div class="card admin-stat">
            <span>Terverifikasi</span>
            <strong>{{ $stats['verified'] }}</strong>
        </div>
        <div class="card admin-stat">
            <span>Ditolak</span>
            <strong>{{ $stats['rejected'] }}</strong>
        </div>
        <div class="card admin-stat">
            <span>Sponsor</span>
            <strong>{{ $stats['sponsors'] }}</strong>
        </div>
        <div class="card admin-stat">
            <span>Panitia</span>
            <strong>{{ $stats['panitia'] }}</strong>
        </div>
    </section>

    <section class="admin-shortcuts">
        <a class="card admin-shortcut" href="{{ route('admin.verifications.index') }}">
            <h2>Verifikasi Akun</h2>
            <p>Tinjau alasan pendaftaran dan proses {{ $stats['pending'] }} akun yang masih menunggu.</p>
            <span class="btn btn-sm">Buka verifikasi</span>
        </a>
        <a class="card admin-shortcut" href="{{ route('admin.users.index') }}">
            <h2>Kelola Pengguna</h2>
            <p>Cari dan filter daftar pengguna berdasarkan role atau status verifikasi.</p>
            <span class="btn btn-outline btn-sm">Lihat pengguna</span>
        </a>
    </section>
@endsection
