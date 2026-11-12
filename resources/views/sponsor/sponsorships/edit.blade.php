@extends('layouts.app')

@section('title', 'Edit Sponsorship')
@section('container_class', 'narrow')

@section('content')
    <div class="page-head">
        <div>
            <h1>Edit Sponsorship</h1>
            <p>{{ $sponsorship->judul }}</p>
        </div>
    </div>

    <div class="card">
        <form action="{{ route('sponsor.sponsorships.update', $sponsorship) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('sponsor.sponsorships.form')

            <div class="btn-group">
                <button type="submit" class="btn">Simpan Perubahan</button>
                <a href="{{ route('sponsor.sponsorships.show', $sponsorship) }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
@endsection