@extends('layouts.app')

@section('title', 'Buat Sponsorship')
@section('container_class', 'narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1>Buat Sponsorship</h1>
            <p>Sponsorship akan langsung dibuka dan terlihat oleh panitia.</p>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('sponsor.sponsorships.store') }}" method="POST">
            @csrf
            @include('sponsor.sponsorships.form')

            <div class="btn-group">
                <button type="submit" class="btn">Simpan</button>
                <a href="{{ route('sponsor.sponsorships.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection