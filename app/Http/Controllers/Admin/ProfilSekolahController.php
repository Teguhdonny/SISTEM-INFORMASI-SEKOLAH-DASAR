<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use App\Models\VisiMisi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        return view('pages.admin.profil.index', compact('profilSekolah', 'visi', 'misi'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_sekolah' => ['required', 'string', 'max:150'],
            'email_sekolah' => ['required', 'email', 'max:100'],
            'alamat' => ['required', 'string', 'max:1000'],
            'visi' => ['required', 'string', 'max:5000'],
            'misi' => ['required', 'string', 'max:10000'],
        ]);

        $misi = collect(preg_split('/\R/', $validated['misi']) ?: [])
            ->map(fn (string $line): string => trim(preg_replace('/^\s*\d+[.)]\s*/', '', $line) ?? $line))
            ->filter()
            ->values();

        if ($misi->isEmpty()) {
            return back()->withInput()->withErrors(['misi' => 'Isi minimal satu misi sekolah.']);
        }

        DB::transaction(function () use ($validated, $misi): void {
            $profilSekolah = ProfilSekolah::query()->firstOrNew();
            $sejarah = $profilSekolah->sejarah ?: 'SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri.';
            $profilSekolah->fill([
                'nama_sekolah' => $validated['nama_sekolah'],
                'email_sekolah' => $validated['email_sekolah'],
                'alamat' => $validated['alamat'],
                'kontak' => $profilSekolah->kontak ?? '',
                'sejarah' => $sejarah,
            ])->save();

            VisiMisi::query()->whereIn('kategori', ['Visi', 'Misi'])->delete();
            VisiMisi::query()->create(['kategori' => 'Visi', 'deskripsi' => $validated['visi']]);

            foreach ($misi as $deskripsi) {
                VisiMisi::query()->create(['kategori' => 'Misi', 'deskripsi' => $deskripsi]);
            }
        });

        return redirect()->route('admin.profil.index')->with('success', 'Profil, visi, dan misi sekolah berhasil diperbarui.');
    }
}
