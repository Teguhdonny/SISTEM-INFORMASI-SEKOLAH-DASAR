<?php

namespace App\Http\Controllers;

use App\Models\PendaftaranPindahan;
use App\Models\RegistrationPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PendaftaranPindahanWebController extends Controller
{
    public function create(): View
    {
        $tahunAjaranAktif = RegistrationPeriod::query()->value('tahun_ajaran');

        return view('pages.public.registrasi.pindahan', compact('tahunAjaranAktif'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_siswa' => ['required', 'string', 'max:150'],
            'nisn' => ['required', 'digits:10'],
            'nisn_aktif_dapodik' => ['accepted'],
            'nik' => ['required', 'digits:16'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'asal_sekolah' => ['required', 'string', 'max:150'],
            'kelas_asal' => ['required', 'in:1,2,3,4,5,6'],
            'kelas_tujuan' => ['required', 'in:Kelas 1,Kelas 2,Kelas 3,Kelas 4,Kelas 5,Kelas 6'],
            'tahun_ajaran' => ['required', 'regex:/^[0-9]{4}\/[0-9]{4}$/'],
            'alasan_pindah' => ['required', 'string', 'max:2000'],
            'nama_wali' => ['required', 'string', 'max:100'],
            'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            'file_surat_pindah' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'file_rekomendasi_diterima' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'file_rapor' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'file_surat_permohonan' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'file_kk' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'file_akta' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
            'file_rekomendasi_dinas' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'],
        ], [
            'nisn_aktif_dapodik.accepted' => 'Konfirmasikan bahwa NISN siswa terdaftar aktif di Dapodik.',
            'kelas_asal.in' => 'Pilih kelas asal dari kelas 1 sampai kelas 6.',
            'tahun_ajaran.regex' => 'Tahun ajaran harus menggunakan format seperti 2026/2027.',
            'no_whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi 8 sampai 20 angka.',
            'file_surat_pindah.required' => 'Surat keterangan pindah sekolah wajib diunggah.',
            'file_rekomendasi_diterima.required' => 'Surat rekomendasi diterima dari sekolah tujuan wajib diunggah.',
            'file_rapor.required' => 'Rapor terakhir wajib diunggah.',
            'file_surat_permohonan.required' => 'Surat permohonan pindah dari orang tua/wali wajib diunggah.',
            'file_kk.required' => 'Kartu Keluarga wajib diunggah.',
            'file_akta.required' => 'Akta kelahiran wajib diunggah.',
        ]);

        $kelasTujuan = (int) str_replace('Kelas ', '', $validated['kelas_tujuan']);
        $kelasAsal = (int) $validated['kelas_asal'];
        if ($kelasTujuan !== $kelasAsal) {
            throw ValidationException::withMessages([
                'kelas_tujuan' => 'Kelas tujuan harus sama dengan kelas di sekolah asal.',
            ]);
        }

        $activeApplicationExists = PendaftaranPindahan::query()
            ->where('nisn', $validated['nisn'])
            ->whereIn('status', ['Menunggu Verifikasi', 'Perlu Perbaikan', 'Diterima'])
            ->exists();

        if ($activeApplicationExists) {
            return back()
                ->withErrors(['nisn' => 'Pengajuan dengan NISN ini masih diproses atau sudah disetujui. Hubungi sekolah untuk tindak lanjut.'])
                ->withInput();
        }

        $validated['no_pendaftaran'] = 'PINDAH-'.now('Asia/Jakarta')->format('Y').'-'.Str::upper(Str::random(8));
        $validated['file_surat_pindah'] = $request->file('file_surat_pindah')->store('dokumen-pindahan', 'local');
        $validated['file_rekomendasi_diterima'] = $request->file('file_rekomendasi_diterima')->store('dokumen-pindahan', 'local');
        $validated['file_surat_permohonan'] = $request->file('file_surat_permohonan')->store('dokumen-pindahan', 'local');
        $validated['file_kk'] = $request->file('file_kk')->store('dokumen-pindahan', 'local');
        $validated['file_akta'] = $request->file('file_akta')->store('dokumen-pindahan', 'local');
        $validated['file_rapor'] = $request->file('file_rapor')->store('dokumen-pindahan', 'local');
        $validated['file_rekomendasi_dinas'] = $request->file('file_rekomendasi_dinas')?->store('dokumen-pindahan', 'local');

        PendaftaranPindahan::query()->create($validated);

        return redirect()->route('pendaftaran-pindahan.public.create')
            ->with('success', 'Pengajuan berhasil dikirim. Simpan nomor pendaftaran Anda: '.$validated['no_pendaftaran']);
    }
}
