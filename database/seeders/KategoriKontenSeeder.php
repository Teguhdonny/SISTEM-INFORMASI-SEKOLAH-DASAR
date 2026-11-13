<?php

namespace Database\Seeders;

use App\Models\Ekstrakurikuler;
use App\Models\KategoriKonten;
use App\Models\ProgramSekolah;
use Illuminate\Database\Seeder;

class KategoriKontenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (ProgramSekolah::query()->whereNotNull('kategori')->distinct()->pluck('kategori') as $namaKategori) {
            KategoriKonten::query()->firstOrCreate([
                'tipe' => KategoriKonten::TYPE_PROGRAM,
                'nama' => trim($namaKategori),
            ]);
        }

        foreach (Ekstrakurikuler::query()->whereNotNull('kategori')->distinct()->pluck('kategori') as $namaKategori) {
            KategoriKonten::query()->firstOrCreate([
                'tipe' => KategoriKonten::TYPE_EKSTRAKURIKULER,
                'nama' => trim($namaKategori),
            ]);
        }
    }
}
