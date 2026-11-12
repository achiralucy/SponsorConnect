@props(['title', 'sub', 'items', 'tone' => 's'])
<div class="flow-box {{ $tone }} rv">
 <h3>{{ $title }}</h3><small>{{ $sub }}</small>
 <ol class="steps">@foreach($items as $s)<li>{{ $s }}</li>@endforeach</ol>
</div>
