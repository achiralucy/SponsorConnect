<?php

namespace Tests\Feature;

use App\Models\PanitiaProfile;
use App\Models\Sponsorship;
use App\Models\SponsorshipApplication;
use App\Models\SponsorProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SponsorshipCrudTest extends TestCase
{
    use RefreshDatabase;

    private function makeSponsor(string $email = 'sponsor@test.com'): User
    {
        $user = User::create([
            'name' => 'PT Maju', 'email' => $email, 'password' => 'password',
            'role' => 'sponsor', 'status_verifikasi' => 'terverifikasi',
        ]);
        SponsorProfile::create(['user_id' => $user->id, 'nama_perusahaan' => 'PT Maju']);

        return $user;
    }

    private function payload(array $override = []): array
    {
        return array_merge([
            'judul' => 'Sponsor Seminar Nasional',
            'deskripsi' => 'Mencari acara kampus',
            'benefit' => 'Dana Rp5.000.000',
            'persyaratan' => 'Minimal 200 peserta',
            'batas_pengajuan' => now()->addDays(10)->toDateString(),
        ], $override);
    }

    private function makeSponsorship(User $sponsor, array $override = []): Sponsorship
    {
        return $sponsor->sponsorProfile->sponsorships()->create(array_merge(
            $this->payload(), ['status' => 'open'], $override
        ));
    }

    public function test_relasi_model_terhubung(): void
    {
        $sponsor = $this->makeSponsor();
        $s = $this->makeSponsorship($sponsor);

        $this->assertTrue($sponsor->sponsorProfile->is($s->sponsor));
        $this->assertCount(1, $sponsor->sponsorProfile->sponsorships);

        $pUser = User::create(['name' => 'BEM', 'email' => 'bem@test.com', 'password' => 'password',
            'role' => 'panitia', 'status_verifikasi' => 'terverifikasi']);
        $panitia = PanitiaProfile::create(['user_id' => $pUser->id, 'nama_organisasi' => 'BEM']);
        $app = SponsorshipApplication::create([
            'sponsorship_id' => $s->id, 'panitia_id' => $panitia->id, 'nama_event' => 'Seminar',
            'deskripsi_event' => 'x', 'tanggal_event' => now()->addMonth(), 'lokasi_event' => 'Aula',
            'proposal' => 'p.pdf', 'status' => 'pending',
        ]);

        $this->assertTrue($app->sponsorship->is($s));
        $this->assertTrue($app->panitia->is($panitia));
        $this->assertCount(1, $s->applications);
        $this->assertCount(1, $panitia->applications);
        $this->assertTrue($pUser->panitiaProfile->is($panitia));
    }

    public function test_sponsor_bisa_membuat_sponsorship(): void
    {
        $sponsor = $this->makeSponsor();

        $this->actingAs($sponsor)->post('/sponsor/sponsorships', $this->payload())
            ->assertRedirect('/sponsor/sponsorships');

        $this->assertDatabaseHas('sponsorships', [
            'judul' => 'Sponsor Seminar Nasional', 'status' => 'open',
            'sponsor_id' => $sponsor->sponsorProfile->id,
        ]);
    }

    public function test_validasi_form_tambah(): void
    {
        $sponsor = $this->makeSponsor();

        $this->actingAs($sponsor)->post('/sponsor/sponsorships', [])
            ->assertSessionHasErrors(['judul', 'deskripsi', 'benefit', 'persyaratan', 'batas_pengajuan']);

        $this->actingAs($sponsor)->post('/sponsor/sponsorships',
            $this->payload(['batas_pengajuan' => now()->subDay()->toDateString()]))
            ->assertSessionHasErrors('batas_pengajuan');
    }

    public function test_daftar_hanya_menampilkan_milik_sendiri(): void
    {
        $a = $this->makeSponsor('a@test.com');
        $b = $this->makeSponsor('b@test.com');
        $this->makeSponsorship($a, ['judul' => 'Milik A']);
        $this->makeSponsorship($b, ['judul' => 'Milik B']);

        $this->actingAs($a)->get('/sponsor/sponsorships')
            ->assertOk()->assertSee('Milik A')->assertDontSee('Milik B');
    }

    public function test_sponsor_lain_tidak_bisa_akses_edit_hapus(): void
    {
        $a = $this->makeSponsor('a@test.com');
        $b = $this->makeSponsor('b@test.com');
        $s = $this->makeSponsorship($a);

        $this->actingAs($b)->get("/sponsor/sponsorships/{$s->id}")->assertNotFound();
        $this->actingAs($b)->get("/sponsor/sponsorships/{$s->id}/edit")->assertNotFound();
        $this->actingAs($b)->put("/sponsor/sponsorships/{$s->id}", $this->payload(['status' => 'open']))->assertNotFound();
        $this->actingAs($b)->patch("/sponsor/sponsorships/{$s->id}/close")->assertNotFound();
        $this->actingAs($b)->delete("/sponsor/sponsorships/{$s->id}")->assertNotFound();
        $this->assertDatabaseHas('sponsorships', ['id' => $s->id, 'status' => 'open']);
    }

    public function test_panitia_dan_tamu_tidak_bisa_mengakses(): void
    {
        $panitia = User::create(['name' => 'P', 'email' => 'p@test.com', 'password' => 'password',
            'role' => 'panitia', 'status_verifikasi' => 'terverifikasi']);

        $this->actingAs($panitia)->get('/sponsor/sponsorships')->assertForbidden();
        $this->post('/logout');
        auth()->logout();
        $this->get('/sponsor/sponsorships')->assertRedirect('/login');
    }

    public function test_edit_dan_update(): void
    {
        $sponsor = $this->makeSponsor();
        $s = $this->makeSponsorship($sponsor);

        $this->actingAs($sponsor)->get("/sponsor/sponsorships/{$s->id}/edit")->assertOk()->assertSee($s->judul);

        $this->actingAs($sponsor)->put("/sponsor/sponsorships/{$s->id}",
            $this->payload(['judul' => 'Judul Baru', 'status' => 'closed']))
            ->assertRedirect("/sponsor/sponsorships/{$s->id}");

        $this->assertDatabaseHas('sponsorships', ['id' => $s->id, 'judul' => 'Judul Baru', 'status' => 'closed']);
    }

    public function test_tidak_bisa_membuka_lagi_dengan_tanggal_lewat(): void
    {
        $sponsor = $this->makeSponsor();
        $s = $this->makeSponsorship($sponsor, ['status' => 'closed', 'batas_pengajuan' => now()->subDays(3)]);

        $this->actingAs($sponsor)->put("/sponsor/sponsorships/{$s->id}",
            $this->payload(['batas_pengajuan' => now()->subDays(3)->toDateString(), 'status' => 'open']))
            ->assertSessionHasErrors('batas_pengajuan');

        // tetap boleh disimpan kalau statusnya closed
        $this->actingAs($sponsor)->put("/sponsor/sponsorships/{$s->id}",
            $this->payload(['batas_pengajuan' => now()->subDays(3)->toDateString(), 'status' => 'closed']))
            ->assertSessionHasNoErrors();
    }

    public function test_tutup_manual(): void
    {
        $sponsor = $this->makeSponsor();
        $s = $this->makeSponsorship($sponsor);

        $this->actingAs($sponsor)->patch("/sponsor/sponsorships/{$s->id}/close")->assertRedirect();
        $this->assertDatabaseHas('sponsorships', ['id' => $s->id, 'status' => 'closed']);
    }

    public function test_hapus_kosong_berhasil_tapi_yang_punya_pengajuan_ditolak(): void
    {
        $sponsor = $this->makeSponsor();
        $kosong = $this->makeSponsorship($sponsor, ['judul' => 'Kosong']);
        $berisi = $this->makeSponsorship($sponsor, ['judul' => 'Berisi']);

        $pUser = User::create(['name' => 'BEM', 'email' => 'bem@test.com', 'password' => 'password',
            'role' => 'panitia', 'status_verifikasi' => 'terverifikasi']);
        $panitia = PanitiaProfile::create(['user_id' => $pUser->id, 'nama_organisasi' => 'BEM']);
        SponsorshipApplication::create([
            'sponsorship_id' => $berisi->id, 'panitia_id' => $panitia->id, 'nama_event' => 'E',
            'deskripsi_event' => 'x', 'tanggal_event' => now()->addMonth(), 'lokasi_event' => 'Aula',
            'proposal' => 'p.pdf',
        ]);

        $this->actingAs($sponsor)->delete("/sponsor/sponsorships/{$kosong->id}")->assertRedirect('/sponsor/sponsorships');
        $this->assertDatabaseMissing('sponsorships', ['id' => $kosong->id]);

        $this->actingAs($sponsor)->delete("/sponsor/sponsorships/{$berisi->id}")->assertSessionHas('error');
        $this->assertDatabaseHas('sponsorships', ['id' => $berisi->id]);
    }

    public function test_penutupan_otomatis_dan_scope_open(): void
    {
        $sponsor = $this->makeSponsor();
        $lewat = $this->makeSponsorship($sponsor, ['judul' => 'Lewat', 'batas_pengajuan' => now()->subDay()]);
        $hariIni = $this->makeSponsorship($sponsor, ['judul' => 'Hari Ini', 'batas_pengajuan' => now()]);
        $masihBuka = $this->makeSponsorship($sponsor, ['judul' => 'Masih']);

        // scope open() sudah menyaring yang lewat walau status di DB belum diubah
        $this->assertEqualsCanonicalizing(
            ['Hari Ini', 'Masih'], Sponsorship::open()->pluck('judul')->all()
        );
        $this->assertFalse($lewat->fresh()->isOpen());
        $this->assertTrue($hariIni->fresh()->isOpen());

        $this->assertSame(1, Sponsorship::closeExpired());
        $this->assertSame('closed', $lewat->fresh()->status);
        $this->assertSame('open', $hariIni->fresh()->status);
        $this->assertSame('open', $masihBuka->fresh()->status);

        $this->assertSame('Kedaluwarsa', $this->makeSponsorship($sponsor, ['status' => 'open', 'batas_pengajuan' => now()->subDay()])->status_label);
    }

    public function test_halaman_daftar_menutup_otomatis(): void
    {
        $sponsor = $this->makeSponsor();
        $lewat = $this->makeSponsorship($sponsor, ['batas_pengajuan' => now()->subDay()]);

        $this->actingAs($sponsor)->get('/sponsor/sponsorships')->assertOk()->assertSee('Ditutup');
        $this->assertSame('closed', $lewat->fresh()->status);
    }

    public function test_halaman_publik_dan_dashboard_dirender(): void
    {
        $this->get('/')->assertOk()->assertSee('SponsorConnect');
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();

        $sponsor = $this->makeSponsor();
        $this->actingAs($sponsor)->get('/dashboard')->assertRedirect('/sponsor/sponsorships');
        $this->actingAs($sponsor)->get('/sponsor/profile')->assertOk()->assertSee('Profil Sponsor');
        $this->actingAs($sponsor)->get('/sponsor/sponsorships/create')->assertOk();

        $panitia = User::create(['name' => 'P', 'email' => 'p@test.com', 'password' => 'password',
            'role' => 'panitia', 'status_verifikasi' => 'terverifikasi']);
        $this->actingAs($panitia)->get('/dashboard')->assertOk()->assertSee('Halo, P');
    }

    public function test_akun_belum_verifikasi_tetap_diblokir(): void
    {
        $user = User::create(['name' => 'X', 'email' => 'x@test.com', 'password' => 'password',
            'role' => 'sponsor', 'status_verifikasi' => 'pending']);
        SponsorProfile::create(['user_id' => $user->id, 'nama_perusahaan' => 'X']);

        $this->actingAs($user)->get('/sponsor/sponsorships')->assertRedirect('/login');
    }
}