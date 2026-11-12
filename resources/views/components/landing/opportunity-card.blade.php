{{-- $o = ['initials','sponsor','title','category','description',[benefits],'deadline','gradient'] --}}
@props(['o'])
@php [$ini, $name, $title, $cat, $desc, $ben, $dl, $g] = $o; @endphp
<article class="card rv">
 <div class="top">
  <div class="who"><span class="av" style="background:linear-gradient(135deg,{{ $g }})">{{ $ini }}</span>{{ $name }}</div>
  <span class="tag t-open" style="font-size:11.5px;padding:3px 10px;border-radius:99px;font-weight:600">● Open</span>
 </div>
 <h3>{{ $title }}</h3>
 <span class="cat">{{ $cat }}</span>
 <p>{{ $desc }}</p>
 <ul class="ben">@foreach($ben as $b)<li><x-landing.icon name="dot" />{{ $b }}</li>@endforeach</ul>
 <div class="dl"><x-landing.icon name="cal" />Deadline pengajuan: {{ $dl }}</div>
 <a href="#" class="btn btn-g">View Details <x-landing.icon name="arrow" /></a>
</article>
