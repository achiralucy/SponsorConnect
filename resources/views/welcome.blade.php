@extends('layouts.landing')

@section('content')
@php
$login = Route::has('login') ? route('login') : '#';
$register = Route::has('register') ? route('register') : '#';
$masuk = $login;      // tombol "Jadi Sponsor" / "Gabung"
$isPanitia = auth()->check() && auth()->user()->role === 'panitia';

// Tujuan tombol "Lihat detail": panitia ke halaman detail, lainnya ke login / dashboard
$detailUrl = function ($s) use ($isPanitia, $login) {
    if ($isPanitia && Route::has('panitia.sponsorships.show')) return route('panitia.sponsorships.show', $s);
    return auth()->check() ? route('dashboard') : $login;
};
$semuaUrl = ($isPanitia && Route::has('panitia.sponsorships.index'))
    ? route('panitia.sponsorships.index')
    : (auth()->check() ? route('dashboard') : $login);

$features = [
 ['layers','Kelola Open Sponsorship','Sponsor membuat dan mengelola peluang sponsorship lengkap dengan benefit, persyaratan, dan batas pengajuan.'],
 ['search','Temukan Peluang Sponsorship','Panitia mencari peluang sponsorship yang sedang dibuka, lalu membaca detail dan persyaratannya.'],
 ['send','Ajukan Kerja Sama dengan Mudah','Panitia mengirim pengajuan berisi informasi acara dan proposal digital.'],
 ['check','Kelola Pengajuan Masuk','Sponsor memeriksa pengajuan, lalu menerima atau menolaknya dengan catatan keputusan.'],
 ['pulse','Pantau Status Pengajuan','Panitia melihat status pengajuan: menunggu, diterima, atau ditolak.'],
 ['shield','Akun Terverifikasi','Admin memverifikasi akun sponsor dan panitia agar platform tetap tepercaya.'],
];
@endphp

{{-- A. HERO --}}
<section class="hero" id="home">
 <div class="wrap">
  <div class="hero-rise">
   <h1>Tempat Peluang <span class="grad">Bertemu Kolaborasi.</span></h1>
   <p>Temukan peluang sponsorship, bangun kemitraan yang berarti, dan buat setiap acara lebih berdampak bersama SponsorConnect.</p>
   <div class="row">
    <a href="{{ $masuk }}" class="btn btn-p">Lihat Peluang Sponsorship</a>
    <a href="{{ $masuk }}" class="btn btn-g">Jadi Sponsor</a>
   </div>
  </div>
  <div class="net hero-rise" style="animation-delay:.15s" aria-hidden="true">
   <svg class="lines" viewBox="0 0 460 460" preserveAspectRatio="none">
    <g fill="none" stroke-width="1.5">
     <path class="flow" d="M90 70 Q150 150 230 230" stroke="#22D3EE"/><path class="flow" d="M60 380 Q150 320 230 230" stroke="#22D3EE"/>
     <path class="flow" d="M400 100 Q320 160 230 230" stroke="#8B5CF6"/><path class="flow" d="M400 400 Q320 320 230 230" stroke="#8B5CF6"/>
    </g>
   </svg>
   <div class="hub">Open<br>Sponsorship</div>
   <div class="fc fc1"><small>Sponsor</small><b>Membuka peluang</b><span class="tag t-open">● Dibuka</span></div>
   <div class="fc fc3"><small>Sponsor</small><b>Meninjau pengajuan</b><span class="tag t-open">Diterima / Ditolak</span></div>
   <div class="fc fc2"><small>Panitia</small><b>Mengirim proposal</b><span class="tag t-wait">Terkirim</span></div>
   <div class="fc fc4"><small>Panitia</small><b>Memantau status</b><span class="tag t-wait">Menunggu</span></div>
  </div>
 </div>
</section>

{{-- B. PELUANG SPONSORSHIP (data asli dari sponsor) --}}
<section id="opportunities">
 <div class="wrap">
  <div class="head rv">
   <div><h2>Jelajahi Peluang Sponsorship</h2>
   <p class="lead">Peluang kerja sama dari perusahaan dan UMKM yang sedang mencari acara untuk didukung.</p></div>
   <a href="{{ $semuaUrl }}" class="btn btn-g">Lihat Semua Peluang</a>
  </div>
  <div class="oc-grid">
   @forelse($sponsorships as $s)
    <x-landing.opportunity-card :s="$s" :href="$detailUrl($s)" />
   @empty
    <div class="empty"><b>Belum ada peluang yang dibuka</b>Sponsor terverifikasi dapat membuka peluang sponsorship kapan saja.</div>
   @endforelse
  </div>
 </div>
</section>

{{-- C. FITUR --}}
<section class="features" id="features">
 <div class="wrap">
  <h2 class="rv">Semua yang Dibutuhkan untuk Kolaborasi Sponsorship yang Lebih Baik</h2>
  <div class="grid3">
   @foreach($features as [$ic, $t, $d])<x-landing.feature-card :icon="$ic" :title="$t">{{ $d }}</x-landing.feature-card>@endforeach
  </div>
 </div>
</section>

{{-- D. CARA KERJA --}}
<section id="how-it-works">
 <div class="wrap">
  <h2 class="rv">Proses Sederhana, Koneksi yang Bermakna</h2>
  <div class="flows">
   <x-landing.steps tone="s" title="Untuk Sponsor" sub="Buka peluang, tentukan keputusan."
    :items="['Buat akun','Lengkapi profil bisnis','Buka peluang sponsorship','Tinjau pengajuan yang masuk','Terima atau tolak pengajuan']" />
   <x-landing.steps tone="p" title="Untuk Panitia" sub="Temukan peluang, ajukan kerja sama."
    :items="['Buat akun','Lengkapi profil organisasi','Jelajahi peluang yang dibuka','Kirim proposal acara','Pantau status pengajuan']" />
  </div>
  <p class="bridge rv">Dua alur, satu ekosistem: peluang yang dibuka sponsor adalah peluang yang dijelajahi panitia.</p>
 </div>
</section>

{{-- E. CTA --}}
<div class="cta rv">
 <h2>Siap Membangun Kemitraan yang Bermakna?</h2>
 <p class="lead">Baik Anda mencari peluang sponsorship maupun siap mendukung acara berikutnya, SponsorConnect mendekatkan koneksi yang tepat.</p>
 <div class="row">
  <a href="{{ $masuk }}" class="btn btn-w">Lihat Peluang Sponsorship</a>
  <a href="{{ $masuk }}" class="btn btn-g">Gabung SponsorConnect</a>
 </div>
</div>
@endsection
