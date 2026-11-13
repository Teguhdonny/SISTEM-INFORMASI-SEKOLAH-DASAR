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
    private const DAYS = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Senin–Jumat', 'Setiap hari'];

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
            'days' => self::DAYS,
            'scheduleParts' => ['hari' => '', 'jam_mulai' => '', 'jam_selesai' => ''],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->scheduleMessages());
        Ekstrakurikuler::create($this->withFormattedSchedule($validated));

        return redirect()->route('admin.ekstrakurikuler.index')->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Ekstrakurikuler $ekstrakurikuler): View
    {
        return view('pages.admin.ekstrakurikuler.form', [
            'item' => $ekstrakurikuler,
            'categories' => $this->categories(),
            'icons' => Ekstrakurikuler::ICON_OPTIONS,
            'colors' => Ekstrakurikuler::COLOR_OPTIONS,
            'days' => self::DAYS,
            'scheduleParts' => $this->scheduleParts($ekstrakurikuler->jadwal),
        ]);
    }

    public function update(Request $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $validated = $request->validate($this->rules(), $this->scheduleMessages());
        $ekstrakurikuler->update($this->withFormattedSchedule($validated));

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
            'hari' => ['required', Rule::in(self::DAYS)],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'deskripsi' => ['required', 'string', 'max:5000'],
            'pembina' => ['nullable', 'string', 'max:150'],
            'peserta' => ['required', 'integer', 'min:0'],
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

    /** @return array<string, string> */
    private function scheduleMessages(): array
    {
        return [
            'hari.required' => 'Hari kegiatan wajib dipilih.',
            'hari.in' => 'Pilih hari kegiatan yang tersedia.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format' => 'Gunakan format jam yang tersedia.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Gunakan format jam yang tersedia.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
        ];
    }

    /** @param array<string, mixed> $validated
     * @return array<string, mixed>
     */
    private function withFormattedSchedule(array $validated): array
    {
        $validated['jadwal'] = sprintf('%s, %s–%s', $validated['hari'], $validated['jam_mulai'], $validated['jam_selesai']);
        unset($validated['hari'], $validated['jam_mulai'], $validated['jam_selesai']);

        return $validated;
    }

    /** @return array{hari: string, jam_mulai: string, jam_selesai: string} */
    private function scheduleParts(string $schedule): array
    {
        if (preg_match('/^(.+?),\s*(\d{1,2})[:.](\d{2})\s*[–—-]\s*(\d{1,2})[:.](\d{2})$/u', trim($schedule), $matches) !== 1) {
            return ['hari' => '', 'jam_mulai' => '', 'jam_selesai' => ''];
        }

        return [
            'hari' => trim($matches[1]),
            'jam_mulai' => sprintf('%02d:%s', (int) $matches[2], $matches[3]),
            'jam_selesai' => sprintf('%02d:%s', (int) $matches[4], $matches[5]),
        ];
    }
}
