@props(['name', 'class' => ''])
@php
$paths = [
 'layers'=>'<path d="m12 2 10 5-10 5L2 7z"/><path d="m2 17 10 5 10-5M2 12l10 5 10-5"/>',
 'search'=>'<circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/>',
 'send'=>'<path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>',
 'check'=>'<rect x="5" y="3" width="14" height="18" rx="2"/><path d="m9 13 2 2 4-4"/>',
 'pulse'=>'<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
 'shield'=>'<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
 'arrow'=>'<path d="M5 12h14M12 5l7 7-7 7"/>',
 'cal'=>'<rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/>',
 'dot'=>'<path d="m5 12 5 5 9-10"/>',
];
@endphp
<svg class="ic {{ $class }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $paths[$name] ?? '' !!}</svg>
