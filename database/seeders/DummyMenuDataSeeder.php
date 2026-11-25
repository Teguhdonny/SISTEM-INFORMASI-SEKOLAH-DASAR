<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\ProgramSekolah;
use App\Models\SiswaPpdb;
use Illuminate\Database\Seeder;

class DummyMenuDataSeeder extends Seeder
{
    /**
     * Add repeatable sample records for the admin sidebar menus.
     */
    public function run(): void
    {
        $guruKelas = Guru::updateOrCreate(
            ['nip' => '999000000000001'],
            [
                'nama' => 'Budi Santoso (Demo)',
                'bagian' => 'Guru Kelas',
                'lulusan' => 'S1 Pendidikan Guru Sekolah Dasar',
            ],
        );

        $guruOlahraga = Guru::updateOrCreate(
            ['nip' => '999000000000002'],
            [
                'nama' => 'Rina Puspitasari (Demo)',
                'bagian' => 'Guru Pendidikan Jasmani',
                'lulusan' => 'S1 Pendidikan Jasmani, Kesehatan, dan Rekreasi',
            ],
        );

        Guru::updateOrCreate(
            ['nip' => '999000000000003'],
            [
                'nama' => 'Siti Aminah (Demo)',
                'bagian' => 'Tenaga Administrasi',
                'lulusan' => 'D3 Administrasi Perkantoran',
            ],
        );

        $jadwal = [
            ['kelas' => 'Kelas 1', 'hari' => 'Senin', 'mata_pelajaran' => 'Bahasa Indonesia', 'jam_mulai' => '07:00', 'jam_selesai' => '08:10', 'id_guru' => $guruKelas->id],
            ['kelas' => 'Kelas 1', 'hari' => 'Selasa', 'mata_pelajaran' => 'Matematika', 'jam_mulai' => '07:00', 'jam_selesai' => '08:10', 'id_guru' => $guruKelas->id],
            ['kelas' => 'Kelas 1', 'hari' => 'Rabu', 'mata_pelajaran' => 'Pendidikan Pancasila', 'jam_mulai' => '07:00', 'jam_selesai' => '08:10', 'id_guru' => $guruKelas->id],
            ['kelas' => 'Kelas 1', 'hari' => 'Kamis', 'mata_pelajaran' => 'Seni Budaya', 'jam_mulai' => '08:10', 'jam_selesai' => '09:20', 'id_guru' => $guruKelas->id],
            ['kelas' => 'Kelas 1', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'jam_mulai' => '07:00', 'jam_selesai' => '08:10', 'id_guru' => $guruOlahraga->id],
            ['kelas' => 'Kelas 2', 'hari' => 'Senin', 'mata_pelajaran' => 'Matematika', 'jam_mulai' => '08:10', 'jam_selesai' => '09:20', 'id_guru' => $guruKelas->id],
            ['kelas' => 'Kelas 2', 'hari' => 'Rabu', 'mata_pelajaran' => 'Bahasa Indonesia', 'jam_mulai' => '08:10', 'jam_selesai' => '09:20', 'id_guru' => $guruKelas->id],
            ['kelas' => 'Kelas 2', 'hari' => 'Jumat', 'mata_pelajaran' => 'Pendidikan Jasmani', 'jam_mulai' => '08:10', 'jam_selesai' => '09:20', 'id_guru' => $guruOlahraga->id],
        ];

        foreach ($jadwal as $dataJadwal) {
            JadwalPelajaran::updateOrCreate(
                [
                    'kelas' => $dataJadwal['kelas'],
                    'hari' => $dataJadwal['hari'],
                    'mata_pelajaran' => $dataJadwal['mata_pelajaran'],
                    'id_guru' => $dataJadwal['id_guru'],
                ],
                [
                    'jam_mulai' => $dataJadwal['jam_mulai'],
                    'jam_selesai' => $dataJadwal['jam_selesai'],
                ],
            );
        }

        $program = [
            [
                'nama_program' => '[Demo] Layanan Pendidikan Inklusif',
                'kategori' => 'Layanan Siswa',
                'jadwal' => 'Senin–Jumat',
                'deskripsi' => 'Pendampingan belajar yang menyesuaikan kebutuhan siswa berkebutuhan khusus, disertai koordinasi berkala dengan orang tua.',
                'peserta' => 4,
                'ikon' => 'fa-universal-access',
                'warna' => 'blue',
                'status' => 'Aktif',
            ],
            [
                'nama_program' => '[Demo] Gerakan Literasi Pagi',
                'kategori' => 'Akademik',
                'jadwal' => 'Setiap hari, 15 menit sebelum pelajaran',
                'deskripsi' => 'Pembiasaan membaca dan menceritakan kembali bacaan untuk meningkatkan kemampuan literasi siswa.',
                'peserta' => 120,
                'ikon' => 'fa-book-open',
                'warna' => 'amber',
                'status' => 'Aktif',
            ],
            [
                'nama_program' => '[Demo] Jumat Sehat dan Bersih',
                'kategori' => 'Pembiasaan',
                'jadwal' => 'Setiap Jumat pagi',
                'deskripsi' => 'Kegiatan kebugaran ringan dan kerja bakti untuk membiasakan pola hidup sehat serta menjaga lingkungan sekolah.',
                'peserta' => 120,
                'ikon' => 'fa-heart',
                'warna' => 'green',
                'status' => 'Aktif',
            ],
        ];

        foreach ($program as $dataProgram) {
            ProgramSekolah::updateOrCreate(
                ['nama_program' => $dataProgram['nama_program']],
                $dataProgram,
            );
        }

        $ekstrakurikuler = [
            [
                'nama_ekskul' => '[Demo] Pramuka',
                'kategori' => 'Kepemimpinan',
                'jadwal' => 'Jumat, 13.00–14.30',
                'deskripsi' => 'Latihan kepramukaan untuk membangun kedisiplinan, kemandirian, dan kerja sama.',
                'pembina' => 'Budi Santoso (Demo)',
                'peserta' => 32,
                'ikon' => 'fa-compass',
                'warna' => 'green',
                'status' => 'Aktif',
            ],
            [
                'nama_ekskul' => '[Demo] Futsal',
                'kategori' => 'Olahraga',
                'jadwal' => 'Selasa, 14.00–15.30',
                'deskripsi' => 'Latihan dasar teknik futsal dan permainan tim untuk siswa kelas 3 sampai 6.',
                'pembina' => 'Rina Puspitasari (Demo)',
                'peserta' => 24,
                'ikon' => 'fa-futbol',
                'warna' => 'blue',
                'status' => 'Aktif',
            ],
            [
                'nama_ekskul' => '[Demo] Seni Tari',
                'kategori' => 'Seni',
                'jadwal' => 'Kamis, 14.00–15.00',
                'deskripsi' => 'Pengenalan gerak tari daerah dan latihan penampilan untuk acara sekolah.',
                'pembina' => 'Siti Aminah (Demo)',
                'peserta' => 18,
                'ikon' => 'fa-music',
                'warna' => 'rose',
                'status' => 'Aktif',
            ],
        ];

        foreach ($ekstrakurikuler as $dataEkstrakurikuler) {
            Ekstrakurikuler::updateOrCreate(
                ['nama_ekskul' => $dataEkstrakurikuler['nama_ekskul']],
                $dataEkstrakurikuler,
            );
        }

        $berita = [
            [
                'judul' => '[Demo] Siswa Mengikuti Praktik Sains Sederhana',
                'kategori' => 'Kegiatan',
                'tanggal_kegiatan' => now()->subDays(2)->toDateString(),
                'foto' => null,
                'status' => 'Aktif',
                'isi' => 'Siswa melakukan percobaan sains sederhana secara berkelompok. Kegiatan ini melatih rasa ingin tahu, kemampuan mengamati, dan kerja sama.',
            ],
            [
                'judul' => '[Demo] Pembiasaan Membaca Bersama di Pagi Hari',
                'kategori' => 'Pendidikan',
                'tanggal_kegiatan' => now()->subDays(5)->toDateString(),
                'foto' => null,
                'status' => 'Aktif',
                'isi' => 'Kegiatan membaca bersama dilaksanakan sebelum pembelajaran dimulai. Siswa memilih bacaan sesuai minat dan berbagi cerita tentang isi buku.',
            ],
            [
                'judul' => '[Demo] Informasi Penerimaan Peserta Didik Baru',
                'kategori' => 'Pengumuman',
                'tanggal_kegiatan' => now()->subDays(7)->toDateString(),
                'foto' => null,
                'status' => 'Draft',
                'isi' => 'Informasi persyaratan dan jadwal penerimaan peserta didik baru akan diumumkan melalui kanal resmi sekolah setelah ditetapkan.',
            ],
        ];

        foreach ($berita as $dataBerita) {
            Berita::updateOrCreate(
                ['judul' => $dataBerita['judul']],
                $dataBerita,
            );
        }

        $siswa = [
            ['no_pendaftaran' => 'PPDB-DEMO-001', 'nik' => str_repeat('2', 16), 'nisn' => str_repeat('2', 10), 'nama_siswa' => 'Ananda Contoh Dua (Demo)', 'jenis_kelamin' => 'Perempuan', 'jenjang' => 'SD', 'tahun_ajaran' => '2026/2027', 'asal_sekolah' => 'TK Contoh Bangsa', 'status_diterima' => true, 'dicatat_admin' => true],
            ['no_pendaftaran' => 'PPDB-DEMO-002', 'nik' => str_repeat('3', 16), 'nisn' => str_repeat('3', 10), 'nama_siswa' => 'Ananda Contoh Tiga (Demo)', 'jenis_kelamin' => 'Laki-laki', 'jenjang' => 'SD', 'tahun_ajaran' => '2026/2027', 'asal_sekolah' => 'TK Tunas Contoh', 'status_diterima' => true, 'dicatat_admin' => true],
            ['no_pendaftaran' => 'PPDB-DEMO-003', 'nik' => str_repeat('4', 16), 'nisn' => str_repeat('4', 10), 'nama_siswa' => 'Ananda Contoh Empat (Demo)', 'jenis_kelamin' => 'Perempuan', 'jenjang' => 'SD', 'tahun_ajaran' => '2026/2027', 'asal_sekolah' => 'TK Melati Contoh', 'status_diterima' => true, 'dicatat_admin' => true],
        ];

        foreach ($siswa as $dataSiswa) {
            SiswaPpdb::updateOrCreate(
                ['no_pendaftaran' => $dataSiswa['no_pendaftaran']],
                $dataSiswa,
            );
        }

        $this->call(KategoriKontenSeeder::class);
    }
}
