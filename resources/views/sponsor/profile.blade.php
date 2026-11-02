<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Sponsor - SponsorConnect</title>
    <style>
        body { font-family: sans-serif; background: #f4f6f9; margin: 0; padding: 2rem; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: .5rem; font-weight: bold; }
        input, textarea { width: 100%; padding: .5rem; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 100px; resize: vertical; }
        button { width: 100%; padding: .75rem; background: #007bff; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert-success { background: #d4edda; color: #155724; padding: .75rem; border-radius: 4px; margin-bottom: 1rem; }
        .logo-preview { margin-top: 1rem; text-align: center; }
        .logo-preview img { max-width: 150px; border-radius: 8px; border: 1px solid #ddd; padding: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Profil Sponsor</h2>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('sponsor.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama_perusahaan">Nama Perusahaan / UMKM</label>
                <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan', $profile->nama_perusahaan ?? '') }}" required>
            </div>

            <div class="form-group">
                <label for="bidang_usaha">Bidang Usaha</label>
                <input type="text" name="bidang_usaha" id="bidang_usaha" value="{{ old('bidang_usaha', $profile->bidang_usaha ?? '') }}">
            </div>

            <div class="form-group">
                <label for="kontak">Kontak / No. HP / Email</label>
                <input type="text" name="kontak" id="kontak" value="{{ old('kontak', $profile->kontak ?? '') }}">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Profil</label>
                <textarea name="deskripsi" id="deskripsi">{{ old('deskripsi', $profile->deskripsi ?? '') }}</textarea>
            </div>

            <div class="form-group">
                <label for="logo">Logo Perusahaan</label>
                <input type="file" name="logo" id="logo" accept="image/*">
            </div>

            @if(!empty($profile->logo))
                <div class="logo-preview">
                    <p>Logo Saat Ini:</p>
                    <img src="{{ asset('storage/' . $profile->logo) }}" alt="Logo Sponsor">
                </div>
            @endif

            <br>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>