<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
        return view('pages.admin.ekstrakurikuler.form', ['item' => new Ekstrakurikuler]);
    }

    public function store(Request $request): RedirectResponse
    {
        Ekstrakurikuler::create($request->validate($this->rules()));

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler): View
    {
        return view('pages.admin.ekstrakurikuler.form', ['item' => $ekstrakurikuler]);
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $ekstrakurikuler->update($request->validate($this->rules()));

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
            'kategori' => ['required', 'string', 'max:50'],
            'jadwal' => ['required', 'string', 'max:100'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'pembina' => ['nullable', 'string', 'max:150'],
            'peserta' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:Aktif,Draft'],
        ];
    }
}
