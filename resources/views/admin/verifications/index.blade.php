@extends('layouts.app')

@section('title', 'Verifikasi Akun')

@section('content')
    <div class="page-head">
        <div>
            <h1>Verifikasi Akun</h1>
            <p class="sub">Tinjau alasan pendaftaran sebelum menyetujui atau menolak akun.</p>
        </div>
    </div>

    @if ($users->isEmpty())
        <div class="card empty">Tidak ada pendaftaran yang menunggu verifikasi.</div>
    @else
        <div class="table-wrap card">
            <table>
                <thead>
                    <tr>
                        <th>Pendaftar</th>
                        <th>Role</th>
                        <th>Alasan Mendaftar</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @php($bukaTolak = (string) old('reject_user_id') === (string) $user->id)
                        <tr>
                            <td>
                                <div class="title">{{ $user->name }}</div>
                                <div class="sub">{{ $user->email }}</div>
                                <div class="sub">Daftar {{ $user->created_at->format('d M Y H:i') }}</div>
                            </td>

                            <td>{{ ucfirst($user->role) }}</td>

                            <td class="alasan-cell">{{ $user->alasan_mendaftar }}</td>

                            <td class="aksi-cell">
                                <div class="aksi-buttons">
                                    <form action="{{ route('admin.verifications.approve', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm">Setujui</button>
                                    </form>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="document.getElementById('tolak-{{ $user->id }}').toggleAttribute('hidden')">
                                        Tolak
                                    </button>
                                </div>

                                <form id="tolak-{{ $user->id }}" class="tolak-form"
                                    action="{{ route('admin.verifications.reject', $user) }}" method="POST"
                                    @unless($bukaTolak) hidden @endunless>
                                    @csrf
                                    <input type="hidden" name="reject_user_id" value="{{ $user->id }}">

                                    <label for="catatan_verifikasi_{{ $user->id }}">Catatan penolakan (wajib)</label>
                                    <textarea name="catatan_verifikasi" id="catatan_verifikasi_{{ $user->id }}"
                                        rows="3" required maxlength="2000"
                                        class="{{ $bukaTolak && $errors->has('catatan_verifikasi') ? 'is-invalid' : '' }}"
                                        placeholder="Tulis alasan penolakan...">{{ $bukaTolak ? old('catatan_verifikasi') : '' }}</textarea>

                                    @if ($bukaTolak)
                                        @error('catatan_verifikasi')
                                            <div class="field-error">{{ $message }}</div>
                                        @enderror
                                    @endif

                                    <button type="submit" class="btn btn-danger btn-sm">Kirim Penolakan</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">{{ $users->links() }}</div>
    @endif
@endsection