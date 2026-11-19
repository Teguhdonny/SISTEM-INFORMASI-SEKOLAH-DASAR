<?php

namespace Tests\Feature;

use App\Models\ProfilSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilSekolahCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_save_school_history(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.profil.update'), [
            'section' => 'sejarah',
            'sejarah' => 'Sekolah berdiri dan terus berkembang.',
            'riwayat' => [
                ['tahun' => '2025', 'judul' => 'Renovasi', 'isi' => 'Ruang kelas diperbarui.'],
            ],
        ]);

        $response->assertRedirect(route('admin.profil.index', ['tab' => 'sejarah']));
        $this->assertSame('Sekolah berdiri dan terus berkembang.', ProfilSekolah::query()->firstOrFail()->sejarah);
        $this->assertSame([
            ['tahun' => '2025', 'judul' => 'Renovasi', 'isi' => 'Ruang kelas diperbarui.'],
        ], ProfilSekolah::query()->firstOrFail()->riwayat_sekolah);
    }

    public function test_admin_can_open_each_profile_content_section(): void
    {
        $response = $this->actingAs($this->admin())->get(route('admin.profil.index'));

        $response->assertOk()
            ->assertSee('Notifikasi Pengajuan')
            ->assertSee('Sejarah Singkat')
            ->assertSee('Identitas Sekolah')
            ->assertSee('Struktur Organisasi')
            ->assertSee('Fasilitas Sekolah');
    }

    public function test_admin_can_save_school_identity_fields(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.profil.update'), [
            'section' => 'identitas',
            'identitas_sekolah' => [
                ['label' => 'NPSN', 'nilai' => '12345678'],
                ['label' => 'Akreditasi', 'nilai' => 'A'],
            ],
        ]);

        $response->assertRedirect(route('admin.profil.index', ['tab' => 'identitas']));
        $this->assertSame([
            ['label' => 'NPSN', 'nilai' => '12345678'],
            ['label' => 'Akreditasi', 'nilai' => 'A'],
        ], ProfilSekolah::query()->firstOrFail()->identitas_sekolah);
    }

    public function test_admin_can_save_organization_structure(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.profil.update'), [
            'section' => 'struktur',
            'struktur' => [
                ['tingkat' => '1', 'jabatan' => 'Kepala Sekolah', 'nama' => 'Siti Aminah', 'keterangan' => ''],
                ['tingkat' => '2', 'jabatan' => 'Koordinator', 'nama' => 'Budi Santoso', 'keterangan' => 'Kurikulum'],
            ],
        ]);

        $response->assertRedirect(route('admin.profil.index', ['tab' => 'struktur']));
        $this->assertSame([
            ['tingkat' => 1, 'jabatan' => 'Kepala Sekolah', 'nama' => 'Siti Aminah', 'keterangan' => ''],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => 'Budi Santoso', 'keterangan' => 'Kurikulum'],
        ], ProfilSekolah::query()->firstOrFail()->struktur_organisasi);
    }

    public function test_admin_can_save_school_facilities(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.profil.update'), [
            'section' => 'fasilitas',
            'fasilitas' => [
                ['nama' => 'Perpustakaan Digital', 'ket' => 'Ruang baca dengan koleksi digital.', 'ikon' => 'buku'],
            ],
        ]);

        $response->assertRedirect(route('admin.profil.index', ['tab' => 'fasilitas']));
        $this->assertSame([
            ['nama' => 'Perpustakaan Digital', 'ket' => 'Ruang baca dengan koleksi digital.', 'ikon' => 'buku'],
        ], ProfilSekolah::query()->firstOrFail()->fasilitas_sekolah);
    }

    public function test_admin_can_save_school_objectives_before_a_profile_exists(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.profil.update'), [
            'section' => 'tujuan',
            'tujuan_pendek' => 'Tujuan jangka pendek',
            'tujuan_menengah' => 'Tujuan jangka menengah',
            'tujuan_panjang' => 'Tujuan jangka panjang',
        ]);

        $response->assertRedirect(route('admin.profil.index', ['tab' => 'tujuan']));
        $this->assertSame([
            'pendek' => ['Tujuan jangka pendek'],
            'menengah' => ['Tujuan jangka menengah'],
            'panjang' => ['Tujuan jangka panjang'],
        ], ProfilSekolah::query()->firstOrFail()->tujuan);
    }

    public function test_duplicate_identity_labels_are_rejected_without_saving(): void
    {
        $response = $this->actingAs($this->admin())->put(route('admin.profil.update'), [
            'section' => 'identitas',
            'identitas_sekolah' => [
                ['label' => 'NPSN', 'nilai' => '12345678'],
                ['label' => 'NPSN', 'nilai' => '87654321'],
            ],
        ]);

        $response->assertSessionHasErrors('identitas_sekolah.1.label');
        $this->assertDatabaseCount('profil_sekolah', 0);
    }

    public function test_public_profile_displays_saved_content(): void
    {
        ProfilSekolah::query()->create([
            'nama_sekolah' => 'SD Negeri Contoh',
            'sejarah' => 'Sejarah yang ditampilkan kepada publik.',
            'alamat' => 'Jalan Pendidikan',
            'kontak' => '',
            'email_sekolah' => 'sekolah@example.test',
            'riwayat_sekolah' => [
                ['tahun' => '2025', 'judul' => 'Gedung Baru', 'isi' => 'Gedung sekolah selesai direnovasi.'],
            ],
            'identitas_sekolah' => [
                ['label' => 'NPSN', 'nilai' => '12345678'],
            ],
            'struktur_organisasi' => [
                ['tingkat' => 1, 'jabatan' => 'Kepala Sekolah', 'nama' => 'Siti Aminah', 'keterangan' => ''],
            ],
            'fasilitas_sekolah' => [
                ['nama' => 'Perpustakaan Digital', 'ket' => 'Ruang baca digital.', 'ikon' => 'buku'],
            ],
        ]);

        $response = $this->get(route('profil.public.index', ['tab' => 'fasilitas']));

        $response->assertOk()
            ->assertSee('Gedung Baru')
            ->assertSee('12345678')
            ->assertSee('Siti Aminah')
            ->assertSee('Perpustakaan Digital');
    }

    public function test_profile_management_requires_admin_access(): void
    {
        $this->get(route('admin.profil.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_access_profile_management(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'staff']))
            ->get(route('admin.profil.index'))
            ->assertForbidden();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }
}
