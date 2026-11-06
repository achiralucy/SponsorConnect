@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <h2>Daftar Akun</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap / Instansi</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="role">Daftar Sebagai</label>
            <select name="role" id="role" required>
                <option value="panitia" {{ old('role') == 'panitia' ? 'selected' : '' }}>Panitia Acara</option>
                <option value="sponsor" {{ old('role') == 'sponsor' ? 'selected' : '' }}>Sponsor (Perusahaan / UMKM)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="alasan_mendaftar">Alasan Mendaftar</label>
            <textarea name="alasan_mendaftar" id="alasan_mendaftar" rows="4" required>{{ old('alasan_mendaftar') }}</textarea>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-block">Daftar</button>
    </form>
@endsection

@section('footer')
    Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
@endsection
