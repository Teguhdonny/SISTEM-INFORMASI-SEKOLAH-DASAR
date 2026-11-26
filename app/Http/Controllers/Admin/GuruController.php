<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Services\ExcelWorkbook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GuruController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $guru = Guru::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('bagian', 'like', "%{$search}%")
                        ->orWhere('lulusan', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();
        if ($request->expectsJson()) {
            return response()->json([
                'rows' => view('pages.admin.guru.rows', compact('guru', 'search'))->render(),
                'count' => $guru->total(),
                'pagination' => $guru->hasPages() ? (string) $guru->links() : '',
            ]);
        }

        $totalGuru = Guru::count();

        return view('pages.admin.guru.index', compact('guru', 'search', 'totalGuru'));
    }

    public function exportExcel(Request $request, ExcelWorkbook $workbook): StreamedResponse
    {
        $rows = $this->guruQuery($request)->get()->map(fn (Guru $guru): array => [
            $guru->nama,
            $guru->nip,
            $guru->bagian,
            $guru->lulusan,
            $guru->kompetensi,
        ]);

        return $workbook->download('data-guru-staff.xlsx', ['Nama', 'NIP', 'Bagian', 'Lulusan', 'Kompetensi'], $rows);
    }

    public function exportPdf(Request $request): View
    {
        $rows = $this->guruQuery($request)->get()->map(fn (Guru $guru): array => [
            $guru->nama,
            $guru->nip,
            $guru->bagian,
            $guru->lulusan,
            $guru->kompetensi,
        ])->all();

        return $this->printView('Guru & Staff', ['Nama', 'NIP', 'Bagian', 'Jenjang Pendidikan', 'Kompetensi'], $rows);
    }

    public function importTemplate(ExcelWorkbook $workbook): StreamedResponse
    {
        return $workbook->download('template-impor-guru-staff.xlsx', ['Nama', 'NIP', 'Bagian', 'Lulusan', 'Kompetensi'], [], [1], true);
    }

    public function importExcel(Request $request, ExcelWorkbook $workbook): RedirectResponse
    {
        $fileValidator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'mimes:xlsx', 'extensions:xlsx', 'max:10240'],
        ], [
            'file.required' => 'Pilih file Excel yang akan diimpor.',
            'file.mimes' => 'File harus berformat Excel .xlsx.',
            'file.extensions' => 'Gunakan template Excel dengan ekstensi .xlsx.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ]);

        if ($fileValidator->fails()) {
            return back()->with('error', $fileValidator->errors()->first());
        }

        try {
            $rows = $workbook->read($request->file('file'));
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $seenNips = [];
        $validatedRows = [];
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $validator = Validator::make($row, [
                'nama' => ['required', 'string', 'max:100'],
                'nip' => ['required', 'digits:18', 'unique:guru,nip'],
                'bagian' => ['required', 'string', 'max:100'],
                'lulusan' => ['nullable', 'string', 'max:5000'],
                'kompetensi' => ['nullable', 'string', 'max:5000'],
            ], [
                'nama.required' => 'Nama lengkap wajib diisi.',
                'nip.required' => 'NIP wajib diisi.',
                'nip.digits' => 'NIP harus tepat 18 digit. Pastikan kolom NIP berformat Teks dan ketik ulang NIP dari sumber asli.',
                'nip.unique' => 'NIP sudah terdaftar.',
                'bagian.required' => 'Bagian / Tugas wajib diisi.',
            ]);

            if ($validator->fails()) {
                return back()->with('error', "Baris {$rowNumber}: ".$validator->errors()->first());
            }

            $data = $validator->validated();
            if (isset($seenNips[$data['nip']])) {
                return back()->with('error', "Baris {$rowNumber}: NIP duplikat di dalam file.");
            }
            $seenNips[$data['nip']] = true;
            $validatedRows[] = $data;
        }

        DB::transaction(function () use ($validatedRows): void {
            foreach ($validatedRows as $row) {
                Guru::query()->create($row);
            }
        });

        return back()->with('success', count($validatedRows).' data guru & staff berhasil diimpor.');
    }

    public function create(): View
    {
        return view('pages.admin.guru.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nip' => ['required', 'digits:18', Rule::unique('guru', 'nip')],
            'lulusan' => ['nullable', 'array', 'max:10'],
            'lulusan.*' => ['nullable', 'string', 'max:200'],
            'bagian' => ['required', 'string', 'max:100'],
            'kompetensi' => ['nullable', 'array', 'max:20'],
            'kompetensi.*' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'nip.required' => 'NIP wajib diisi dengan tepat 18 digit.',
            'nip.digits' => 'NIP harus terdiri dari tepat 18 digit angka.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
        ]);

        $validated['lulusan'] = $this->normalizeEducation($validated['lulusan'] ?? []);
        $validated['kompetensi'] = $this->normalizeCompetencies($validated['kompetensi'] ?? []);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('guru', 'public');
        }

        Guru::create($validated);

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru): RedirectResponse
    {
        return redirect()->route('admin.guru.edit', $guru);
    }

    public function edit(Guru $guru): View
    {
        return view('pages.admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $rules = [
            'nama' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'digits:18'],
            'lulusan' => ['nullable', 'array', 'max:10'],
            'lulusan.*' => ['nullable', 'string', 'max:200'],
            'bagian' => ['required', 'string', 'max:100'],
            'kompetensi' => ['nullable', 'array', 'max:20'],
            'kompetensi.*' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        if ($request->input('nip') !== $guru->nip) {
            $rules['nip'][] = Rule::unique('guru', 'nip')->ignore($guru->id);
        }
        $validated = $request->validate($rules, [
            'nip.digits' => 'NIP harus terdiri dari tepat 18 digit angka.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
        ]);

        $validated['lulusan'] = $this->normalizeEducation($validated['lulusan'] ?? []);
        $validated['kompetensi'] = $this->normalizeCompetencies($validated['kompetensi'] ?? []);

        $oldPhoto = $guru->foto;
        if ($request->hasFile('foto')) {
            $newPhoto = $request->file('foto')->store('guru', 'public');
            $validated['foto'] = $newPhoto;
        }

        $guru->update($validated);
        if ($request->hasFile('foto') && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        $photo = $guru->foto;
        $guru->delete();
        if ($photo) {
            Storage::disk('public')->delete($photo);
        }

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil dihapus.');
    }

    /** @param array<int, string|null> $educations */
    private function normalizeEducation(array $educations): string
    {
        return collect($educations)
            ->map(fn (?string $education): string => trim((string) $education))
            ->filter()
            ->values()
            ->implode("\n");
    }

    /** @param array<int, string|null> $competencies */
    private function normalizeCompetencies(array $competencies): string
    {
        return collect($competencies)
            ->map(fn (?string $competency): string => trim((string) $competency))
            ->filter()
            ->values()
            ->implode("\n");
    }

    private function guruQuery(Request $request): Builder
    {
        $search = $request->string('search')->trim()->toString();

        return Guru::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%")
                        ->orWhere('bagian', 'like', "%{$search}%")
                        ->orWhere('lulusan', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama');
    }

    /** @param array<int, string> $columns
     * @param  array<int, array<int, scalar|null>>  $rows
     */
    private function printView(string $title, array $columns, array $rows): View
    {
        return view('pages.admin.exports.print', [
            'title' => $title,
            'columns' => $columns,
            'rows' => $rows,
            'printedAt' => now('Asia/Jakarta')->format('d/m/Y H:i').' WIB',
        ]);
    }
}
