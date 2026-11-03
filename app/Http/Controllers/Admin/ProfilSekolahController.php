<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilSekolah;
use App\Models\VisiMisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ProfilSekolahController extends Controller
{
    public function index()
    {
        $profilSekolah = ProfilSekolah::first();

        if (!$profilSekolah) {
            $dataAwal = [
                'nama_sekolah' => 'SD Negeri Pojok 2 Kota Kediri',
                'alamat' => 'Jalan Mastrip Nomor 59, Kota Kediri, 64115, Jawa Timur',
                'email_sekolah' => 'pojok2mojoroto@gmail.com',
                'sejarah' => 'SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri yang berlokasi di Jalan Mastrip Nomor 59, Kecamatan Mojoroto, Kota Kediri.',
                'kontak' => '(0354) 771234 / 0812-3456-7890',
                'no_telepon' => '(0354) 771234',
                'akreditasi' => 'A',
                'npsn' => '20534389',
                'nama_kepsek' => 'H. Ahmad Fauzi, S.Pd',
                'sambutan_kepsek' => 'Kami ucapkan selamat datang di website resmi SDN Pojok 2 Kota Kediri. Semoga kehadiran website ini dapat menjadi jembatan informasi dan komunikasi antara sekolah, siswa, orang tua, dan masyarakat.',
            ];

            // Hanya masukkan kolom yang benar-benar ada di tabel profil_sekolah
            $kolomTabel = Schema::getColumnListing('profil_sekolah');
            $dataValid = array_intersect_key($dataAwal, array_flip($kolomTabel));

            $profilSekolah = ProfilSekolah::create($dataValid);
        }

        $visi = VisiMisi::where('kategori', 'Visi')->value('deskripsi')
            ?? 'Mewujudkan Generasi Muda yang Berakhlak Mulia, Mandiri, Kreatif Berwawasan Global dan Peduli Lingkungan.';

        $misi = VisiMisi::where('kategori', 'Misi')->orderBy('id')->pluck('deskripsi')->toArray();
        if (empty($misi)) {
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

    public function update(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:150',
            'email_sekolah' => 'required|email|max:100',
            'alamat' => 'required|string|max:1000',
            'nama_kepsek' => 'nullable|string|max:150',
            'sambutan_kepsek' => 'nullable|string|max:3000',
            'foto_kepsek' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'visi' => 'required|string|max:5000',
            'misi' => 'required|string|max:10000',
        ]);

        $profilSekolah = ProfilSekolah::first() ?? new ProfilSekolah();
        $kolomTabel = Schema::getColumnListing('profil_sekolah');

        $profilSekolah->nama_sekolah = $request->nama_sekolah;
        $profilSekolah->email_sekolah = $request->email_sekolah;
        $profilSekolah->alamat = $request->alamat;

        // Pastikan kolom sejarah dan kontak terisi jika ada di tabel
        if (in_array('sejarah', $kolomTabel) && empty($profilSekolah->sejarah)) {
            $profilSekolah->sejarah = 'SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri.';
        }
        if (in_array('kontak', $kolomTabel) && empty($profilSekolah->kontak)) {
            $profilSekolah->kontak = '(0354) 771234 / 0812-3456-7890';
        }

        if (in_array('nama_kepsek', $kolomTabel)) {
            $profilSekolah->nama_kepsek = $request->nama_kepsek ?: 'H. Ahmad Fauzi, S.Pd';
        }
        if (in_array('sambutan_kepsek', $kolomTabel)) {
            $profilSekolah->sambutan_kepsek = $request->sambutan_kepsek;
        }

        // Jika Admin mengunggah foto kepala sekolah baru
        if ($request->hasFile('foto_kepsek') && in_array('foto_kepsek', $kolomTabel)) {
            if ($profilSekolah->foto_kepsek && Storage::disk('public')->exists($profilSekolah->foto_kepsek)) {
                Storage::disk('public')->delete($profilSekolah->foto_kepsek);
            }
            $profilSekolah->foto_kepsek = $request->file('foto_kepsek')->store('profil_sekolah', 'public');
        }

        $profilSekolah->save();

        // Simpan Visi
        VisiMisi::updateOrCreate(
            ['kategori' => 'Visi'],
            ['deskripsi' => trim($request->visi)]
        );

        // Simpan Misi (Satu per baris)
        VisiMisi::where('kategori', 'Misi')->delete();
        $barisMisi = array_filter(array_map('trim', explode("\n", $request->misi)));
        foreach ($barisMisi as $itemMisi) {
            VisiMisi::create([
                'kategori' => 'Misi',
                'deskripsi' => $itemMisi,
            ]);
        }

        return redirect()->route('admin.profil.index')->with('success', 'Profil Sekolah, Sambutan Kepala Sekolah & Visi Misi berhasil diperbarui!');
    }
}