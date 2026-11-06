<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use Illuminate\Database\Seeder;

class JadwalPelajaranSeeder extends Seeder
{
    /**
     * Seed a sample weekly schedule using the school's existing teachers.
     */
    public function run(): void
    {
        $guruIds = Guru::query()->pluck('id', 'nip');

        $jadwal = [
            ['kelas' => 'Kelas 1', 'hari' => 'Senin', 'mata_pelajaran' => 'Bahasa Indonesia', 'id_guru' => '198501142014091001'],
            ['kelas' => 'Kelas 1', 'hari' => 'Selasa', 'mata_pelajaran' => 'Matematika', 'id_guru' => '197807132014092001'],
            ['kelas' => 'Kelas 1', 'hari' => 'Rabu', 'mata_pelajaran' => 'Pendidikan Pancasila', 'id_guru' => '198503142014092001'],
            ['kelas' => 'Kelas 1', 'hari' => 'Kamis', 'mata_pelajaran' => 'Seni Budaya', 'id_guru' => '198812242024212014'],
            ['kelas' => 'Kelas 1', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'id_guru' => '199801112025211041'],
            ['kelas' => 'Kelas 2', 'hari' => 'Senin', 'mata_pelajaran' => 'Matematika', 'id_guru' => '197807132014092001'],
            ['kelas' => 'Kelas 2', 'hari' => 'Selasa', 'mata_pelajaran' => 'Bahasa Indonesia', 'id_guru' => '198501142014091001'],
            ['kelas' => 'Kelas 2', 'hari' => 'Rabu', 'mata_pelajaran' => 'IPAS', 'id_guru' => '198303042023212019'],
            ['kelas' => 'Kelas 2', 'hari' => 'Kamis', 'mata_pelajaran' => 'Bahasa Inggris', 'id_guru' => '198508282021212003'],
            ['kelas' => 'Kelas 2', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'id_guru' => '198511302025212065'],
            ['kelas' => 'Kelas 3', 'hari' => 'Senin', 'mata_pelajaran' => 'IPAS', 'id_guru' => '198303042023212019'],
            ['kelas' => 'Kelas 3', 'hari' => 'Selasa', 'mata_pelajaran' => 'Matematika', 'id_guru' => '197807132014092001'],
            ['kelas' => 'Kelas 3', 'hari' => 'Rabu', 'mata_pelajaran' => 'Bahasa Indonesia', 'id_guru' => '198812242024212014'],
            ['kelas' => 'Kelas 3', 'hari' => 'Kamis', 'mata_pelajaran' => 'Pendidikan Pancasila', 'id_guru' => '198503142014092001'],
            ['kelas' => 'Kelas 3', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'id_guru' => '199801112025211041'],
            ['kelas' => 'Kelas 4', 'hari' => 'Senin', 'mata_pelajaran' => 'Bahasa Indonesia', 'id_guru' => '198812242024212014'],
            ['kelas' => 'Kelas 4', 'hari' => 'Selasa', 'mata_pelajaran' => 'Matematika', 'id_guru' => '197807132014092001'],
            ['kelas' => 'Kelas 4', 'hari' => 'Rabu', 'mata_pelajaran' => 'IPAS', 'id_guru' => '198303042023212019'],
            ['kelas' => 'Kelas 4', 'hari' => 'Kamis', 'mata_pelajaran' => 'Bahasa Inggris', 'id_guru' => '198508282021212003'],
            ['kelas' => 'Kelas 4', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'id_guru' => '198511302025212065'],
            ['kelas' => 'Kelas 5', 'hari' => 'Senin', 'mata_pelajaran' => 'Pendidikan Pancasila', 'id_guru' => '198503142014092001'],
            ['kelas' => 'Kelas 5', 'hari' => 'Selasa', 'mata_pelajaran' => 'Bahasa Indonesia', 'id_guru' => '198501142014091001'],
            ['kelas' => 'Kelas 5', 'hari' => 'Rabu', 'mata_pelajaran' => 'Matematika', 'id_guru' => '197807132014092001'],
            ['kelas' => 'Kelas 5', 'hari' => 'Kamis', 'mata_pelajaran' => 'IPAS', 'id_guru' => '198303042023212019'],
            ['kelas' => 'Kelas 5', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'id_guru' => '199801112025211041'],
            ['kelas' => 'Kelas 6', 'hari' => 'Senin', 'mata_pelajaran' => 'Bahasa Indonesia', 'id_guru' => '198501142014091001'],
            ['kelas' => 'Kelas 6', 'hari' => 'Selasa', 'mata_pelajaran' => 'Matematika', 'id_guru' => '197807132014092001'],
            ['kelas' => 'Kelas 6', 'hari' => 'Rabu', 'mata_pelajaran' => 'IPAS', 'id_guru' => '198303042023212019'],
            ['kelas' => 'Kelas 6', 'hari' => 'Kamis', 'mata_pelajaran' => 'Pendidikan Pancasila', 'id_guru' => '198503142014092001'],
            ['kelas' => 'Kelas 6', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'id_guru' => '198511302025212065'],
        ];

        foreach ($jadwal as $index => $dataJadwal) {
            $jamMulai = $index % 2 === 0 ? '07:00' : '08:00';

            JadwalPelajaran::updateOrCreate(
                [
                    'kelas' => $dataJadwal['kelas'],
                    'hari' => $dataJadwal['hari'],
                    'mata_pelajaran' => $dataJadwal['mata_pelajaran'],
                ],
                [
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $index % 2 === 0 ? '08:00' : '09:00',
                    'id_guru' => $guruIds[$dataJadwal['id_guru']],
                ],
            );
        }
    }
}
