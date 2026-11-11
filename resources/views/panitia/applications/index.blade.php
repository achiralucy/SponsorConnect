@extends('layouts.app')

@section('title', 'Pengajuan Saya')

@section('content')
    <div class="page-head">
        <div>
            <h1>Pengajuan Saya</h1>
            <p>Semua pengajuan sponsorship yang pernah kamu kirim.</p>
        </div>
        <a href="{{ route('panitia.sponsorships.index') }}" class="btn">+ Cari Sponsorship</a>
    </div>

    <div class="pn-tabs">
        <a href="{{ route('panitia.applications.index') }}" class="{{ empty($filters['status']) ? 'active' : '' }}">Semua</a>
        @foreach (['pending' => 'Menunggu', 'diterima' => 'Diterima', 'ditolak' => 'Ditolak'] as $key => $label)
            <a href="{{ route('panitia.applications.index', ['status' => $key]) }}"
               class="{{ ($filters['status'] ?? null) === $key ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card">
        @if ($applications->isEmpty())
            <div class="empty">
                <p>Belum ada pengajuan di sini.</p>
                <a href="{{ route('panitia.sponsorships.index') }}" class="btn">Cari Sponsorship</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Event</th>
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
                                    <a href="{{ route('panitia.applications.show', $item->id) }}" class="title">{{ $item->nama_event }}</a>
                                    <div class="sub">Dikirim {{ $item->created_at->translatedFormat('d M Y') }}</div>
                                </td>
                                <td>
                                    {{ $item->sponsorship->judul ?? '-' }}
                                    <div class="sub">{{ $item->sponsorship->sponsor->nama_perusahaan ?? '' }}</div>
                                </td>
                                <td>{{ $item->tanggal_event->translatedFormat('d M Y') }}</td>
                                <td>@include('panitia.partials.status-badge', ['status' => $item->status])</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('panitia.applications.show', $item->id) }}" class="btn btn-outline btn-sm">Detail</a>
                                        @if ($item->status === 'pending')
                                            <a href="{{ route('panitia.applications.edit', $item->id) }}" class="btn btn-outline btn-sm">Edit</a>
                                            <form action="{{ route('panitia.applications.destroy', $item->id) }}" method="POST"
                                                  onsubmit="return confirm('Batalkan pengajuan ini? Datanya akan dihapus.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $applications->links() }}</div>
        @endif
    </div>
@endsection
