<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlang;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\ProfilSekolah;
use App\Models\ProgramSekolah;
use App\Models\SiswaPpdb;
use App\Models\VisiMisi;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const TOTAL_BERITA_BERANDA = 4;

    public function index(): View
    {
        $totalGuru = Guru::query()->count();
        $totalSiswa = SiswaPpdb::query()->count();
        $totalBerita = self::TOTAL_BERITA_BERANDA;
        $totalDaftarUlang = DaftarUlang::query()->count();
        $tahunSekarang = CarbonImmutable::now('Asia/Jakarta')->year;
        $awalRentang = CarbonImmutable::create($tahunSekarang - 4, 1, 1, 0, 0, 0, 'Asia/Jakarta');
        $akhirRentang = CarbonImmutable::create($tahunSekarang, 12, 31, 23, 59, 59, 'Asia/Jakarta');
        $ppdbPerTahun = SiswaPpdb::query()
            ->whereBetween('created_at', [$awalRentang, $akhirRentang])
            ->get(['created_at'])
            ->groupBy(fn (SiswaPpdb $pendaftar): int => $pendaftar->created_at->timezone('Asia/Jakarta')->year);
        $statistikPpdb = collect(range($tahunSekarang - 4, $tahunSekarang))->map(function (int $tahun) use ($ppdbPerTahun): array {

            return [
                'tahun' => $tahun,
                'total' => $ppdbPerTahun->get($tahun, collect())->count(),
            ];
        });
        $aktivitasTerbaru = $this->aktivitasTerbaru();

        return view('pages.admin.dashboard.index', compact(
            'totalGuru',
            'totalSiswa',
            'totalBerita',
            'totalDaftarUlang',
            'statistikPpdb',
            'aktivitasTerbaru',
        ));
    }

    /** @return Collection<int, array{judul: string, keterangan: string, waktu: CarbonImmutable, ikon: string, warna: string}> */
    private function aktivitasTerbaru(): Collection
    {
        $aktivitas = collect();

        foreach (Guru::query()->latest('updated_at')->limit(5)->get() as $guru) {
            $aktivitas->push($this->buatAktivitas(
                'Guru', $guru->nama, $guru->created_at, $guru->updated_at, 'fa-user-tie', 'blue'
            ));
        }

        foreach (SiswaPpdb::query()->latest('updated_at')->limit(5)->get() as $siswa) {
            $aktivitas->push($this->buatAktivitas(
                'PPDB', $siswa->nama_siswa, $siswa->created_at, $siswa->updated_at, 'fa-user-plus', 'blue'
            ));
        }

        foreach (DaftarUlang::query()->with('siswaPpdb')->latest('updated_at')->limit(5)->get() as $daftarUlang) {
            $aktivitas->push($this->buatAktivitas(
                'Daftar ulang', $daftarUlang->siswaPpdb?->nama_siswa ?? 'Data siswa', $daftarUlang->created_at, $daftarUlang->updated_at, 'fa-user-graduate', 'green'
            ));
        }

        foreach (Ekstrakurikuler::query()->latest('updated_at')->limit(5)->get() as $ekstrakurikuler) {
            $aktivitas->push($this->buatAktivitas(
                'Ekstrakurikuler', $ekstrakurikuler->nama_ekskul, $ekstrakurikuler->created_at, $ekstrakurikuler->updated_at, 'fa-person-running', 'purple'
            ));
        }

        foreach (ProgramSekolah::query()->latest('updated_at')->limit(5)->get() as $program) {
            $aktivitas->push($this->buatAktivitas(
                'Program sekolah', $program->nama_program, $program->created_at, $program->updated_at, 'fa-clipboard-list', 'amber'
            ));
        }

        foreach (ProfilSekolah::query()->latest('updated_at')->limit(1)->get() as $profil) {
            $aktivitas->push($this->buatAktivitas(
                'Profil sekolah', $profil->nama_sekolah, $profil->created_at, $profil->updated_at, 'fa-building', 'blue'
            ));
        }

        foreach (VisiMisi::query()->latest('updated_at')->limit(1)->get() as $visiMisi) {
            $aktivitas->push(array_replace(
                $this->buatAktivitas('Visi & Misi', $visiMisi->kategori, $visiMisi->created_at, $visiMisi->updated_at, 'fa-bullseye', 'amber'),
                ['judul' => 'Visi & Misi diperbarui', 'ikon' => 'fa-pen'],
            ));
        }

        return $aktivitas->sortByDesc('waktu')->take(5)->values();
    }

    /** @return array{judul: string, keterangan: string, waktu: CarbonImmutable, ikon: string, warna: string} */
    private function buatAktivitas(string $jenis, string $nama, \DateTimeInterface $dibuat, \DateTimeInterface $diperbarui, string $ikon, string $warna): array
    {
        $waktuDibuat = CarbonImmutable::instance($dibuat);
        $waktuDiperbarui = CarbonImmutable::instance($diperbarui);
        $ditambahkan = $waktuDibuat->equalTo($waktuDiperbarui);

        return [
            'judul' => $ditambahkan ? $jenis.' ditambahkan' : $jenis.' diperbarui',
            'keterangan' => $nama,
            'waktu' => $waktuDiperbarui->timezone('Asia/Jakarta'),
            'ikon' => $ditambahkan ? 'fa-plus' : $ikon,
            'warna' => $warna,
        ];
    }
}
