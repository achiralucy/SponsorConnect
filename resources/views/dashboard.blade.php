@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <div class="card">
        <h1>Halo, {{ auth()->user()->name }}!</h1>
        <p>
            Kamu masuk sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong>
            dan akunmu sudah terverifikasi.
        </p>

        
        <p style="color:var(--muted);margin-bottom:0">Fitur untuk peran ini sedang dalam pengembangan.</p>
    </div>
@endsection