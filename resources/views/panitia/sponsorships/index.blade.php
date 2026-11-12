@extends('layouts.app')

@section('title', 'Cari Sponsorship')

@section('content')
    <div class="page-head">
        <div>
            <h1>Cari Sponsorship</h1>
            <p>Peluang kerja sama yang masih dibuka untuk acaramu.</p>
        </div>
        <a href="{{ route('panitia.applications.index') }}" class="btn btn-outline">Pengajuan Saya</a>
    </div>

    <form method="GET" action="{{ route('panitia.sponsorships.index') }}" class="card pn-filters">
        <div class="form-group">
            <label for="q"><svg class="pn-filter-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"></circle><path d="m16 16 4.5 4.5"></path></svg>Cari</label>
            <input type="text" name="q" id="q" value="{{ $filters['q'] ?? '' }}"
                   placeholder="Judul, benefit, atau nama sponsor">
        </div>

        <div class="form-group">
            <label for="sponsor"><svg class="pn-filter-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 4.4 4h15.2l1.4 6.5"></path><path d="M4 10v10h16V10M8 20v-6h8v6M3 10.5a2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0 2.5 2.5 0 0 0 5 0"></path></svg>Sponsor</label>
            <select name="sponsor" id="sponsor">
                <option value="">Semua sponsor</option>
                @foreach ($sponsors as $sp)
                    <option value="{{ $sp->id }}"
                        {{ (string) ($filters['sponsor'] ?? '') === (string) $sp->id ? 'selected' : '' }}>
                        {{ $sp->nama_perusahaan }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="urutan"><svg class="pn-filter-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M7 12h10m-7 6h4"></path><path d="m17 9 3 3-3 3"></path></svg>Urutkan</label>
            <select name="urutan" id="urutan">
                <option value="terbaru" {{ ($filters['urutan'] ?? 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="deadline" {{ ($filters['urutan'] ?? '') === 'deadline' ? 'selected' : '' }}>Batas pengajuan terdekat</option>
            </select>
        </div>

        <div class="btn-group">
            <button type="submit" class="btn">Terapkan</button>
            <a href="{{ route('panitia.sponsorships.index') }}" class="btn btn-outline">Reset</a>
        </div>
    </form>

    @if ($sponsorships->isEmpty())
        <div class="card empty">
            <p>Belum ada sponsorship terbuka yang cocok dengan pencarianmu.</p>
        </div>
    @else
        <div class="pn-grid">
            @foreach ($sponsorships as $item)
                @php
                    $sisa = (int) today()->diffInDays($item->batas_pengajuan, false);
                    $nama = $item->sponsor->nama_perusahaan ?? 'Sponsor';
                    // Benefit disimpan sebagai teks bebas ("- a - b" atau per baris) -> dipecah jadi daftar singkat
                    $benefits = collect(preg_split('/\R|\s-\s/', (string) $item->benefit))
                        ->map(fn ($b) => trim($b, " \t-•"))
                        ->filter()->values();
                @endphp
                <article class="card pn-card">
                    <div class="pn-card-top">
                        <div class="pn-sponsor">
                            @if ($item->sponsor?->logo_url)
                                <img src="{{ $item->sponsor->logo_url }}" alt="Logo {{ $nama }}" class="pn-logo">
                            @else
                                <span class="pn-logo-fallback" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($nama, 0, 1)) }}</span>
                            @endif
                            <span>{{ $nama }}</span>
                        </div>
                        <span class="pn-due {{ $sisa <= 7 ? 'pn-due-soon' : '' }}">{{ $sisa <= 0 ? 'Hari terakhir' : $sisa . ' hari lagi' }}</span>
                    </div>

                    <div class="pn-card-main">
                        <div class="pn-card-text">
                            <h2><a href="{{ route('panitia.sponsorships.show', $item->id) }}">{{ $item->judul }}</a></h2>
                            <p class="pn-muted pn-card-description">{{ $item->deskripsi }}</p>
                        </div>
                        @if ($item->foto_url)
                            <img src="{{ $item->foto_url }}" alt="Foto {{ $item->judul }}" class="pn-thumb" loading="lazy">
                        @endif
                    </div>

                    @if ($benefits->isNotEmpty())
                        <ul class="pn-chips" aria-label="Benefit">
                            @foreach ($benefits->take(3) as $b)
                                <li>{{ \Illuminate\Support\Str::limit($b, 26) }}</li>
                            @endforeach
                            @if ($benefits->count() > 3)
                                <li class="pn-chip-more">+{{ $benefits->count() - 3 }}</li>
                            @endif
                        </ul>
                    @endif

                    <div class="pn-card-foot">
                        <span class="pn-muted">Batas {{ $item->batas_pengajuan->translatedFormat('d M Y') }}</span>
                        <div class="btn-group pn-card-actions">
                            <a href="{{ route('panitia.sponsorships.show', $item->id) }}" class="btn btn-outline btn-sm">Detail</a>
                            @if ($item->sudah_diajukan)
                                <span class="badge pn-badge-diterima">Sudah diajukan</span>
                            @else
                                <a href="{{ route('panitia.sponsorships.apply', $item->id) }}" class="btn btn-sm">Ajukan</a>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="pagination-wrap">{{ $sponsorships->links() }}</div>
    @endif
@endsection
