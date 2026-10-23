<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramSekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return view('pages.admin.program.form', ['program' => new ProgramSekolah]);
    }

    public function store(Request $request): RedirectResponse
    {
        ProgramSekolah::create($request->validate($this->rules()));

        return redirect()->route('admin.program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(ProgramSekolah $programSekolah): View
    {
        return view('pages.admin.program.form', ['program' => $programSekolah]);
    }

    public function update(Request $request, ProgramSekolah $programSekolah): RedirectResponse
    {
        $programSekolah->update($request->validate($this->rules()));

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
            'kategori' => ['required', 'string', 'max:50'],
            'jadwal' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'peserta' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Aktif,Draft'],
        ];
    }
}
