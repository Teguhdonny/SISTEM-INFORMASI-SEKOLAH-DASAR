<?php

namespace App\Http\Controllers;

use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PendaftaranWebController extends Controller
{
    public function create(): View
    {
        $registrationPeriod = RegistrationPeriod::query()->first();
        $registrationOpen = $registrationPeriod?->acceptsRegistrationsAt(now('Asia/Jakarta')) ?? false;

        return view('pages.public.registrasi.ppdb', compact('registrationPeriod', 'registrationOpen'));
    }

    public function store(Request $request): RedirectResponse
    {
        $registrationPeriod = RegistrationPeriod::query()->first();
        if (! $registrationPeriod?->acceptsRegistrationsAt(now('Asia/Jakarta'))) {
            return redirect()->route('registrasi.public.create')->with('registration_closed', true);
        }

        $validated = $request->validate([
            'nisn' => ['required', 'digits:10'],
            'nama_siswa' => ['required', 'string', 'max:150'],
            'nama_wali' => ['required', 'string', 'max:100'],
            'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            'file_akta' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'file_kk' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ], [
            'file_akta.required' => 'Akta Kelahiran wajib diunggah.',
            'file_kk.required' => 'Kartu Keluarga wajib diunggah.',
        ]);

        $siswa = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->where('tahun_ajaran', $registrationPeriod->tahun_ajaran)
            ->where('nisn', $validated['nisn'])
            ->where('nama_siswa', $validated['nama_siswa'])
            ->where('status_diterima', true)
            ->first();

        if (! $siswa) {
            return back()
                ->withErrors(['nisn' => 'Data nama dan NISN tidak cocok dengan daftar siswa yang diterima PPDB.'])
                ->withInput();
        }

        if ($siswa->daftarUlang()->exists()) {
            return back()
                ->withErrors(['nisn' => 'Siswa ini sudah mengirim registrasi daftar ulang.'])
                ->withInput();
        }

        $aktaPath = $request->hasFile('file_akta') ? $request->file('file_akta')->store('dokumen_ppdb', 'public') : null;
        $kkPath = $request->hasFile('file_kk') ? $request->file('file_kk')->store('dokumen_ppdb', 'public') : null;

        $siswa->daftarUlang()->create([
            'nama_wali' => $validated['nama_wali'],
            'no_whatsapp' => $validated['no_whatsapp'],
            'file_akta' => $aktaPath,
            'file_kk' => $kkPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim! Data Anda sudah masuk ke sistem.');
    }

    // Menampilkan data dan statistik registrasi daftar ulang di Panel Admin
    public function adminIndex(): View
    {
        $pendaftars = SiswaPpdb::query()->where('dicatat_admin', true)->whereHas('daftarUlang')->with('daftarUlang')->latest()->get();

        // Hitung statistik untuk 4 kartu ringkasan
        $totalPendaftar = $pendaftars->count();
        $menungguVerifikasi = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status == 'Menunggu Verifikasi' || ! optional($item->daftarUlang)->status;
        })->count();
        $diterima = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status == 'Diterima';
        })->count();
        $ditolak = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status == 'Ditolak';
        })->count();

        return view('pages.admin.data-ppdb.index', compact(
            'pendaftars', 'totalPendaftar', 'menungguVerifikasi', 'diterima', 'ditolak'
        ));
    }

    // Mengubah status verifikasi pendaftar oleh admin
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Diterima,Ditolak',
        ]);

        $siswa = SiswaPpdb::query()->where('dicatat_admin', true)->whereHas('daftarUlang')->findOrFail($id);
        $siswa->daftarUlang->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status verifikasi siswa berhasil diperbarui!');
    }
}
