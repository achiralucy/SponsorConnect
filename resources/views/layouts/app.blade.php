<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SponsorConnect') - SponsorConnect</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="navbar">
        <div class="navbar-inner">
            <a href="{{ url('/') }}" class="brand">Sponsor<span>Connect</span></a>

            @auth
                <nav class="nav-links">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a>

                    {{-- Menu Sponsor --}}
                    @if (auth()->user()->role === 'sponsor')
                        <a href="{{ route('sponsor.sponsorships.index') }}"
                           class="{{ request()->routeIs('sponsor.sponsorships.*') ? 'active' : '' }}">Sponsorship Saya</a>
                                <a href="{{ route('sponsor.applications.index') }}"
                                    class="{{ request()->routeIs('sponsor.applications.*') ? 'active' : '' }}">Pengajuan Masuk</a>
                        <a href="{{ route('sponsor.profile') }}"
                           class="{{ request()->routeIs('sponsor.profile*') ? 'active' : '' }}">Profil</a>
                    @endif

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}"
                           class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Beranda Admin</a>
                        <a href="{{ route('admin.users.index') }}"
                           class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Kelola Pengguna</a>
                        <a href="{{ route('admin.verifications.index') }}"
                           class="{{ request()->routeIs('admin.verifications.*') ? 'active' : '' }}">Verifikasi Akun</a>
                    @endif

                    @if (auth()->user()->role === 'panitia')
                        <a href="{{ route('panitia.sponsorships.index') }}"
                        class="{{ request()->routeIs('panitia.sponsorships.*') ? 'active' : '' }}">Cari Sponsorship</a>
                        <a href="{{ route('panitia.applications.index') }}"
                        class="{{ request()->routeIs('panitia.applications.*') ? 'active' : '' }}">Pengajuan Saya</a>
                        <a href="{{ route('panitia.profile') }}"
                        class="{{ request()->routeIs('panitia.profile*') ? 'active' : '' }}">Profil</a>
                    @endif

                </nav>

                <div class="nav-user">
                    <span>{{ auth()->user()->name }} <span class="role-badge">{{ auth()->user()->role }}</span></span>
                    <form action="{{ route('logout') }}" method="POST" style="margin:0">
                        @csrf
                        <button type="submit" class="btn btn-outline btn-sm">Keluar</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="container @yield('container_class')">
        @include('partials.alerts')
        @yield('content')
    </main>
    @include('partials.confirm-dialog')
</body>
</html>