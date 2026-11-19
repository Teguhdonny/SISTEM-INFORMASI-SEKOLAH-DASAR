<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\KategoriKonten;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EkstrakurikulerController extends Controller
{
    public function index(): View
    {
        $ekstrakurikuler = Ekstrakurikuler::query()->orderBy('nama_ekskul')->get();

        return view('pages.admin.ekstrakurikuler.index', compact('ekstrakurikuler'));
    }

    public function create(): View
    {
        return view('pages.admin.ekstrakurikuler.form', [
            'item' => new Ekstrakurikuler,
            'categories' => $this->categories(),
            'icons' => Ekstrakurikuler::ICON_OPTIONS,
            'colors' => Ekstrakurikuler::COLOR_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        Ekstrakurikuler::create($validated);

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler): View
    {
        return view('pages.admin.ekstrakurikuler.form', [
            'item' => $ekstrakurikuler,
            'categories' => $this->categories(),
            'icons' => Ekstrakurikuler::ICON_OPTIONS,
            'colors' => Ekstrakurikuler::COLOR_OPTIONS,
        ]);
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $ekstrakurikuler->update($validated);

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $ekstrakurikuler->delete();

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Kegiatan berhasil dihapus.');
    }

    /** @return array<string, array<int, string>> */
    private function rules(): array
    {
        return [
            'nama_ekskul' => ['required', 'string', 'max:100'],
            'kategori' => [
                'required',
                Rule::exists('kategori_konten', 'nama')
                    ->where(fn (Builder $query): Builder => $query->where('tipe', KategoriKonten::TYPE_EKSTRAKURIKULER)),
            ],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'pembina' => ['nullable', 'string', 'max:150'],
            'ikon' => ['required', Rule::in(array_keys(Ekstrakurikuler::ICON_OPTIONS))],
            'warna' => ['required', Rule::in(array_keys(Ekstrakurikuler::COLOR_OPTIONS))],
            'status' => ['required', 'in:Aktif,Draft'],
        ];
    }

    private function categories(): Collection
    {
        return KategoriKonten::query()
            ->where('tipe', KategoriKonten::TYPE_EKSTRAKURIKULER)
            ->orderBy('nama')
            ->pluck('nama');
    }
}
