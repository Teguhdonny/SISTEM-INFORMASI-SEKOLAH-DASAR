<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        return view('pages.admin.jadwal.form', [
            'jadwalPelajaran' => new JadwalPelajaran,
            'guru' => Guru::query()->orderBy('nama')->get(['id', 'nama', 'kompetensi']),
            'kelas' => $this->classes(),
            'hari' => $this->days(),
            'mataPelajaran' => $this->subjects(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $this->validateDateMatchesDay($validated);
        $this->validateTeacherCompetency($validated);
        $this->ensureNoScheduleConflict($validated);

        JadwalPelajaran::query()->create($validated);

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(JadwalPelajaran $jadwalPelajaran): View
    {
        return view('pages.admin.jadwal.form', [
            'jadwalPelajaran' => $jadwalPelajaran,
            'guru' => Guru::query()->orderBy('nama')->get(['id', 'nama', 'kompetensi']),
            'kelas' => $this->classes(),
            'hari' => $this->days(),
            'mataPelajaran' => $this->subjects(),
        ]);
    }

    public function update(Request $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $validated = $request->validate($this->rules());
        $this->validateDateMatchesDay($validated);
        $this->validateTeacherCompetency($validated);
        $this->ensureNoScheduleConflict($validated, $jadwalPelajaran->id);

        $jadwalPelajaran->update($validated);

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
            'tanggal' => ['required', 'date'],
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

    /** @return array<int, string> */
    private function subjects(): array
    {
        return [
            'Pendidikan Agama Islam',
            'Pendidikan Pancasila',
            'Bahasa Indonesia',
            'Matematika',
            'IPAS',
            'PJOK',
            'Seni Budaya',
            'Bahasa Inggris',
            'Bahasa Jawa',
        ];
    }

    /** @param array<string, mixed> $validated */
    private function validateTeacherCompetency(array $validated): void
    {
        $guru = Guru::query()->findOrFail($validated['id_guru']);
        if ($guru->hasCompetency($validated['mata_pelajaran'])) {
            return;
        }

        throw ValidationException::withMessages([
            'id_guru' => "Guru {$guru->nama} belum memiliki kompetensi {$validated['mata_pelajaran']}.",
        ]);
    }

    /** @param array<string, mixed> $validated */
    private function validateDateMatchesDay(array $validated): void
    {
        $dayNames = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        $expectedDay = $dayNames[CarbonImmutable::parse($validated['tanggal'])->dayOfWeekIso] ?? null;

        if ($expectedDay === null) {
            throw ValidationException::withMessages([
                'tanggal' => 'Tanggal jadwal hanya boleh berada pada hari Senin sampai Sabtu.',
            ]);
        }

        if ($expectedDay === $validated['hari']) {
            return;
        }

        throw ValidationException::withMessages([
            'tanggal' => "Tanggal tersebut jatuh pada {$expectedDay}. Pilih hari yang sesuai dengan tanggal.",
        ]);
    }

    /** @param array<string, mixed> $validated */
    private function ensureNoScheduleConflict(array $validated, ?int $exceptId = null): void
    {
        $overlappingSchedules = JadwalPelajaran::query()
            ->where('hari', $validated['hari'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId));

        if ((clone $overlappingSchedules)->where('id_guru', $validated['id_guru'])->exists()) {
            throw ValidationException::withMessages([
                'id_guru' => 'Guru tersebut sudah memiliki jadwal lain yang bentrok pada hari, tanggal, dan jam yang sama.',
            ]);
        }

        $classConflict = (clone $overlappingSchedules)
            ->where('kelas', $validated['kelas'])
            ->where('mata_pelajaran', '!=', $validated['mata_pelajaran'])
            ->exists();

        if ($classConflict) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Kelas tersebut sudah memiliki jadwal mata pelajaran lain pada waktu yang bentrok.',
            ]);
        }
    }
}
