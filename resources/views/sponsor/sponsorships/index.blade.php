@extends('layouts.app')

@section('title', 'Sponsorship Saya')

@section('content')
    <div class="page-head">
        <div>
            <h1>Sponsorship Saya</h1>
            <p>Peluang kerja sama yang sudah kamu buka untuk panitia.</p>
        </div>
        <a href="{{ route('sponsor.sponsorships.create') }}" class="btn">+ Buat Sponsorship</a>
    </div>

    <div class="card">
        @if ($sponsorships->isEmpty())
            <div class="empty">
                <p>Belum ada sponsorship. Buat yang pertama supaya panitia bisa mengajukan kerja sama.</p>
                <a href="{{ route('sponsor.sponsorships.create') }}" class="btn">Buat Sponsorship</a>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Batas Pengajuan</th>
                            <th>Pengajuan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sponsorships as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('sponsor.sponsorships.show', $item) }}" class="title">{{ $item->judul }}</a>
                                    <div class="sub">{{ \Illuminate\Support\Str::limit($item->deskripsi, 70) }}</div>
                                </td>
                                <td>{{ $item->batas_pengajuan->translatedFormat('d M Y') }}</td>
                                <td>{{ $item->applications_count }}</td>
                                <td>
                                    <span class="badge {{ $item->status === 'open' ? 'badge-open' : 'badge-closed' }}">
                                        {{ $item->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('sponsor.sponsorships.show', $item) }}" class="btn btn-outline btn-sm">Detail</a>
                                        <a href="{{ route('sponsor.sponsorships.edit', $item) }}" class="btn btn-outline btn-sm">Edit</a>
                                        @if ($item->status === 'open')
                                            <form action="{{ route('sponsor.sponsorships.close', $item) }}" method="POST"
                                                  onsubmit="return confirm('Tutup sponsorship ini? Panitia tidak bisa mengajukan lagi.')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warn btn-sm">Tutup</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('sponsor.sponsorships.destroy', $item) }}" method="POST"
                                              onsubmit="return confirm('Hapus sponsorship ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="pagination-wrap">{{ $sponsorships->links() }}</div>
        @endif
    </div>
@endsection