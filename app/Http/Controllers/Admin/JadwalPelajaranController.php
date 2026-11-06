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
        $kelas = $this->classes();
        $hari = $this->days();
        $filters = $request->validate([
            'kelas' => ['nullable', Rule::in($kelas)],
            'hari' => ['nullable', Rule::in($hari)],
        ]);

        $jadwal = JadwalPelajaran::query()
            ->with('guru')
            ->when($filters['kelas'] ?? null, fn ($query, string $kelas) => $query->where('kelas', $kelas))
            ->when($filters['hari'] ?? null, fn ($query, string $hari) => $query->where('hari', $hari))
            ->orderBy('kelas')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.jadwal.index', compact('jadwal', 'kelas', 'hari', 'filters'));
    }

    public function create(): View
    {
        return view('pages.admin.jadwal.form', ['jadwalPelajaran' => new JadwalPelajaran, 'guru' => Guru::query()->orderBy('nama')->get(), 'kelas' => $this->classes(), 'hari' => $this->days()]);
    }

    public function store(Request $request): RedirectResponse
    {
        JadwalPelajaran::query()->create($request->validate($this->rules()));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran): View
    {
        return view('pages.admin.jadwal.form', ['jadwalPelajaran' => $jadwalPelajaran, 'guru' => Guru::query()->orderBy('nama')->get(), 'kelas' => $this->classes(), 'hari' => $this->days()]);
    }

    public function update(Request $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $jadwalPelajaran->update($request->validate($this->rules()));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $jadwalPelajaran->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    /** @return array<string, array<int, string>> */
    private function rules(): array
    {
        return [
            'kelas' => ['required', 'in:Kelas 1,Kelas 2,Kelas 3,Kelas 4,Kelas 5,Kelas 6'],
            'hari' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'mata_pelajaran' => ['required', 'string', 'max:100'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'id_guru' => ['required', 'integer', 'exists:guru,id'],
        ];
    }

    /** @return array<int, string> */
    private function classes(): array
    {
        return ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6'];
    }

    /** @return array<int, string> */
    private function days(): array
    {
        return ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    }
}
