<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use App\Services\FonnteWhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    // Mengambil semua data pendaftar (untuk ditampilkan di list Android)
    public function index(): JsonResponse
    {
        $tahunAjaran = RegistrationPeriod::query()->value('tahun_ajaran');
        $pendaftaran = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->where('status_diterima', true)
            ->whereNotNull('tahun_ajaran')
            ->where('tahun_ajaran', $tahunAjaran)
            ->orderBy('nama_siswa')
            ->get(['id', 'nama_siswa', 'jenjang', 'tahun_ajaran']);

        return response()->json([
            'success' => true,
            'message' => 'List data pendaftaran',
            'data' => $pendaftaran,
        ], 200);
    }

    // Menambah pendaftaran baru dari aplikasi Android
    public function store(Request $request, FonnteWhatsAppService $whatsapp): JsonResponse
    {
        $registrationPeriod = RegistrationPeriod::query()->first();
        if (! $registrationPeriod?->acceptsRegistrationsAt(now('Asia/Jakarta'))) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi daftar ulang sedang ditutup.',
            ], 403);
        }

        $validated = $request->validate([
            'nisn' => ['required', 'digits:10'],
            'nama_siswa' => ['required', 'string', 'max:150'],
            'nama_wali' => ['required', 'string', 'max:100'],
            'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            'tanggal_verifikasi' => ['nullable', 'date'],
            'jam_verifikasi' => ['nullable', 'string'],
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
            return response()->json([
                'success' => false,
                'message' => 'Data nama dan NISN tidak cocok dengan daftar siswa yang diterima PPDB.',
            ], 422);
        }

        if ($siswa->daftarUlang()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa ini sudah mengirim registrasi daftar ulang.',
            ], 422);
        }

        // Jika ada upload file Akta dari Android
        $fileAktaPath = null;
        if ($request->hasFile('file_akta')) {
            $fileAktaPath = $request->file('file_akta')->store('dokumen_ppdb', 'public');
        }

        // Jika ada upload file KK dari Android
        $fileKkPath = null;
        if ($request->hasFile('file_kk')) {
            $fileKkPath = $request->file('file_kk')->store('dokumen_ppdb', 'public');
        }

        // Simpan data relasi daftar ulang / berkas
        $daftarUlang = $siswa->daftarUlang()->create([
            'nama_wali' => $validated['nama_wali'],
            'no_whatsapp' => $validated['no_whatsapp'],
            'file_akta' => $fileAktaPath,
            'file_kk' => $fileKkPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        $whatsapp->sendDaftarUlangSubmitted($daftarUlang);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dikirim dan tersimpan di database web!',
        ], 200);
    }

    // Melihat detail satu siswa berdasarkan ID
    public function show($id)
    {
        $tahunAjaran = RegistrationPeriod::query()->value('tahun_ajaran');
        $siswa = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->where('status_diterima', true)
            ->whereNotNull('tahun_ajaran')
            ->where('tahun_ajaran', $tahunAjaran)
            ->find($id, ['id', 'nama_siswa', 'jenjang', 'tahun_ajaran']);

        if (! $siswa) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $siswa], 200);
    }

    // Mengubah data pendaftar dari Android
    public function update(Request $request, $id)
    {
        $siswa = SiswaPpdb::find($id);
        if (! $siswa) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'nama_siswa' => ['sometimes', 'required', 'string', 'max:150'],
            'nisn' => ['sometimes', 'required', 'digits:10'],
            'jenjang' => ['sometimes', 'required', 'string'],
            'asal_sekolah' => ['sometimes', 'required', 'string'],
        ]);

        $siswa->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil diperbarui!',
            'data' => $siswa,
        ], 200);
    }

    // Menghapus data pendaftar
    public function destroy($id)
    {
        $siswa = SiswaPpdb::find($id);
        if (! $siswa) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil dihapus!',
        ], 200);
    }

    public function validasi(Request $request, int $id, FonnteWhatsAppService $whatsapp): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Menunggu Verifikasi,Diterima,Ditolak'],
        ]);

        try {
            // 1. GUNAKAN SiswaPpdb (sesuai nama model Anda) dan relasi daftarUlang
            $siswa = SiswaPpdb::query()->where('dicatat_admin', true)->with('daftarUlang')->findOrFail($id);

            // 2. Pastikan relasi daftarUlang ada
            if ($siswa->daftarUlang) {
                $siswa->daftarUlang->update([
                    'status' => $validated['status'],
                ]);
                $whatsapp->sendStatusUpdate($siswa->daftarUlang);

                return response()->json([
                    'success' => true,
                    'message' => 'Status berhasil diperbarui',
                    'data' => $siswa->daftarUlang,
                ]);
            }

            return response()->json(['success' => false, 'message' => 'Data daftar ulang tidak ditemukan'], 404);

        } catch (\Exception $e) {
            // Kirimkan pesan teknisnya ke Android jika error
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: '.$e->getMessage(),
            ], 500);
        }
    }
}
