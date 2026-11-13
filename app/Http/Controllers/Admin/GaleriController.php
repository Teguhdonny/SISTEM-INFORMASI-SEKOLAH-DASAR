<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galeri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GaleriController extends Controller
{
    public function index(Request $request): View
    {
        $galeri = Galeri::query()->latest('id')->get();
        $albums = $galeri
            ->groupBy(fn (Galeri $item): string => $item->judul.'|'.$item->kategori)
            ->map(fn ($album) => $album->sortBy('id')->values());
        $editGaleri = $request->filled('edit') ? Galeri::query()->find($request->integer('edit')) : null;
        $editAlbum = $editGaleri
            ? Galeri::query()
                ->where('judul', $editGaleri->judul)
                ->where('kategori', $editGaleri->kategori)
                ->orderBy('id')
                ->get()
            : collect();

        return view('pages.admin.galeri.index', compact('albums', 'editGaleri', 'editAlbum'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['required', 'string', 'max:100'],
            'foto' => ['required', 'array', 'min:1', 'max:20'],
            'foto.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ]);

        foreach ($validated['foto'] as $foto) {
            Galeri::create([
                'judul' => $validated['judul'],
                'kategori' => $validated['kategori'],
                'foto' => $foto->store('galeri', 'public'),
                'deskripsi' => $validated['deskripsi'] ?? null,
            ]);
        }

        return redirect()->route('admin.galeri.index')->with('success', 'Album galeri berhasil ditambahkan.');
    }

    public function update(Request $request, Galeri $galeri): RedirectResponse
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['required', 'string', 'max:100'],
            'foto' => ['sometimes', 'array', 'max:20'],
            'foto.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ]);

        $fotos = $data['foto'] ?? [];
        unset($data['foto']);

        Galeri::query()
            ->where('judul', $galeri->judul)
            ->where('kategori', $galeri->kategori)
            ->update($data);

        foreach ($fotos as $foto) {
            Galeri::create([
                'judul' => $data['judul'],
                'kategori' => $data['kategori'],
                'foto' => $foto->store('galeri', 'public'),
                'deskripsi' => $data['deskripsi'] ?? null,
            ]);
        }

        $message = $fotos === []
            ? 'Album galeri berhasil diperbarui.'
            : 'Album galeri berhasil diperbarui dan foto baru ditambahkan.';

        return redirect()->route('admin.galeri.index')->with('success', $message);
    }

    public function destroy(Galeri $galeri): RedirectResponse
    {
        $album = Galeri::query()
            ->where('judul', $galeri->judul)
            ->where('kategori', $galeri->kategori)
            ->get();

        foreach ($album as $foto) {
            if ($foto->foto) {
                Storage::disk('public')->delete($foto->foto);
            }
        }

        Galeri::query()
            ->where('judul', $galeri->judul)
            ->where('kategori', $galeri->kategori)
            ->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Album galeri beserta seluruh fotonya berhasil dihapus.');
    }
}
