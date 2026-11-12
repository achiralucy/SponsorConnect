{{-- $s = model Sponsorship (dengan relasi sponsor), $href = tujuan tombol --}}
@props(['s', 'href'])
@php
 $nama = $s->sponsor->nama_perusahaan ?? 'Sponsor';
 $logo = $s->sponsor->logo_url ?? null;
@endphp
<article class="oc rv">
 <div class="oc-who">
  @if($logo)<img class="oc-logo" src="{{ $logo }}" alt="" loading="lazy">
  @else<i class="oc-logo">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($nama, 0, 2)) }}</i>@endif
  <span>{{ $nama }}</span>
 </div>
 <h3>{{ $s->judul }}</h3>
 <p>{{ \Illuminate\Support\Str::limit(strip_tags($s->deskripsi), 110) }}</p>
 <div class="oc-foot">
  <small>Batas {{ $s->batas_pengajuan->locale('id')->translatedFormat('j M Y') }}</small>
  <a href="{{ $href }}" class="oc-link">Lihat detail</a>
 </div>
</article>
