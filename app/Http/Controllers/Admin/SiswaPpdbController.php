<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaPpdbController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $siswa = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_siswa', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('tahun_ajaran', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'rows' => view('pages.admin.ppdb.rows', compact('siswa', 'search'))->render(),
                'count' => $siswa->total(),
                'pagination' => $siswa->hasPages() ? (string) $siswa->links() : '',
            ]);
        }

        return view('pages.admin.ppdb.index', compact('siswa', 'search'));
    }

    public function create(): View
    {
        $tahunAjaranAktif = RegistrationPeriod::query()->value('tahun_ajaran');

        return view('pages.admin.ppdb.create', compact('tahunAjaranAktif'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_siswa' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'nik' => ['required', 'digits:16', Rule::unique('siswa_ppdb', 'nik')],
            'nisn' => ['required', 'digits:10', Rule::unique('siswa_ppdb', 'nisn')],
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nisn.unique' => 'NISN ini sudah terdaftar.',
        ]);

        $tahunAjaranAktif = RegistrationPeriod::query()->value('tahun_ajaran');
        if (! $tahunAjaranAktif) {
            return redirect()->route('admin.registration-period.edit')
                ->with('error', 'Atur tahun ajaran aktif sebelum menambahkan data siswa PPDB.');
        }

        $validated['no_pendaftaran'] = 'PPDB-ADMIN-'.Str::upper(Str::random(8));
        $validated['status_diterima'] = true;
        $validated['dicatat_admin'] = true;
        $validated['tahun_ajaran'] = $tahunAjaranAktif;
        SiswaPpdb::create($validated);

        return redirect()->route('admin.ppdb.index')->with('success', 'Data siswa PPDB berhasil ditambahkan.');
    }

    public function edit(SiswaPpdb $siswaPpdb): View
    {
        $tahunAjaranAktif = RegistrationPeriod::query()->value('tahun_ajaran');

        return view('pages.admin.ppdb.edit', compact('siswaPpdb', 'tahunAjaranAktif'));
    }

    public function update(Request $request, SiswaPpdb $siswaPpdb): RedirectResponse
    {
        $validated = $request->validate([
            'nama_siswa' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'nik' => ['required', 'digits:16', Rule::unique('siswa_ppdb', 'nik')->ignore($siswaPpdb->id)],
            'nisn' => ['required', 'digits:10', Rule::unique('siswa_ppdb', 'nisn')->ignore($siswaPpdb->id)],
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'nisn.unique' => 'NISN ini sudah terdaftar.',
        ]);

        if (! $siswaPpdb->tahun_ajaran) {
            $tahunAjaranAktif = RegistrationPeriod::query()->value('tahun_ajaran');
            if (! $tahunAjaranAktif) {
                return redirect()->route('admin.registration-period.edit')
                    ->with('error', 'Atur tahun ajaran aktif sebelum memperbarui data siswa PPDB.');
            }

            $validated['tahun_ajaran'] = $tahunAjaranAktif;
        }

        $validated['dicatat_admin'] = true;
        $siswaPpdb->update($validated);

        return redirect()->route('admin.ppdb.index')->with('success', 'Data siswa PPDB berhasil diperbarui.');
    }

    public function destroy(SiswaPpdb $siswaPpdb): RedirectResponse
    {
        $siswaPpdb->delete();

        return redirect()->route('admin.ppdb.index')->with('success', 'Data siswa PPDB berhasil dihapus.');
    }
}
