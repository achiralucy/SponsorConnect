@php
    $map = [
        'pending'  => ['Menunggu', 'pn-badge-pending'],
        'diterima' => ['Diterima', 'pn-badge-diterima'],
        'ditolak'  => ['Ditolak', 'pn-badge-ditolak'],
    ];
    [$label, $class] = $map[$status] ?? [ucfirst($status), 'badge-closed'];
@endphp
<span class="badge {{ $class }}">{{ $label }}</span>
