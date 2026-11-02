<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SponsorConnect</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #f4f6f9; margin: 0; }
        .card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 350px; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-weight: bold; }
        input { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: .75rem; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background: #218838; }
        .error { color: red; font-size: 0.875rem; margin-top: 0.25rem; }
        .alert-success { background: #d4edda; color: #155724; padding: .75rem; border-radius: 4px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Login</h2>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error" style="margin-bottom: 1rem;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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

            <button type="submit">Masuk</button>
        </form>

        <p style="text-align: center; margin-top: 1rem;">
            Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
        </p>
    </div>
</body>
</html>