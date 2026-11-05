@extends('layouts.guest')

@section('title', 'Status Verifikasi')

@section('content')
    <h2>Status Verifikasi Akun</h2>

    @if ($user->status_verifikasi === 'pending')
        <div class="verification-status">
            <h3>Pendaftaran Sedang Ditinjau</h3>
            <p>
                Terima kasih telah mendaftar di SponsorConnect.
                Akun Anda sedang menunggu proses verifikasi oleh Admin.
            </p>
            <p>
                Silakan login kembali secara berkala untuk memeriksa status akun Anda.
            </p>
        </div>

    @elseif ($user->status_verifikasi === 'terverifikasi')
        <div class="verification-status">
            <h3>Akun Berhasil Diverifikasi</h3>
            <p>
                Selamat! Akun Anda telah disetujui oleh Admin.
                Sekarang Anda sudah dapat menggunakan fitur SponsorConnect.
            </p>

            <a href="{{ route('dashboard') }}" class="btn btn-block">
                Masuk ke Dashboard
            </a>
        </div>

    @elseif ($user->status_verifikasi === 'ditolak')
        <div class="verification-status">
            <h3>Pendaftaran Ditolak</h3>
            <p>
                Mohon maaf, pendaftaran akun Anda belum dapat disetujui oleh Admin.
            </p>

            @if ($user->catatan_verifikasi)
                <p><strong>Catatan dari Admin:</strong></p>
                <p>{{ $user->catatan_verifikasi }}</p>
            @endif

            <p>
                Jika terdapat pertanyaan mengenai keputusan ini,
                silakan hubungi Admin.
            </p>
        </div>
    @endif

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-block">
            Logout
        </button>
    </form>
@endsection

@section('footer')
    SponsorConnect - Platform Pengajuan dan Pengelolaan Sponsorship Acara
@endsection
