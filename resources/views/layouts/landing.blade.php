@php
 $login = Route::has('login') ? route('login') : '#';
 $register = Route::has('register') ? route('register') : '#';
 $home = url('/');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'SponsorConnect — Tempat Peluang Bertemu Kolaborasi')</title>
<meta name="description" content="Platform open sponsorship: sponsor membuka peluang, panitia mengajukan kerja sama.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@vite(['resources/css/landing.css', 'resources/js/landing.js'])
</head>
<body>
<header class="nav">
 <div class="wrap">
  <a href="{{ $home }}#home" class="logo"><i><x-landing.icon name="layers" /></i>SponsorConnect</a>
  <nav class="links" id="links" aria-label="Navigasi utama">
   <a href="{{ $home }}#home">Beranda</a><a href="{{ $home }}#opportunities">Peluang</a><a href="{{ $home }}#features">Fitur</a><a href="{{ $home }}#how-it-works">Cara Kerja</a>
  </nav>
  <div class="nav-cta">
   <a href="{{ $login }}" class="btn btn-g btn-login">Masuk</a>
   <a href="{{ $register }}" class="btn btn-p btn-register">Daftar</a>
   <button class="burger" id="burger" aria-label="Buka menu" aria-expanded="false">☰</button>
  </div>
 </div>
</header>
<main>@yield('content')</main>
<footer>
 <div class="wrap">
  <div class="fgrid">
   <div>
    <a href="{{ $home }}#home" class="logo"><i><x-landing.icon name="layers" /></i>SponsorConnect</a>
    <p>Platform open sponsorship yang mempertemukan sponsor dari perusahaan dan UMKM dengan panitia penyelenggara acara.</p>
    <div class="soc"><a href="#" aria-label="Instagram">IG</a><a href="#" aria-label="LinkedIn">in</a><a href="#" aria-label="X">X</a><a href="#" aria-label="YouTube">YT</a></div>
   </div>
   <div><h4>Navigasi</h4><ul><li><a href="{{ $home }}#home">Beranda</a></li><li><a href="{{ $home }}#opportunities">Peluang Sponsorship</a></li><li><a href="{{ $home }}#features">Fitur</a></li><li><a href="{{ $home }}#how-it-works">Cara Kerja</a></li></ul></div>
   <div><h4>Perusahaan</h4><ul><li><a href="#">Tentang Kami</a></li><li><a href="#">Kontak</a></li></ul></div>
  </div>
  <p class="copy" style="max-width:none">© {{ date('Y') }} SponsorConnect. Hak cipta dilindungi.</p>
 </div>
</footer>
</body>
</html>
