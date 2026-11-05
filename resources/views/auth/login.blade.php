@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <h2>Masuk</h2>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
        </div>

        <button type="submit" class="btn btn-block">Masuk</button>
    </form>
@endsection

@section('footer')
    Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
@endsection