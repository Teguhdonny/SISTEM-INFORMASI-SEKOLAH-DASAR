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
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->string('q')->trim()->toString();
        $term = '%'.$query.'%';

        $menu = collect([
            ['judul' => 'Dashboard', 'deskripsi' => 'Ringkasan dan statistik sekolah', 'url' => route('admin.dashboard'), 'kata_kunci' => 'beranda utama statistik aktivitas'],
            ['judul' => 'Profil Sekolah', 'deskripsi' => 'Identitas, sejarah, visi, dan misi sekolah', 'url' => route('admin.profil.index'), 'kata_kunci' => 'identitas sejarah visi misi'],
            ['judul' => 'Guru & Staf', 'deskripsi' => 'Kelola data guru dan staf', 'url' => route('admin.guru.index'), 'kata_kunci' => 'pendidik pegawai guru'],
            ['judul' => 'Program Sekolah', 'deskripsi' => 'Kelola program sekolah', 'url' => route('admin.program.index'), 'kata_kunci' => 'program kegiatan'],
            ['judul' => 'Ekstrakurikuler', 'deskripsi' => 'Kelola kegiatan ekstrakurikuler', 'url' => route('admin.ekstrakurikuler.index'), 'kata_kunci' => 'ekskul kegiatan siswa'],
            ['judul' => 'PPDB & Daftar Ulang', 'deskripsi' => 'Lihat data pendaftaran dan daftar ulang', 'url' => route('admin.pendaftaran.index'), 'kata_kunci' => 'siswa ppdb pendaftar registrasi daftar ulang'],
            ['judul' => 'Galeri Foto', 'deskripsi' => 'Lihat galeri foto sekolah', 'url' => route('admin.galeri.index'), 'kata_kunci' => 'foto dokumentasi gambar'],
        ])->filter(fn (array $item): bool => $query !== '' && str_contains(mb_strtolower($item['judul'].' '.$item['deskripsi'].' '.$item['kata_kunci']), mb_strtolower($query)))
            ->values();

        $informasi = collect();

        if ($query !== '') {
            ProfilSekolah::query()
                ->where(fn ($builder) => $builder->where('nama_sekolah', 'like', $term)->orWhere('sejarah', 'like', $term)->orWhere('alamat', 'like', $term)->orWhere('kontak', 'like', $term)->orWhere('email_sekolah', 'like', $term))
                ->limit(1)->get()
                ->each(fn (ProfilSekolah $profil) => $informasi->push($this->hasil('Profil Sekolah', $profil->nama_sekolah ?: 'Profil sekolah', $profil->alamat, route('admin.profil.index'))));

            VisiMisi::query()
                ->where('deskripsi', 'like', $term)->orWhere('kategori', 'like', $term)
                ->orderBy('kategori')->limit(8)->get()
                ->each(fn (VisiMisi $item) => $informasi->push($this->hasil('Visi & Misi', $item->kategori, $item->deskripsi, route('admin.profil.index'))));

            Guru::query()
                ->where(fn ($builder) => $builder->where('nama', 'like', $term)->orWhere('nip', 'like', $term)->orWhere('bagian', 'like', $term)->orWhere('email', 'like', $term))
                ->orderBy('nama')->limit(8)->get()
                ->each(fn (Guru $guru) => $informasi->push($this->hasil('Guru & Staf', $guru->nama, trim(($guru->bagian ?? '').' '.($guru->nip ?? '')), route('admin.guru.index', ['search' => $query]))));

            ProgramSekolah::query()
                ->where(fn ($builder) => $builder->where('nama_program', 'like', $term)->orWhere('deskripsi', 'like', $term))
                ->orderBy('nama_program')->limit(8)->get()
                ->each(fn (ProgramSekolah $program) => $informasi->push($this->hasil('Program Sekolah', $program->nama_program, $program->deskripsi, route('admin.program.index'))));

            Ekstrakurikuler::query()
                ->where(fn ($builder) => $builder->where('nama_ekskul', 'like', $term)->orWhere('jadwal', 'like', $term)->orWhere('deskripsi', 'like', $term))
                ->orderBy('nama_ekskul')->limit(8)->get()
                ->each(fn (Ekstrakurikuler $item) => $informasi->push($this->hasil('Ekstrakurikuler', $item->nama_ekskul, $item->jadwal, route('admin.ekstrakurikuler.index'))));

            SiswaPpdb::query()
                ->where(fn ($builder) => $builder->where('nama_siswa', 'like', $term)->orWhere('no_pendaftaran', 'like', $term)->orWhere('nisn', 'like', $term)->orWhere('asal_sekolah', 'like', $term))
                ->orderBy('nama_siswa')->limit(8)->get()
                ->each(fn (SiswaPpdb $siswa) => $informasi->push($this->hasil('PPDB', $siswa->nama_siswa, 'No. pendaftaran: '.$siswa->no_pendaftaran, route('admin.pendaftaran.index'))));

            DaftarUlang::query()->with('siswaPpdb')
                ->where('status', 'like', $term)
                ->orWhereHas('siswaPpdb', fn ($builder) => $builder->where('nama_siswa', 'like', $term))
                ->latest()->limit(8)->get()
                ->each(fn (DaftarUlang $item) => $informasi->push($this->hasil('Daftar Ulang', $item->siswaPpdb?->nama_siswa ?? 'Data siswa', 'Status: '.$item->status, route('admin.pendaftaran.index'))));
        }

        return view('pages.admin.search.index', compact('query', 'menu', 'informasi'));
    }

    /** @return array{jenis: string, judul: string, deskripsi: string, url: string} */
    private function hasil(string $jenis, string $judul, string $deskripsi, string $url): array
    {
        return [
            'jenis' => $jenis,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'url' => $url,
        ];
    }
}
