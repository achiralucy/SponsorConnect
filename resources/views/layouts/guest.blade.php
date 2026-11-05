<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - SponsorConnect</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="guest-wrap">
        <div class="guest-card">
            <div class="guest-brand">
                <a href="{{ url('/') }}" class="brand">Sponsor<span>Connect</span></a>
            </div>

            <div class="card">
                @include('partials.alerts')
                @yield('content')
            </div>

            <p class="guest-foot">@yield('footer')</p>
        </div>
    </div>
</body>
</html>