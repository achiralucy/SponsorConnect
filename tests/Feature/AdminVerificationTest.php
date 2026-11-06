<?php

namespace Tests\Feature;

use App\Models\PanitiaProfile;
use App\Models\SponsorProfile;
use App\Models\SponsorshipApplication;
use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    private function makeAdmin(): User
    {
        return User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => 'password',
            'role' => 'admin',
            'status_verifikasi' => 'terverifikasi',
        ]);
    }

    private function makePendingUser(string $email = 'pending@test.com'): User
    {
        return User::create([
            'name' => 'Pendaftar',
            'email' => $email,
            'password' => 'password',
            'role' => 'panitia',
            'status_verifikasi' => 'pending',
            'alasan_mendaftar' => 'Ingin mencari sponsor untuk acara kampus.',
        ]);
    }

    public function test_admin_dapat_melihat_pendaftar_pending_dan_menyetujui(): void
    {
        $admin = $this->makeAdmin();
        $pending = $this->makePendingUser();
        $this->makePendingUser('other@test.com')->update(['status_verifikasi' => 'ditolak']);

        $this->actingAs($admin)
            ->get('/admin/verifications')
            ->assertOk()
            ->assertSee($pending->email)
            ->assertSee($pending->alasan_mendaftar)
            ->assertDontSee('other@test.com');

        $this->actingAs($admin)
            ->post("/admin/verifications/{$pending->id}/approve")
            ->assertRedirect('/admin/verifications')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $pending->id,
            'status_verifikasi' => 'terverifikasi',
            'catatan_verifikasi' => null,
        ]);
        $this->actingAs($admin)->get('/dashboard')->assertRedirect('/admin/dashboard');
        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Beranda Admin')
            ->assertSee('Total Pengguna');
    }

    public function test_admin_harus_memberi_catatan_untuk_menolak_dan_pendaftar_dapat_melihatnya(): void
    {
        $admin = $this->makeAdmin();
        $pending = $this->makePendingUser();

        $this->actingAs($admin)
            ->post("/admin/verifications/{$pending->id}/reject", ['catatan_verifikasi' => ''])
            ->assertSessionHasErrors('catatan_verifikasi');

        $this->actingAs($admin)
            ->post("/admin/verifications/{$pending->id}/reject", [
                'catatan_verifikasi' => 'Mohon lengkapi informasi acara.',
            ])
            ->assertRedirect('/admin/verifications')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $pending->id,
            'status_verifikasi' => 'ditolak',
            'catatan_verifikasi' => 'Mohon lengkapi informasi acara.',
        ]);

        $this->actingAs($pending->fresh())
            ->get('/status-verifikasi')
            ->assertOk()
            ->assertSee('Mohon lengkapi informasi acara.');
    }

    public function test_hanya_admin_terverifikasi_dapat_mengakses_pengelolaan_verifikasi(): void
    {
        $pending = $this->makePendingUser();
        $panitia = User::create([
            'name' => 'Panitia',
            'email' => 'panitia@test.com',
            'password' => 'password',
            'role' => 'panitia',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->actingAs($panitia)->get('/admin/verifications')->assertForbidden();
        $this->actingAs($panitia)->post("/admin/verifications/{$pending->id}/approve")->assertForbidden();
        $this->actingAs($panitia)->get('/admin/users')->assertForbidden();
        $this->actingAs($pending)->get('/admin/verifications')->assertRedirect('/status-verifikasi');
    }

    public function test_admin_dapat_mencari_dan_memfilter_pengguna_tanpa_mengubahnya(): void
    {
        $admin = $this->makeAdmin();
        $pending = $this->makePendingUser();
        $rejectedUser = User::create([
            'name' => 'Pendaftar Ditolak',
            'email' => 'rejected@test.com',
            'password' => 'password',
            'role' => 'panitia',
            'status_verifikasi' => 'ditolak',
        ]);
        $verifiedSponsor = User::create([
            'name' => 'PT Contoh',
            'email' => 'sponsor@test.com',
            'password' => 'password',
            'role' => 'sponsor',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->actingAs($admin)
            ->get('/admin/users?q=pending')
            ->assertOk()
            ->assertSee($pending->email)
            ->assertDontSee($verifiedSponsor->email)
            ->assertDontSee($rejectedUser->email);

        $this->actingAs($admin)
            ->get('/admin/users?role=sponsor&status=terverifikasi')
            ->assertOk()
            ->assertSee($verifiedSponsor->email)
            ->assertDontSee($pending->email)
            ->assertDontSee($rejectedUser->email);

        $this->actingAs($admin)
            ->get('/admin/users?role=admin')
            ->assertOk()
            ->assertSee($admin->email)
            ->assertDontSee($rejectedUser->email);

        $this->actingAs($admin)
            ->get('/admin/users?status=ditolak')
            ->assertOk()
            ->assertDontSee($rejectedUser->email);

        $this->assertDatabaseHas('users', [
            'id' => $pending->id,
            'status_verifikasi' => 'pending',
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $rejectedUser->id,
            'status_verifikasi' => 'ditolak',
        ]);
    }

    public function test_admin_dapat_menghapus_pengguna_tanpa_riwayat(): void
    {
        $admin = $this->makeAdmin();
        $user = User::create([
            'name' => 'Sponsor Tanpa Riwayat',
            'email' => 'empty-sponsor@test.com',
            'password' => 'password',
            'role' => 'sponsor',
            'status_verifikasi' => 'pending',
        ]);
        $profile = SponsorProfile::create([
            'user_id' => $user->id,
            'nama_perusahaan' => 'Sponsor Tanpa Riwayat',
        ]);

        $this->actingAs($admin)
            ->delete("/admin/users/{$user->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->assertDatabaseMissing('sponsor_profiles', ['id' => $profile->id]);
    }

    public function test_admin_tidak_dapat_menghapus_akun_sendiri(): void
    {
        $admin = $this->makeAdmin();

        $this->actingAs($admin)
            ->delete("/admin/users/{$admin->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id, 'role' => 'admin']);
    }

    public function test_admin_tidak_dapat_menghapus_sponsor_yang_memiliki_sponsorship(): void
    {
        $admin = $this->makeAdmin();
        $user = User::create([
            'name' => 'Sponsor Dengan Riwayat',
            'email' => 'history-sponsor@test.com',
            'password' => 'password',
            'role' => 'sponsor',
            'status_verifikasi' => 'terverifikasi',
        ]);
        $profile = SponsorProfile::create([
            'user_id' => $user->id,
            'nama_perusahaan' => 'Sponsor Dengan Riwayat',
        ]);
        $sponsorship = $profile->sponsorships()->create([
            'judul' => 'Sponsorship Aktif',
            'deskripsi' => 'Deskripsi',
            'benefit' => 'Benefit',
            'persyaratan' => 'Persyaratan',
            'batas_pengajuan' => now()->addDays(10)->toDateString(),
        ]);

        $this->actingAs($admin)
            ->delete("/admin/users/{$user->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('sponsorships', ['id' => $sponsorship->id]);
    }

    public function test_admin_tidak_dapat_menghapus_panitia_yang_memiliki_pengajuan(): void
    {
        $admin = $this->makeAdmin();
        $sponsor = User::create([
            'name' => 'Sponsor',
            'email' => 'application-sponsor@test.com',
            'password' => 'password',
            'role' => 'sponsor',
            'status_verifikasi' => 'terverifikasi',
        ]);
        $sponsorProfile = SponsorProfile::create([
            'user_id' => $sponsor->id,
            'nama_perusahaan' => 'Sponsor',
        ]);
        $sponsorship = $sponsorProfile->sponsorships()->create([
            'judul' => 'Sponsorship',
            'deskripsi' => 'Deskripsi',
            'benefit' => 'Benefit',
            'persyaratan' => 'Persyaratan',
            'batas_pengajuan' => now()->addDays(10)->toDateString(),
        ]);
        $user = User::create([
            'name' => 'Panitia Dengan Riwayat',
            'email' => 'history-panitia@test.com',
            'password' => 'password',
            'role' => 'panitia',
            'status_verifikasi' => 'terverifikasi',
        ]);
        $profile = PanitiaProfile::create([
            'user_id' => $user->id,
            'nama_organisasi' => 'Panitia Dengan Riwayat',
        ]);
        $application = SponsorshipApplication::create([
            'sponsorship_id' => $sponsorship->id,
            'panitia_id' => $profile->id,
            'nama_event' => 'Seminar',
            'deskripsi_event' => 'Deskripsi acara',
            'tanggal_event' => now()->addMonth()->toDateString(),
            'lokasi_event' => 'Aula',
            'proposal' => 'proposal.pdf',
        ]);

        $this->actingAs($admin)
            ->delete("/admin/users/{$user->id}")
            ->assertRedirect('/admin/users')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $user->id]);
        $this->assertDatabaseHas('sponsorship_applications', ['id' => $application->id]);
    }

    public function test_ringkasan_role_hanya_menghitung_pengguna_yang_terverifikasi(): void
    {
        $admin = $this->makeAdmin();
        $pendingSponsor = User::create([
            'name' => 'Sponsor Pending',
            'email' => 'pending-sponsor@test.com',
            'password' => 'password',
            'role' => 'sponsor',
            'status_verifikasi' => 'pending',
        ]);
        $verifiedSponsor = User::create([
            'name' => 'Sponsor Verified',
            'email' => 'verified-sponsor@test.com',
            'password' => 'password',
            'role' => 'sponsor',
            'status_verifikasi' => 'terverifikasi',
        ]);
        $pendingPanitia = $this->makePendingUser('pending-panitia@test.com');
        $verifiedPanitia = User::create([
            'name' => 'Panitia Verified',
            'email' => 'verified-panitia@test.com',
            'password' => 'password',
            'role' => 'panitia',
            'status_verifikasi' => 'terverifikasi',
        ]);

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSeeInOrder([
                'Total Pengguna',
                '5',
                'Menunggu Verifikasi',
                '2',
                'Terverifikasi',
                '3',
                'Sponsor',
                '1',
                'Panitia',
                '1',
            ]);

        $this->assertSame('pending', $pendingSponsor->fresh()->status_verifikasi);
        $this->assertSame('pending', $pendingPanitia->fresh()->status_verifikasi);
        $this->assertSame('terverifikasi', $verifiedSponsor->fresh()->status_verifikasi);
        $this->assertSame('terverifikasi', $verifiedPanitia->fresh()->status_verifikasi);
    }

    public function test_role_admin_tidak_dapat_dipilih_dari_form_registrasi(): void
    {
        $this->post('/register', [
            'name' => 'Admin Publik',
            'email' => 'public-admin@test.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'admin',
            'alasan_mendaftar' => 'Mendaftar sebagai admin.',
        ])->assertSessionHasErrors('role');

        $this->assertDatabaseMissing('users', ['email' => 'public-admin@test.com']);
    }

    public function test_seeder_membuat_admin_terverifikasi_dengan_konfigurasi(): void
    {
        config([
            'app.admin.name' => 'Seed Admin',
            'app.admin.email' => 'seed-admin@test.com',
            'app.admin.password' => 'secure-password',
        ]);

        $this->seed(AdminUserSeeder::class);

        $admin = User::where('email', 'seed-admin@test.com')->firstOrFail();
        $this->assertSame('admin', $admin->role);
        $this->assertSame('terverifikasi', $admin->status_verifikasi);
        $this->assertTrue(Hash::check('secure-password', $admin->password));
    }
}
