<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BeritaController extends Controller
{
    public function index()
    {
        // Isi 4 berita default otomatis jika tabel masih kosong
        if (Berita::count() === 0) {
            $defaults = [
                [
                    'judul' => 'Upacara HUT RI ke-81 di SDN Pojok 2 Kota Kediri',
                    'kategori' => 'Pengumuman',
                    'tanggal' => '2026-08-20',
                    'isi' => 'Seluruh keluarga besar SDN Pojok 2 Kota Kediri melaksanakan upacara bendera memperingati Hari Ulang Tahun Kemerdekaan Republik Indonesia dengan khidmat dan penuh semangat nasionalisme.',
                    'status' => 'Aktif',
                ],
                [
                    'judul' => 'Siswa SDN Pojok 2 Raih Juara 1 Lomba Matematika Tingkat Kota',
                    'kategori' => 'Prestasi',
                    'tanggal' => '2026-08-15',
                    'isi' => 'Selamat kepada peserta didik SDN Pojok 2 Kota Kediri yang berhasil meraih prestasi membanggakan sebagai Juara 1 Olimpiade Matematika Tingkat Kota Kediri tahun 2026.',
                    'status' => 'Aktif',
                ],
                [
                    'judul' => 'MPLS Tahun Ajaran 2026/2027 Berjalan Lancar',
                    'kategori' => 'Kegiatan',
                    'tanggal' => '2026-08-10',
                    'isi' => 'Kegiatan Masa Pengenalan Lingkungan Sekolah (MPLS) bagi peserta didik baru kelas 1 berjalan ceria, interaktif, dan menyenangkan guna mengenalkan lingkungan belajar yang ramah anak.',
                    'status' => 'Aktif',
                ],
                [
                    'judul' => 'Daftar Ulang dan Pembagian Kelas Tahun Ajaran 2026/2027',
                    'kategori' => 'Informasi',
                    'tanggal' => '2026-08-05',
                    'isi' => 'Diinformasikan kepada seluruh orang tua/wali calon peserta didik baru yang telah dinyatakan diterima PPDB untuk segera melakukan registrasi daftar ulang melalui halaman website resmi.',
                    'status' => 'Aktif',
                ],
            ];

            foreach ($defaults as $item) {
                Berita::create($item);
            }
        }

        $beritas = Berita::orderByDesc('tanggal')->latest()->get();

        return view('pages.admin.berita.index', compact('beritas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|in:Pengumuman,Prestasi,Kegiatan,Informasi',
            'tanggal' => 'required|date',
            'isi' => 'required|string|max:10000',
            'status' => 'required|in:Aktif,Draft',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        Berita::create($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Berita / Pengumuman baru berhasil ditambahkan!');
    }

    public function update(Request $request, Berita $beritum)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'kategori' => 'required|in:Pengumuman,Prestasi,Kegiatan,Informasi',
            'tanggal' => 'required|date',
            'isi' => 'required|string|max:10000',
            'status' => 'required|in:Aktif,Draft',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($beritum->gambar && Storage::disk('public')->exists($beritum->gambar)) {
                Storage::disk('public')->delete($beritum->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('berita', 'public');
        }

        $beritum->update($validated);

        return redirect()->route('admin.berita.index')->with('success', 'Data Berita / Pengumuman berhasil diperbarui!');
    }

    public function destroy(Berita $beritum)
    {
        if ($beritum->gambar && Storage::disk('public')->exists($beritum->gambar)) {
            Storage::disk('public')->delete($beritum->gambar);
        }

        $beritum->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita / Pengumuman berhasil dihapus!');
    }
}