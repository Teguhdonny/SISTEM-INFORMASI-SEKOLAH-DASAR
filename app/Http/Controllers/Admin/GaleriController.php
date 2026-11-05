<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller
{
    public function index()
    {
        if (Galeri::count() === 0) {
            $defaults = [
                ['judul' => 'Kegiatan Upacara Bendera', 'kategori' => 'Kegiatan', 'tanggal' => '2026-08-17'],
                ['judul' => 'Peringatan Hari Pendidikan', 'kategori' => 'Kegiatan', 'tanggal' => '2026-05-02'],
                ['judul' => 'Kegiatan Belajar Mengajar', 'kategori' => 'Kegiatan', 'tanggal' => '2026-07-15'],
                ['judul' => 'Perpustakaan Sekolah', 'kategori' => 'Fasilitas', 'tanggal' => '2026-03-10'],
                ['judul' => 'Lomba Olahraga Siswa', 'kategori' => 'Prestasi', 'tanggal' => '2026-02-22'],
                ['judul' => 'Lingkungan Sekolah', 'kategori' => 'Fasilitas', 'tanggal' => '2026-01-05'],
            ];
            foreach ($defaults as $item) {
                Galeri::create($item);
            }
        }

        $galeris = Galeri::orderByDesc('tanggal')->latest()->get();

        return view('pages.admin.galeri.index', compact('galeris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'kategori' => 'required|in:Kegiatan,Fasilitas,Prestasi',
            'tanggal' => 'required|date',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('galeri', 'public');
        }

        Galeri::create($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto dokumentasi baru berhasil ditambahkan ke Galeri!');
    }

    public function update(Request $request, Galeri $galeri)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:150',
            'kategori' => 'required|in:Kegiatan,Fasilitas,Prestasi',
            'tanggal' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
                Storage::disk('public')->delete($galeri->foto);
            }
            $validated['foto'] = $request->file('foto')->store('galeri', 'public');
        }

        $galeri->update($validated);

        return redirect()->route('admin.galeri.index')->with('success', 'Data Galeri Foto berhasil diperbarui!');
    }

    public function destroy(Galeri $galeri)
    {
        if ($galeri->foto && Storage::disk('public')->exists($galeri->foto)) {
            Storage::disk('public')->delete($galeri->foto);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil dihapus!');
    }
}