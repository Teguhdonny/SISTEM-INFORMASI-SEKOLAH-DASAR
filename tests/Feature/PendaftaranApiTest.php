<?php

namespace Tests\Feature;

use App\Models\SiswaPpdb;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PendaftaranApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_pendaftaran_records_as_json_with_daftar_ulang_data(): void
    {
        $siswa = SiswaPpdb::query()->create([
            'no_pendaftaran' => 'PPDB-2026-001',
            'nisn' => '1234567890',
            'nama_siswa' => 'Siswa Contoh',
            'jenjang' => 'SD',
            'asal_sekolah' => 'TK Contoh',
            'status_diterima' => true,
        ]);

        $siswa->daftarUlang()->create([
            'nama_wali' => 'Wali Contoh',
            'no_whatsapp' => '081234567890',
            'status' => 'Menunggu Verifikasi',
        ]);

        $response = $this->getJson('/api/pendaftaran');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.0.nama_siswa', 'Siswa Contoh')
            ->assertJsonPath('data.0.daftar_ulang.status', 'Menunggu Verifikasi');
    }
}
