<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $guru = [
            ['nip' => '198501142014091001', 'nama' => 'Ahmad Zakiyyunnuhaa F', 'bagian' => 'Guru (PNS)', 'email' => 'haik35grip@gmail.com'],
            ['nip' => '197807132014092001', 'nama' => 'Anisa Kodrati', 'bagian' => 'Guru (PNS)', 'email' => 'nisarafi212@gmail.com'],
            ['nip' => '198503142014092001', 'nama' => 'Dina Mardiana', 'bagian' => 'Guru (PNS) - Bendahara BOS/BOP', 'email' => 'superglad47@gmail.com'],
            ['nip' => '198812242024212014', 'nama' => 'Erna Susilowati', 'bagian' => 'Guru (PPPK)', 'email' => 'plibiena@yahoo.co.id'],
            ['nip' => '198303042023212019', 'nama' => 'Hawin Hidayati', 'bagian' => 'Guru (PPPK)', 'email' => 'hawinhidayati@gmail.com'],
            ['nip' => '199801112025211041', 'nama' => 'Ramadhan Saputra Anggalih Utomo', 'bagian' => 'Guru (PPPK Paruh Waktu)', 'email' => 'ramadhansaputra1198@gmail.com'],
            ['nip' => '198508282021212003', 'nama' => 'Wulan Puji Astuti', 'bagian' => 'Guru (PPPK)', 'email' => 'wulandeva15@gmail.com'],
            ['nip' => '198511302025212065', 'nama' => 'Yanu Nofanti', 'bagian' => 'Guru (PPPK Paruh Waktu)', 'email' => 'azzahranajwa959@gmail.com'],
        ];

        foreach ($guru as $dataGuru) {
            Guru::updateOrCreate(
                ['nip' => $dataGuru['nip']],
                [
                    'nama' => $dataGuru['nama'],
                    'bagian' => $dataGuru['bagian'],
                    'email' => $dataGuru['email'],
                    'lulusan' => null,
                ],
            );
        }
    }
}
