<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use App\Models\VisiMisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfilSekolahController extends Controller
{
    public function index(): View
    {
        $profilSekolah = ProfilSekolah::query()->first() ?? new ProfilSekolah([
            'nama_sekolah' => 'SD Negeri Pojok 2 Kota Kediri',
            'sejarah' => 'SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri.',
            'alamat' => 'Jalan Mastrip Nomor 59, Kota Kediri, 64115, Jawa Timur',
            'kontak' => '',
            'email_sekolah' => 'pojok2mojoroto@gmail.com',
        ]);
        $visi = VisiMisi::query()->where('kategori', 'Visi')->value('deskripsi')
            ?? 'MEWUJUDKAN GENERASI MUDA YANG BERAKHLAK MULIA, MANDIRI, KREATIF BERWAWASAN GLOBAL DAN PEDULI LINGKUNGAN';
        $misi = VisiMisi::query()->where('kategori', 'Misi')->orderBy('id')->pluck('deskripsi')->all();

        if ($misi === []) {
            $misi = [
                'Melaksanakan pembelajaran dan bimbingan secara efektif dan efisien, sehingga setiap siswa dapat berkembang secara optimal.',
                'Menumbuhkan semangat keunggulan secara kompetitif dan interaktif kepada seluruh warga sekolah.',
                'Mengembangkan potensi berdasarkan minat dan bakat anak.',
                'Menanamkan perilaku hidup sesuai norma-norma dan ajaran agama.',
                'Menumbuhkan rasa cinta lingkungan sebagai sumber belajar.',
            ];
        }

        $tujuan = $profilSekolah->tujuan ?: $this->defaultObjectives();
        $riwayat = $profilSekolah->riwayat_sekolah ?: $this->defaultHistory();
        $identitasSekolah = $profilSekolah->identitas_sekolah ?: $this->defaultIdentity();
        $strukturOrganisasi = $profilSekolah->struktur_organisasi ?: $this->defaultOrganization();
        $fasilitasSekolah = $profilSekolah->fasilitas_sekolah ?: $this->defaultFacilities();

        return view('pages.admin.profil.index', compact(
            'profilSekolah',
            'visi',
            'misi',
            'tujuan',
            'riwayat',
            'identitasSekolah',
            'strukturOrganisasi',
            'fasilitasSekolah',
        ));
    }

    public function update(Request $request): RedirectResponse
    {
        $section = $request->validate([
            'section' => ['required', Rule::in(['profil', 'sejarah', 'identitas', 'struktur', 'fasilitas', 'visimisi', 'tujuan'])],
        ])['section'];

        if ($section === 'profil') {
            $validated = $request->validate([
                'nama_sekolah' => ['required', 'string', 'max:150'],
                'email_sekolah' => ['required', 'email', 'max:100'],
                'alamat' => ['required', 'string', 'max:1000'],
            ]);

            DB::transaction(function () use ($validated): void {
                $profilSekolah = ProfilSekolah::query()->firstOrNew();
                $profilSekolah->fill([
                    ...$validated,
                    'kontak' => $profilSekolah->kontak ?? '',
                    'sejarah' => $profilSekolah->sejarah ?: 'SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri.',
                ])->save();
            });

            return redirect()->route('admin.profil.index', ['tab' => 'profil'])->with('success', 'Identitas sekolah berhasil diperbarui.');
        }

        if ($section === 'sejarah') {
            $validated = $request->validate([
                'sejarah' => ['required', 'string', 'max:10000'],
                'riwayat' => ['required', 'array', 'min:1', 'max:30'],
                'riwayat.*' => ['required', 'array:tahun,judul,isi'],
                'riwayat.*.tahun' => ['required', 'string', 'max:40'],
                'riwayat.*.judul' => ['required', 'string', 'max:150'],
                'riwayat.*.isi' => ['required', 'string', 'max:2000'],
            ]);

            $profilSekolah = $this->profileForUpdate();
            $profilSekolah->fill([
                'sejarah' => $validated['sejarah'],
                'riwayat_sekolah' => array_values($validated['riwayat']),
            ])->save();

            return redirect()->route('admin.profil.index', ['tab' => 'sejarah'])->with('success', 'Sejarah sekolah berhasil diperbarui.');
        }

        if ($section === 'identitas') {
            $validated = $request->validate([
                'identitas_sekolah' => ['required', 'array', 'min:1', 'max:40'],
                'identitas_sekolah.*' => ['required', 'array:label,nilai'],
                'identitas_sekolah.*.label' => ['required', 'string', 'max:100', 'distinct', Rule::notIn(['Nama Sekolah', 'Alamat', 'Email Resmi'])],
                'identitas_sekolah.*.nilai' => ['required', 'string', 'max:500'],
            ]);

            $profilSekolah = $this->profileForUpdate();
            $profilSekolah->identitas_sekolah = array_values($validated['identitas_sekolah']);
            $profilSekolah->save();

            return redirect()->route('admin.profil.index', ['tab' => 'identitas'])->with('success', 'Identitas sekolah berhasil diperbarui.');
        }

        if ($section === 'struktur') {
            $validated = $request->validate([
                'struktur' => ['required', 'array', 'min:1', 'max:30'],
                'struktur.*' => ['required', 'array:tingkat,jabatan,nama,keterangan'],
                'struktur.*.tingkat' => ['required', Rule::in(['1', '2', '3'])],
                'struktur.*.jabatan' => ['required', 'string', 'max:150'],
                'struktur.*.nama' => ['nullable', 'string', 'max:150'],
                'struktur.*.keterangan' => ['nullable', 'string', 'max:500'],
            ]);

            $struktur = collect($validated['struktur'])
                ->map(fn (array $item): array => [
                    'tingkat' => (int) $item['tingkat'],
                    'jabatan' => trim($item['jabatan']),
                    'nama' => trim($item['nama'] ?? ''),
                    'keterangan' => trim($item['keterangan'] ?? ''),
                ])
                ->values()
                ->all();

            $profilSekolah = $this->profileForUpdate();
            $profilSekolah->struktur_organisasi = $struktur;
            $profilSekolah->save();

            return redirect()->route('admin.profil.index', ['tab' => 'struktur'])->with('success', 'Struktur organisasi sekolah berhasil diperbarui.');
        }

        if ($section === 'fasilitas') {
            $validated = $request->validate([
                'fasilitas' => ['required', 'array', 'min:1', 'max:30'],
                'fasilitas.*' => ['required', 'array:nama,ket,ikon'],
                'fasilitas.*.nama' => ['required', 'string', 'max:100'],
                'fasilitas.*.ket' => ['required', 'string', 'max:500'],
                'fasilitas.*.ikon' => ['required', Rule::in(['kelas', 'buku', 'laboratorium', 'uks', 'lapangan', 'toilet'])],
            ]);

            $profilSekolah = $this->profileForUpdate();
            $profilSekolah->fasilitas_sekolah = array_values($validated['fasilitas']);
            $profilSekolah->save();

            return redirect()->route('admin.profil.index', ['tab' => 'fasilitas'])->with('success', 'Fasilitas sekolah berhasil diperbarui.');
        }

        if ($section === 'visimisi') {
            $validated = $request->validate([
                'visi' => ['required', 'string', 'max:5000'],
                'misi' => ['required', 'string', 'max:10000'],
            ]);
            $misi = $this->lines($validated['misi']);

            if ($misi === []) {
                return back()->withInput()->withErrors(['misi' => 'Isi minimal satu misi sekolah.']);
            }

            DB::transaction(function () use ($validated, $misi): void {
                VisiMisi::query()->whereIn('kategori', ['Visi', 'Misi'])->delete();
                VisiMisi::query()->create(['kategori' => 'Visi', 'deskripsi' => $validated['visi']]);

                foreach ($misi as $deskripsi) {
                    VisiMisi::query()->create(['kategori' => 'Misi', 'deskripsi' => $deskripsi]);
                }
            });

            return redirect()->route('admin.profil.index', ['tab' => 'visimisi'])->with('success', 'Visi dan misi sekolah berhasil diperbarui.');
        }

        $validated = $request->validate([
            'tujuan_pendek' => ['required', 'string', 'max:10000'],
            'tujuan_menengah' => ['required', 'string', 'max:10000'],
            'tujuan_panjang' => ['required', 'string', 'max:10000'],
        ]);
        $tujuan = [
            'pendek' => $this->lines($validated['tujuan_pendek']),
            'menengah' => $this->lines($validated['tujuan_menengah']),
            'panjang' => $this->lines($validated['tujuan_panjang']),
        ];

        foreach ($tujuan as $term => $objectives) {
            if ($objectives === []) {
                return back()->withInput()->withErrors(["tujuan_{$term}" => 'Isi minimal satu tujuan untuk setiap jangka waktu.']);
            }
        }

        DB::transaction(function () use ($tujuan): void {
            $profilSekolah = $this->profileForUpdate();
            $profilSekolah->tujuan = $tujuan;
            $profilSekolah->save();
        });

        return redirect()->route('admin.profil.index', ['tab' => 'tujuan'])->with('success', 'Tujuan sekolah berhasil diperbarui.');
    }

    private function profileForUpdate(): ProfilSekolah
    {
        return ProfilSekolah::query()->first() ?? new ProfilSekolah([
            'nama_sekolah' => 'SD Negeri Pojok 2 Kota Kediri',
            'sejarah' => 'SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri.',
            'alamat' => 'Jalan Mastrip Nomor 59, Kota Kediri, 64115, Jawa Timur',
            'kontak' => '',
            'email_sekolah' => 'pojok2mojoroto@gmail.com',
        ]);
    }

    /** @return array<int, array{tahun: string, judul: string, isi: string}> */
    private function defaultHistory(): array
    {
        return [
            ['tahun' => '1982', 'judul' => 'Pendirian sekolah', 'isi' => 'SDN Pojok 2 Kota Kediri resmi berdiri pada tahun 1982 sebagai sekolah dasar negeri di wilayah Kecamatan Mojoroto.'],
            ['tahun' => '1995', 'judul' => 'Pengembangan sarana prasarana', 'isi' => 'Mulai dibangun ruang kelas baru dan perpustakaan sekolah untuk menunjang proses belajar mengajar.'],
            ['tahun' => '2010', 'judul' => 'Akreditasi', 'isi' => 'SDN Pojok 2 mendapatkan akreditasi B dari BAN-S/M serta terus meningkatkan mutu pembelajaran.'],
            ['tahun' => '2020', 'judul' => 'Renovasi gedung', 'isi' => 'Dilakukan renovasi beberapa ruang kelas dan fasilitas penunjang demi kenyamanan warga sekolah.'],
            ['tahun' => '2024 - Sekarang', 'judul' => 'Menuju sekolah unggul', 'isi' => 'Terus berinovasi dalam pembelajaran digital, program CERMAT, gerakan BERSEHAT, dan pengembangan karakter siswa.'],
        ];
    }

    /** @return array<int, array{label: string, nilai: string}> */
    private function defaultIdentity(): array
    {
        return [
            ['label' => 'NPSN', 'nilai' => '20534365'],
            ['label' => 'Status', 'nilai' => 'Negeri'],
            ['label' => 'Jenjang', 'nilai' => 'Sekolah Dasar (SD)'],
            ['label' => 'Kelurahan', 'nilai' => 'Pojok'],
            ['label' => 'Kecamatan', 'nilai' => 'Mojoroto'],
            ['label' => 'Kota', 'nilai' => 'Kota Kediri'],
            ['label' => 'Provinsi', 'nilai' => 'Jawa Timur'],
            ['label' => 'Akreditasi', 'nilai' => 'B'],
        ];
    }

    /** @return array<int, array{tingkat: int, jabatan: string, nama: string, keterangan: string}> */
    private function defaultOrganization(): array
    {
        return [
            ['tingkat' => 1, 'jabatan' => 'Kepala Sekolah', 'nama' => '', 'keterangan' => 'Kepala SDN Pojok 2'],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => '', 'keterangan' => 'Bidang Kurikulum'],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => '', 'keterangan' => 'Bidang Kesiswaan'],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => '', 'keterangan' => 'Bidang Sarana Prasarana'],
            ['tingkat' => 3, 'jabatan' => 'Bendahara & TU', 'nama' => '', 'keterangan' => 'Tata Usaha & BOS'],
            ['tingkat' => 3, 'jabatan' => 'Dewan Guru', 'nama' => '', 'keterangan' => 'Guru Kelas & Mapel'],
            ['tingkat' => 3, 'jabatan' => 'Tenaga Kependidikan', 'nama' => '', 'keterangan' => 'Layanan Khusus / Tendik'],
        ];
    }

    /** @return array<int, array{nama: string, ket: string, ikon: string}> */
    private function defaultFacilities(): array
    {
        return [
            ['nama' => 'Ruang Kelas', 'ket' => 'Ruang kelas yang nyaman dan mendukung proses belajar mengajar.', 'ikon' => 'kelas'],
            ['nama' => 'Perpustakaan', 'ket' => 'Tersedia koleksi buku yang lengkap untuk meningkatkan minat baca siswa.', 'ikon' => 'buku'],
            ['nama' => 'Laboratorium', 'ket' => 'Fasilitas praktikum dan komputer untuk mendukung pembelajaran berbasis sains & digital.', 'ikon' => 'laboratorium'],
            ['nama' => 'UKS', 'ket' => 'Unit Kesehatan Sekolah dengan layanan kesehatan dasar bagi warga sekolah.', 'ikon' => 'uks'],
            ['nama' => 'Lapangan', 'ket' => 'Lapangan olahraga dan upacara untuk kegiatan ekstrakurikuler serta kebugaran.', 'ikon' => 'lapangan'],
            ['nama' => 'Toilet Bersih', 'ket' => 'Toilet bersih dan terawat untuk mendukung gerakan BERSEHAT bagi siswa.', 'ikon' => 'toilet'],
        ];
    }

    /** @return array<int, string> */
    private function lines(string $text): array
    {
        return collect(preg_split('/\R/', $text) ?: [])
            ->map(fn (string $line): string => trim(preg_replace('/^\s*\d+[.)]\s*/', '', $line) ?? $line))
            ->filter()
            ->values()
            ->all();
    }

    /** @return array{pendek: array<int, string>, menengah: array<int, string>, panjang: array<int, string>} */
    private function defaultObjectives(): array
    {
        return [
            'pendek' => [
                '85% peserta didik memahami keimanan & nilai agama.',
                '90% peserta didik menunjukkan keteladanan di dalam & luar kelas.',
                '85% peserta didik mencintai potensi daerah.',
                '85% peserta didik mengikuti program CERMAT (Cerdas, Mandiri dan Terampil).',
                '85% peserta didik mengasah literasi, numerasi, dan pembelajaran HOTS.',
                '85% peserta didik bijak bermedia sosial & menjaga kebersihan lingkungan.',
            ],
            'menengah' => [
                '90% peserta didik mengamalkan nilai agama sehari-hari.',
                '85% peserta didik mandiri dalam kepemimpinan proyek & ekstrakurikuler.',
                '85% pendidik merancang pembelajaran sesuai bakat & meningkatkan literasi-numerasi.',
                '85% peserta didik mampu mencegah hoax & menjaga etika digital.',
                '85% peserta didik menerapkan gerakan BERSEHAT (Bersih, Elok, Sehat, Rindang dan Tentram).',
            ],
            'panjang' => [
                '90% peserta didik berakhlak mulia & peduli sosial dalam toleransi beragama.',
                '90% warga sekolah membangun kultur sekolah yang kompetitif dan positif.',
                '90% pendidik menyusun pembelajaran berbasis proyek budaya lokal.',
                '90% peserta didik mewujudkan budaya belajar mandiri & kolaborasi dengan mitra luar.',
            ],
        ];
    }
}
