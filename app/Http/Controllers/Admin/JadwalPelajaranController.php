<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request): View
    {
        $kelas = $request->string('kelas')->trim()->toString();
        $hari = $request->string('hari')->trim()->toString();

        $jadwal = JadwalPelajaran::query()
            ->with('guru')
            ->when(in_array($kelas, JadwalPelajaran::DAFTAR_KELAS, true), fn ($query) => $query->where('kelas', $kelas))
            ->when(in_array($hari, JadwalPelajaran::DAFTAR_HARI, true), fn ($query) => $query->where('hari', $hari))
            ->urut()
            ->paginate(15)
            ->withQueryString();

        $totalJadwal = JadwalPelajaran::count();

        return view('pages.admin.jadwal.index', compact('jadwal', 'kelas', 'hari', 'totalJadwal'));
    }

    public function create(Request $request): View
    {
        $item = new JadwalPelajaran;
        $item->kelas = $request->string('kelas')->toString() ?: null;
        $item->hari = $request->string('hari')->toString() ?: null;

        return view('pages.admin.jadwal.form', [
            'item' => $item,
            'daftarGuru' => $this->daftarGuru(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        JadwalPelajaran::create($this->validated($request));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwal): View
    {
        return view('pages.admin.jadwal.form', [
            'item' => $jadwal,
            'daftarGuru' => $this->daftarGuru(),
        ]);
    }

    public function update(Request $request, JadwalPelajaran $jadwal): RedirectResponse
    {
        $jadwal->update($this->validated($request));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwal): RedirectResponse
    {
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    /** @return array<string, mixed> */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'kelas' => ['required', Rule::in(JadwalPelajaran::DAFTAR_KELAS)],
            'hari' => ['required', Rule::in(JadwalPelajaran::DAFTAR_HARI)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'id_guru' => ['nullable', 'integer', Rule::exists('guru', 'id')],
        ], [
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $validated['id_guru'] = $validated['id_guru'] ?? null;

        return $validated;
    }

    private function daftarGuru()
    {
        return Guru::query()->orderBy('nama')->get(['id', 'nama']);
    }
}
