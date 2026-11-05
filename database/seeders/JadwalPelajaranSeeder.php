<?php

namespace Database\Seeders;

use App\Models\JadwalPelajaran;
use Illuminate\Database\Seeder;

class JadwalPelajaranSeeder extends Seeder
{
    /**
     * Isi jadwal awal (sama dengan tampilan lama) untuk Kelas 1 – Kelas 6.
     * Hanya berjalan jika tabel jadwal masih kosong.
     */
    public function run(): void
    {
        if (JadwalPelajaran::query()->exists()) {
            return;
        }

        $jadwalHarian = [
            'Senin' => [['07:00', '07:35', 'Upacara Bendera'], ['07:35', '08:45', 'Pendidikan Pancasila'], ['08:45', '09:55', 'Bahasa Indonesia'], ['10:15', '11:25', 'Matematika']],
            'Selasa' => [['07:00', '07:15', 'Literasi Pagi'], ['07:15', '08:25', 'Matematika / Numerasi'], ['08:25', '09:35', 'IPAS / Tematik'], ['09:55', '11:05', 'Seni dan Budaya']],
            'Rabu' => [['07:00', '07:15', 'Literasi Pagi'], ['07:15', '08:25', 'Bahasa Indonesia'], ['08:25', '09:35', 'Pendidikan Agama & Budi Pekerti'], ['09:55', '11:05', 'PJOK (Olahraga)']],
            'Kamis' => [['07:00', '07:15', 'Literasi Pagi'], ['07:15', '08:25', 'Matematika / Numerasi'], ['08:25', '09:35', 'Bahasa Jawa (Muatan Lokal)'], ['09:55', '11:05', 'Bahasa Inggris']],
            'Jumat' => [['07:00', '07:45', 'Jumat Berkarakter / Jumat Sehat'], ['07:45', '09:00', 'Projek Penguatan Profil Pelajar (P5)'], ['09:15', '10:30', 'Bahasa Indonesia'], ['13:00', '15:00', 'Ekstrakurikuler Pramuka']],
            'Sabtu' => [['07:00', '07:30', 'Gerakan Lingkungan BERSEHAT'], ['07:30', '08:45', 'Pengembangan Diri / CERMAT'], ['09:00', '10:30', 'Ekstrakurikuler Pilihan']],
        ];

        foreach (JadwalPelajaran::DAFTAR_KELAS as $kelas) {
            foreach ($jadwalHarian as $hari => $daftarPelajaran) {
                foreach ($daftarPelajaran as [$jamMulai, $jamSelesai, $mataPelajaran]) {
                    JadwalPelajaran::create([
                        'kelas' => $kelas,
                        'hari' => $hari,
                        'jam_mulai' => $jamMulai,
                        'jam_selesai' => $jamSelesai,
                        'mata_pelajaran' => $mataPelajaran,
                    ]);
                }
            }
        }
    }
}
