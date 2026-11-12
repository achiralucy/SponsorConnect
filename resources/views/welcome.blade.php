@extends('layouts.landing')

@section('content')
@php
$register = Route::has('register') ? route('register') : '#';
// Data dummy — nanti ganti dengan Sponsorship::where('status','open')->latest()->take(4)->get()
$opps = [
 ['TI','NexaTech Solutions','Technology Innovation Festival 2026','Technology','Dukungan untuk festival inovasi teknologi yang mempertemukan startup, mahasiswa, dan praktisi industri.',['Logo pada materi utama','Booth showcase produk','Sesi talk sponsor'],'30 Des 2026','#2563EB,#22D3EE'],
 ['KB','Kopi Bumi Nusantara (UMKM)','Campus Creative Expo 2026','Kreatif & Seni','UMKM kopi lokal mencari acara kampus kreatif untuk memperluas jangkauan merek ke generasi muda.',['Stand produk','Sampling gratis','Mention di media sosial'],'15 Jan 2027','#8B5CF6,#2563EB'],
 ['BF','BankFlow Indonesia','Youth Business Summit 2026','Bisnis & Kewirausahaan','Mendukung summit wirausaha muda dengan fokus literasi keuangan dan pengembangan bisnis.',['Branding panggung','Kesempatan sesi workshop','Akses data peserta (opt-in)'],'10 Jan 2027','#22D3EE,#8B5CF6'],
 ['DC','CloudNine Digital','Digital Community Festival 2026','Komunitas Digital','Mencari festival komunitas digital untuk memperkuat kehadiran brand di kalangan kreator dan developer.',['Logo di website acara','Booth komunitas','Merchandise bersama'],'28 Feb 2027','#2563EB,#8B5CF6'],
];
$features = [
 ['layers','Open Sponsorship Management','Sponsor membuat dan mengelola peluang sponsorship lengkap dengan benefit, persyaratan, dan deadline.'],
 ['search','Smart Opportunity Discovery','Panitia menemukan berbagai peluang sponsorship yang sedang terbuka.'],
 ['send','Easy Sponsorship Application','Panitia mengirim pengajuan kerja sama dengan informasi acara dan proposal digital.'],
 ['check','Application Management','Sponsor memeriksa, menerima, atau menolak pengajuan beserta catatan keputusan.'],
 ['pulse','Application Tracking','Panitia memantau status pengajuan: menunggu, diterima, atau ditolak.'],
 ['shield','Verified User Platform','Admin memverifikasi akun sponsor dan panitia untuk menjaga kepercayaan platform.'],
];
@endphp

{{-- A. HERO --}}
<section class="hero" id="home">
 <div class="wrap">
  <div class="hero-rise">
   <h1>Where Opportunities <span class="grad">Meet Collaboration.</span></h1>
   <p>Connect with the right opportunities. Discover sponsorship opportunities, build meaningful partnerships, and make every event more impactful with SponsorConnect.</p>
   <div class="row">
    <a href="#opportunities" class="btn btn-p">Explore Opportunities <x-landing.icon name="arrow" /></a>
    <a href="{{ $register }}" class="btn btn-g">Become a Sponsor</a>
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
   <div class="fc fc1"><small>Sponsor · Perusahaan</small><b>NexaTech Solutions</b><span class="tag t-open">● Open</span></div>
   <div class="fc fc3"><small>Sponsor · UMKM</small><b>Kopi Bumi Nusantara</b><span class="tag t-open">● Open</span></div>
   <div class="fc fc2"><small>Panitia</small><b>BEM Fakultas Teknik</b><span class="tag t-wait">Proposal terkirim</span></div>
   <div class="fc fc4"><small>Status pengajuan</small><b>Menunggu keputusan</b><span class="tag t-wait">Pending</span></div>
  </div>
 </div>
</section>

{{-- B. OPPORTUNITIES --}}
<section id="opportunities">
 <div class="wrap">
  <div class="head rv">
   <div><h2>Explore Sponsorship Opportunities</h2>
   <p class="lead">Discover opportunities from companies and businesses looking to collaborate with event organizers.</p></div>
   <a href="#" class="btn btn-g">Explore All Opportunities <x-landing.icon name="arrow" /></a>
  </div>
  <div class="grid4">
   @foreach($opps as $o)<x-landing.opportunity-card :o="$o" />@endforeach
  </div>
 </div>
</section>

{{-- C. FEATURES --}}
<section class="features" id="features">
 <div class="wrap">
  <h2 class="rv">Everything You Need for Better Sponsorship Collaboration</h2>
  <div class="grid3">
   @foreach($features as [$ic, $t, $d])<x-landing.feature-card :icon="$ic" :title="$t">{{ $d }}</x-landing.feature-card>@endforeach
  </div>
 </div>
</section>

{{-- D. HOW IT WORKS --}}
<section id="how-it-works">
 <div class="wrap">
  <h2 class="rv">Simple Process, Meaningful Connections</h2>
  <div class="flows">
   <x-landing.steps tone="s" title="Untuk Sponsor" sub="Buka peluang, tentukan keputusan."
    :items="['Create an Account','Complete Your Business Profile','Publish Sponsorship Opportunities','Review Incoming Applications','Accept or Reject Applications']" />
   <x-landing.steps tone="p" title="Untuk Panitia" sub="Temukan peluang, ajukan kerja sama."
    :items="['Create an Account','Complete Your Organization Profile','Explore Open Sponsorships','Submit Your Event Proposal','Track Application Status']" />
  </div>
  <p class="bridge rv">Dua alur, satu ekosistem: <b>langkah 3 sponsor</b> bertemu <b>langkah 3 panitia</b> di halaman peluang sponsorship.</p>
 </div>
</section>

{{-- E. CTA --}}
<div class="cta rv">
 <h2>Ready to Build Meaningful Partnerships?</h2>
 <p class="lead">Whether you're looking for sponsorship opportunities or ready to support the next great event, SponsorConnect brings the right connections closer.</p>
 <div class="row">
  <a href="#opportunities" class="btn btn-w">Explore Opportunities <x-landing.icon name="arrow" /></a>
  <a href="{{ $register }}" class="btn btn-g">Join SponsorConnect</a>
 </div>
</div>
@endsection
