<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BeritaController extends Controller
{
    public function index(Request $request): View
    {
        $berita = Berita::query()->latest('tanggal_kegiatan')->latest('id')->get();
        $editBerita = $request->filled('edit') ? Berita::query()->find($request->integer('edit')) : null;

        return view('pages.admin.berita.index', compact('berita', 'editBerita'));
    }

    public function store(Request $request): RedirectResponse
    {
        Berita::create($this->validatedData($request));

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function update(Request $request, Berita $berita): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (array_key_exists('foto', $data) && $data['foto'] !== null && $berita->foto) {
            Storage::disk('public')->delete($berita->foto);
        }

        $berita->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        if ($berita->foto) {
            Storage::disk('public')->delete($berita->foto);
        }

        $berita->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'kategori' => ['required', 'in:Pengumuman,Kegiatan,Informasi'],
            'tanggal_kegiatan' => ['required', 'date'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['required', 'in:Aktif,Draft'],
            'isi' => ['required', 'string', 'max:10000'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('berita', 'public');
        } else {
            unset($validated['foto']);
        }

        return $validated;
    }
}
