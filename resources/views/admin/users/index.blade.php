@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="page-head">
        <div>
            <h1>Kelola Pengguna</h1>
            <p>Akun yang ditolak tidak ditampilkan. Akun dengan riwayat sponsorship atau pengajuan tidak dapat dihapus.</p>
        </div>
    </div>

    <form action="{{ route('admin.users.index') }}" method="GET" class="card admin-user-filters">
        <div class="form-group">
            <label for="q">Cari nama atau email</label>
            <input type="text" name="q" id="q" value="{{ $filters['q'] ?? '' }}" maxlength="100">
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role">
                <option value="">Semua role</option>
                @foreach (['sponsor' => 'Sponsor', 'panitia' => 'Panitia', 'admin' => 'Admin'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['role'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">Status verifikasi</label>
            <select name="status" id="status">
                <option value="">Semua status</option>
                @foreach (['pending' => 'Menunggu', 'terverifikasi' => 'Terverifikasi'] as $value => $label)
                    <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn">Terapkan</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Reset</a>
        </div>
    </form>

    @if ($users->isEmpty())
        <div class="card empty">Tidak ada pengguna yang cocok dengan pencarian atau filter.</div>
    @else
        <div class="table-wrap card">
            <table>
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Role</th>
                        <th>Status Verifikasi</th>
                        <th>Terdaftar</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <div class="title">{{ $user->name }}</div>
                                <div class="sub">{{ $user->email }}</div>
                            </td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ ucfirst($user->status_verifikasi) }}</td>
                            <td>{{ $user->created_at->format('d M Y') }}</td>
                            <td>
                                @if ($user->is(auth()->user()))
                                    <span class="sub">Akun yang sedang digunakan</span>
                                @else
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            data-confirm="Hapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $users->links() }}</div>
    @endif
@endsection
