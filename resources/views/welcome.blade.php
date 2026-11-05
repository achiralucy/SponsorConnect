@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <section class="hero">
        <h1>Temukan sponsor tanpa kirim proposal secara acak</h1>
        <p>
            SponsorConnect mempertemukan sponsor dan panitia acara lewat sistem open sponsorship:
            sponsor membuka peluang lebih dulu, panitia mengajukan sesuai persyaratan.
        </p>

        @guest
            <div class="btn-group" style="justify-content:center">
                <a href="{{ route('register') }}" class="btn">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
            </div>
        @else
            <a href="{{ route('dashboard') }}" class="btn">Ke Beranda Saya</a>
        @endguest
    </section>

    <section class="role-cards">
        <div class="card role-card">
            <h3>Sponsor</h3>
            <p>Perusahaan atau UMKM membuka peluang sponsorship lengkap dengan benefit, persyaratan, dan batas waktu pengajuan.</p>
        </div>
        <div class="card role-card">
            <h3>Panitia</h3>
            <p>Cari sponsorship yang cocok dengan acaramu, ajukan kerja sama, lalu pantau statusnya di satu tempat.</p>
        </div>
        <div class="card role-card">
            <h3>Admin</h3>
            <p>Memverifikasi akun dan memantau aktivitas supaya platform tetap berkualitas dan bebas akun palsu.</p>
        </div>
    </section>
@endsection