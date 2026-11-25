<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKonten;
use App\Models\ProgramSekolah;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProgramSekolahController extends Controller
{
    public function index(): View
    {
        $programs = ProgramSekolah::query()->orderBy('nama_program')->get();

        return view('pages.admin.program.index', compact('programs'));
    }

    public function create(): View
    {
        return view('pages.admin.program.form', [
            'program' => new ProgramSekolah,
            'categories' => $this->categories(),
            'icons' => ProgramSekolah::ICON_OPTIONS,
            'colors' => ProgramSekolah::COLOR_OPTIONS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        ProgramSekolah::create($validated);

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(ProgramSekolah $programSekolah): View
    {
        return view('pages.admin.program.form', [
            'program' => $programSekolah,
            'categories' => $this->categories(),
            'icons' => ProgramSekolah::ICON_OPTIONS,
            'colors' => ProgramSekolah::COLOR_OPTIONS,
        ]);
    }

    public function update(Request $request, ProgramSekolah $programSekolah): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $programSekolah->update($validated);

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(ProgramSekolah $programSekolah): RedirectResponse
    {
        $programSekolah->delete();

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil dihapus.');
    }

    /** @return array<string, array<int, string>> */
    private function rules(): array
    {
        return [
            'nama_program' => ['required', 'string', 'max:150'],
            'kategori' => [
                'required',
                Rule::exists('kategori_konten', 'nama')
                    ->where(fn (Builder $query): Builder => $query->where('tipe', KategoriKonten::TYPE_PROGRAM)),
            ],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'ikon' => ['required', Rule::in(array_keys(ProgramSekolah::ICON_OPTIONS))],
            'warna' => ['required', Rule::in(array_keys(ProgramSekolah::COLOR_OPTIONS))],
            'status' => ['required', 'in:Aktif,Draft'],
        ];
    }

    private function categories(): Collection
    {
        return KategoriKonten::query()
            ->where('tipe', KategoriKonten::TYPE_PROGRAM)
            ->orderBy('nama')
            ->pluck('nama');
    }
}
