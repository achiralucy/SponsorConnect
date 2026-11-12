{{-- Form bersama untuk tambah & edit sponsorship. Variabel $sponsorship hanya ada saat edit. --}}
@php($s = $sponsorship ?? null)

<div class="form-group">
    <label for="judul">Judul Sponsorship</label>
    <input type="text" name="judul" id="judul" value="{{ old('judul', $s->judul ?? '') }}"
           class="@error('judul') is-invalid @enderror" required>
</div>

<div class="form-group">
    <label for="deskripsi">Deskripsi</label>
    <textarea name="deskripsi" id="deskripsi" class="@error('deskripsi') is-invalid @enderror" required>{{ old('deskripsi', $s->deskripsi ?? '') }}</textarea>
    <div class="hint">Jelaskan jenis acara atau kerja sama seperti apa yang kamu cari.</div>
</div>

<div class="form-group">
    <label for="benefit">Benefit untuk Panitia</label>
    <textarea name="benefit" id="benefit" class="@error('benefit') is-invalid @enderror" required>{{ old('benefit', $s->benefit ?? '') }}</textarea>
    <div class="hint">Apa yang kamu tawarkan, misalnya dana, produk, atau fasilitas.</div>
</div>

<div class="form-group">
    <label for="persyaratan">Persyaratan</label>
    <textarea name="persyaratan" id="persyaratan" class="@error('persyaratan') is-invalid @enderror" required>{{ old('persyaratan', $s->persyaratan ?? '') }}</textarea>
    <div class="hint">Syarat yang harus dipenuhi panitia, misalnya jumlah peserta atau bentuk promosi.</div>
</div>

<div class="form-group">
    <label for="foto">Foto Sponsorship</label>
    <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp"
           class="@error('foto') is-invalid @enderror">
    <div class="hint">Opsional. Format JPG, PNG, atau WebP, maksimal 4 MB.</div>
    @error('foto') <div class="field-error">{{ $message }}</div> @enderror
    @if ($s?->foto_url)
        <img src="{{ $s->foto_url }}" alt="Foto {{ $s->judul }}" class="sponsorship-photo-preview" data-photo-preview>
    @else
        <img alt="Pratinjau foto sponsorship" class="sponsorship-photo-preview" data-photo-preview hidden>
    @endif
</div>

<div class="form-group">
    <label for="batas_pengajuan">Batas Pengajuan</label>
    <input type="date" name="batas_pengajuan" id="batas_pengajuan"
           value="{{ old('batas_pengajuan', isset($s) ? $s->batas_pengajuan->format('Y-m-d') : '') }}"
           class="@error('batas_pengajuan') is-invalid @enderror" required>
    <div class="hint">Setelah tanggal ini sponsorship otomatis ditutup.</div>
</div>

@if ($s)
    <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" class="@error('status') is-invalid @enderror">
            <option value="open" {{ old('status', $s->status) === 'open' ? 'selected' : '' }}>Dibuka</option>
            <option value="closed" {{ old('status', $s->status) === 'closed' ? 'selected' : '' }}>Ditutup</option>
        </select>
    </div>
@endif