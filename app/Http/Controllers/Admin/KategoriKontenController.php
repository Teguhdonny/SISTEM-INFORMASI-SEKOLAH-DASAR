<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\KategoriKonten;
use App\Models\ProgramSekolah;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KategoriKontenController extends Controller
{
    public function index(): View
    {
        $programCategories = KategoriKonten::query()
            ->where('tipe', KategoriKonten::TYPE_PROGRAM)
            ->orderBy('nama')
            ->get();
        $extracurricularCategories = KategoriKonten::query()
            ->where('tipe', KategoriKonten::TYPE_EKSTRAKURIKULER)
            ->orderBy('nama')
            ->get();
        $programUsage = ProgramSekolah::query()
            ->select('kategori')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');
        $extracurricularUsage = Ekstrakurikuler::query()
            ->select('kategori')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        return view('pages.admin.kategori-konten.index', compact(
            'programCategories',
            'extracurricularCategories',
            'programUsage',
            'extracurricularUsage',
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $categoryType = $request->string('tipe')->toString();
        $validated = $request->validate([
            'tipe' => ['required', Rule::in([KategoriKonten::TYPE_PROGRAM, KategoriKonten::TYPE_EKSTRAKURIKULER])],
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kategori_konten', 'nama')
                    ->where(fn (Builder $query): Builder => $query->where('tipe', $categoryType)),
            ],
        ], [
            'nama.unique' => 'Nama kategori tersebut sudah ada untuk jenis konten ini.',
        ]);

        KategoriKonten::query()->create($validated);

        return redirect()->route('admin.kategori-konten.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, KategoriKonten $kategoriKonten): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kategori_konten', 'nama')
                    ->where(fn (Builder $query): Builder => $query->where('tipe', $kategoriKonten->tipe))
                    ->ignore($kategoriKonten),
            ],
        ], [
            'nama.unique' => 'Nama kategori tersebut sudah ada untuk jenis konten ini.',
        ]);

        $oldName = $kategoriKonten->nama;
        $newName = trim($validated['nama']);

        DB::transaction(function () use ($kategoriKonten, $oldName, $newName): void {
            $kategoriKonten->update(['nama' => $newName]);

            if ($kategoriKonten->tipe === KategoriKonten::TYPE_PROGRAM) {
                ProgramSekolah::query()->where('kategori', $oldName)->update(['kategori' => $newName]);
            } else {
                Ekstrakurikuler::query()->where('kategori', $oldName)->update(['kategori' => $newName]);
            }
        });

        return redirect()->route('admin.kategori-konten.index')->with('success', 'Nama kategori dan konten terkait berhasil diperbarui.');
    }

    public function destroy(KategoriKonten $kategoriKonten): RedirectResponse
    {
        $usageCount = $kategoriKonten->tipe === KategoriKonten::TYPE_PROGRAM
            ? ProgramSekolah::query()->where('kategori', $kategoriKonten->nama)->count()
            : Ekstrakurikuler::query()->where('kategori', $kategoriKonten->nama)->count();

        if ($usageCount > 0) {
            return redirect()->route('admin.kategori-konten.index')
                ->with('error', "Kategori masih dipakai oleh {$usageCount} konten. Ubah kategori konten tersebut sebelum menghapusnya.");
        }

        $kategoriKonten->delete();

        return redirect()->route('admin.kategori-konten.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
