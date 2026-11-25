<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\RegistrationPeriod;
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
            ->orderByRaw("CASE kelas WHEN 'Kelas 1' THEN 1 WHEN 'Kelas 2' THEN 2 WHEN 'Kelas 3' THEN 3 WHEN 'Kelas 4' THEN 4 WHEN 'Kelas 5' THEN 5 WHEN 'Kelas 6' THEN 6 ELSE 7 END")
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
            ->orderBy('jam_mulai')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.jadwal.index', compact('jadwal', 'kelas', 'hari', 'filters'));
    }

    public function exportPdf(Request $request): View
    {
        $filters = $request->validate([
            'kelas' => ['nullable', Rule::in($this->classes())],
            'hari' => ['nullable', Rule::in($this->days())],
        ]);
        $rows = JadwalPelajaran::query()
            ->with('guru')
            ->when($filters['kelas'] ?? null, fn ($query, string $kelas) => $query->where('kelas', $kelas))
            ->when($filters['hari'] ?? null, fn ($query, string $hari) => $query->where('hari', $hari))
            ->orderByRaw("CASE kelas WHEN 'Kelas 1' THEN 1 WHEN 'Kelas 2' THEN 2 WHEN 'Kelas 3' THEN 3 WHEN 'Kelas 4' THEN 4 WHEN 'Kelas 5' THEN 5 WHEN 'Kelas 6' THEN 6 ELSE 7 END")
            ->orderByRaw("CASE hari WHEN 'Senin' THEN 1 WHEN 'Selasa' THEN 2 WHEN 'Rabu' THEN 3 WHEN 'Kamis' THEN 4 WHEN 'Jumat' THEN 5 WHEN 'Sabtu' THEN 6 ELSE 7 END")
            ->orderBy('jam_mulai')
            ->get()
            ->map(fn (JadwalPelajaran $jadwal): array => [
                $jadwal->tahun_ajaran ?: '-',
                $jadwal->semester ?: '-',
                $jadwal->hari,
                $jadwal->kelas,
                substr($jadwal->jam_mulai, 0, 5).'–'.substr($jadwal->jam_selesai, 0, 5),
                $jadwal->mata_pelajaran,
                $jadwal->guru->nama,
            ])->all();

        return view('pages.admin.exports.print', [
            'title' => 'Jadwal Pelajaran',
            'columns' => ['Tahun Ajaran', 'Semester', 'Hari', 'Kelas', 'Waktu', 'Mata Pelajaran', 'Guru'],
            'rows' => $rows,
            'printedAt' => now('Asia/Jakarta')->format('d/m/Y H:i').' WIB',
        ]);
    }

    public function create(): View
    {
        return view('pages.admin.jadwal.form', [
            'jadwalPelajaran' => new JadwalPelajaran,
            'guru' => Guru::query()->orderBy('nama')->get(['id', 'nama', 'kompetensi']),
            'kelas' => $this->classes(),
            'hari' => $this->days(),
            'mataPelajaran' => $this->subjects(),
            'academicYears' => $this->academicYears(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'semester' => $request->input('semester', 'Ganjil'),
            'tahun_ajaran' => $request->input('tahun_ajaran', RegistrationPeriod::query()->value('tahun_ajaran') ?? now('Asia/Jakarta')->year.'/'.(now('Asia/Jakarta')->year + 1)),
        ]);
        $validated = $request->validate($this->rules());
        $this->ensureNoScheduleConflict($validated);
        $this->validateTeacherCompetency($validated);

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
            'academicYears' => $this->academicYears(),
        ]);
    }

    public function update(Request $request, JadwalPelajaran $jadwalPelajaran): RedirectResponse
    {
        $request->merge([
            'semester' => $request->input('semester', $jadwalPelajaran->semester ?? 'Ganjil'),
            'tahun_ajaran' => $request->input('tahun_ajaran', $jadwalPelajaran->tahun_ajaran ?? RegistrationPeriod::query()->value('tahun_ajaran') ?? now('Asia/Jakarta')->year.'/'.(now('Asia/Jakarta')->year + 1)),
        ]);
        $validated = $request->validate($this->rules());
        $this->ensureNoScheduleConflict($validated, $jadwalPelajaran->id);
        $this->validateTeacherCompetency($validated);

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
            'semester' => ['required', 'in:Ganjil,Genap'],
            'tahun_ajaran' => ['required', 'regex:/^[0-9]{4}\/[0-9]{4}$/'],
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

    /** @return array<int, string> */
    private function academicYears(): array
    {
        $current = RegistrationPeriod::query()->value('tahun_ajaran') ?? now('Asia/Jakarta')->year.'/'.(now('Asia/Jakarta')->year + 1);

        return array_values(array_unique([$current, ((int) substr($current, 0, 4) - 1).'/'.((int) substr($current, 0, 4))]));
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
    private function ensureNoScheduleConflict(array $validated, ?int $exceptId = null): void
    {
        $overlappingSchedules = JadwalPelajaran::query()
            ->where('hari', $validated['hari'])
            ->where('semester', $validated['semester'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId));

        if ((clone $overlappingSchedules)->where('id_guru', $validated['id_guru'])->exists()) {
            throw ValidationException::withMessages([
                'id_guru' => 'Guru tersebut sudah memiliki jadwal lain yang bentrok pada hari dan jam yang sama.',
            ]);
        }

        $classConflict = JadwalPelajaran::query()
            ->where('hari', $validated['hari'])
            ->where('semester', $validated['semester'])
            ->where('tahun_ajaran', $validated['tahun_ajaran'])
            ->where('kelas', $validated['kelas'])
            ->where('mata_pelajaran', '!=', $validated['mata_pelajaran'])
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->when($exceptId, fn ($query) => $query->where('id', '!=', $exceptId))
            ->exists();

        if ($classConflict) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Kelas tersebut sudah memiliki jadwal pada jam ini di hari yang sama. Silakan pilih jam lain.',
            ]);
        }
    }
}
