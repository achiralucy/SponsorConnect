@php
    $a = $application ?? null;
@endphp

<div class="form-group">
    <label for="nama_event">Nama Event</label>
    <input type="text" name="nama_event" id="nama_event" value="{{ old('nama_event', $a->nama_event ?? '') }}"
           class="@error('nama_event') is-invalid @enderror" required>
</div>

<div class="form-group">
    <label for="deskripsi_event">Deskripsi Event</label>
    <textarea name="deskripsi_event" id="deskripsi_event" class="@error('deskripsi_event') is-invalid @enderror" required>{{ old('deskripsi_event', $a->deskripsi_event ?? '') }}</textarea>
    <div class="hint">Jelaskan tujuan acara, target peserta, dan rangkaian kegiatannya.</div>
</div>

<div class="form-group">
    <label for="tanggal_event">Tanggal Event</label>
    <input type="date" name="tanggal_event" id="tanggal_event"
           value="{{ old('tanggal_event', $a ? $a->tanggal_event->format('Y-m-d') : '') }}"
           class="@error('tanggal_event') is-invalid @enderror" required>
</div>

<div class="form-group">
    <label for="lokasi_event">Lokasi Event</label>
    <input type="text" name="lokasi_event" id="lokasi_event" value="{{ old('lokasi_event', $a->lokasi_event ?? '') }}"
           class="@error('lokasi_event') is-invalid @enderror" required>
</div>

<div class="form-group">
    <label for="proposal">Proposal (PDF, maks. 5 MB)</label>
    <input type="file" name="proposal" id="proposal" accept="application/pdf,.pdf"
           class="@error('proposal') is-invalid @enderror" {{ $a ? '' : 'required' }}>
    @if ($a)
        <div class="hint">
            Proposal saat ini:
            <a href="{{ asset('storage/' . $a->proposal) }}" target="_blank" rel="noopener">lihat PDF</a>.
            Kosongkan kalau tidak ingin menggantinya.
        </div>
    @endif
</div>

<div class="form-group">
    <label for="pesan">Pesan untuk Sponsor <span class="pn-muted">(opsional)</span></label>
    <textarea name="pesan" id="pesan" class="@error('pesan') is-invalid @enderror">{{ old('pesan', $a->pesan ?? '') }}</textarea>
</div>
