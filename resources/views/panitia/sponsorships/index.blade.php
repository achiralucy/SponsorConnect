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
            <label for="q">Cari</label>
            <input type="text" name="q" id="q" value="{{ $filters['q'] ?? '' }}"
                   placeholder="Judul, benefit, atau nama sponsor">
        </div>

        <div class="form-group">
            <label for="sponsor">Sponsor</label>
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
            <label for="urutan">Urutkan</label>
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
                @php $sisa = (int) today()->diffInDays($item->batas_pengajuan, false); @endphp
                <div class="card pn-card">
                    <div class="pn-sponsor">
                        @if (!empty($item->sponsor?->logo))
                            <img src="{{ asset('storage/' . $item->sponsor->logo) }}" alt="Logo" class="pn-logo">
                        @endif
                        <span>{{ $item->sponsor->nama_perusahaan ?? 'Sponsor' }}</span>
                    </div>

                    <h2><a href="{{ route('panitia.sponsorships.show', $item->id) }}">{{ $item->judul }}</a></h2>
                    <p class="pn-muted">{{ \Illuminate\Support\Str::limit($item->deskripsi, 110) }}</p>
                    <p class="pn-benefit"><strong>Benefit:</strong> {{ \Illuminate\Support\Str::limit($item->benefit, 80) }}</p>

                    <div class="meta">
                        <span>Batas: <strong>{{ $item->batas_pengajuan->translatedFormat('d M Y') }}</strong>
                            ({{ $sisa <= 0 ? 'hari terakhir' : $sisa . ' hari lagi' }})</span>
                    </div>

                    <div class="btn-group pn-card-actions">
                        <a href="{{ route('panitia.sponsorships.show', $item->id) }}" class="btn btn-outline btn-sm">Detail</a>
                        @if ($item->sudah_diajukan)
                            <span class="badge pn-badge-diterima">Sudah diajukan</span>
                        @else
                            <a href="{{ route('panitia.sponsorships.apply', $item->id) }}" class="btn btn-sm">Ajukan</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination-wrap">{{ $sponsorships->links() }}</div>
    @endif
@endsection
