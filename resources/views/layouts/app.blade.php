<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SponsorConnect') - SponsorConnect</title>
    @vite('resources/css/app.css')
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
                        <a href="{{ route('sponsor.profile') }}"
                           class="{{ request()->routeIs('sponsor.profile*') ? 'active' : '' }}">Profil</a>
                    @endif

                    {{-- Menu Panitia: Khaila menambahkan link di sini --}}
                    {{-- Menu Admin: Zhafiq menambahkan link di sini --}}
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
</body>
</html>