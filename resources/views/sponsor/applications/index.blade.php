@extends('layouts.app')

@section('title', 'Pengajuan Masuk')

@section('content')
    <div class="page-head">
        <div>
            <h1>Pengajuan Masuk</h1>
            <p>Tinjau proposal panitia untuk sponsorship yang kamu buka.</p>
        </div>
        <a href="{{ route('sponsor.sponsorships.index') }}" class="btn btn-outline">Sponsorship Saya</a>
    </div>

    <div class="pn-tabs">
        @foreach (['' => 'Semua', 'pending' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $key => $label)
            <a href="{{ route('sponsor.applications.index', array_filter(['status' => $key, 'sponsorship' => $filters['sponsorship'] ?? null])) }}"
               class="{{ ($filters['status'] ?? '') === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card">
        @if ($applications->isEmpty())
            <div class="empty">
                <p>Belum ada pengajuan yang sesuai.</p>
                <a href="{{ route('sponsor.sponsorships.index') }}" class="btn">Lihat Sponsorship</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Panitia / Event</th>
                            <th>Sponsorship</th>
                            <th>Tanggal Event</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($applications as $item)
                            <tr>
                                <td>
                                    <div class="title">{{ $item->panitia->nama_organisasi ?? $item->panitia->user->name ?? 'Panitia' }}</div>
                                    <div class="sub">{{ $item->nama_event }}</div>
                                </td>
                                <td>{{ $item->sponsorship->judul }}</td>
                                <td>{{ $item->tanggal_event->translatedFormat('d M Y') }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'diterima' ? 'pn-badge-diterima' : ($item->status === 'ditolak' ? 'pn-badge-ditolak' : 'pn-badge-pending') }}">
                                        {{ $item->status === 'diterima' ? 'Diterima' : ($item->status === 'ditolak' ? 'Ditolak' : 'Menunggu') }}
                                    </span>
                                </td>
                                <td><a href="{{ route('sponsor.applications.show', $item->id) }}" class="btn btn-outline btn-sm">Tinjau</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $applications->links() }}</div>
        @endif
    </div>
@endsection