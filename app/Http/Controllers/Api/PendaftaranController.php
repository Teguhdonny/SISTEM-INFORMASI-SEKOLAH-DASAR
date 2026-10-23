<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiswaPpdb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    // Mengambil semua data pendaftar (untuk ditampilkan di list Android)
    public function index(): JsonResponse
    {
        $pendaftaran = SiswaPpdb::with('daftarUlang')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List data pendaftaran',
            'data' => $pendaftaran,
        ], 200);
    }

    // Menambah pendaftaran baru dari aplikasi Android
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari Android
        $request->validate([
            'no_pendaftaran' => 'required',
            'nisn' => 'required|digits:10',
            'nama_siswa' => 'required|string|max:150',
            'jenjang' => 'required|string',
            'asal_sekolah' => 'required|string',
            'tanggal_lahir' => 'nullable|date',
            'nama_wali' => 'required|string',
            'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            'tanggal_verifikasi' => 'nullable|date',
            'jam_verifikasi' => 'nullable|string',
            'file_akta' => 'nullable',
            'file_kk' => 'nullable',
        ]);

        // Simpan data utama siswa
        $siswa = SiswaPpdb::create([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nisn' => $request->nisn,
            'nama_siswa' => $request->nama_siswa,
            'jenjang' => $request->jenjang,
            'asal_sekolah' => $request->asal_sekolah,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status_diterima' => true,
        ]);

        // Jika ada upload file Akta dari Android
        $fileAktaPath = null;
        if ($request->hasFile('file_akta')) {
            $fileAktaPath = $request->file('file_akta')->store('dokumen_ppdb', 'public');
        } elseif ($request->filled('file_akta')) {
            $fileAktaPath = $request->input('file_akta');
        }

        // Jika ada upload file KK dari Android
        $fileKkPath = null;
        if ($request->hasFile('file_kk')) {
            $fileKkPath = $request->file('file_kk')->store('dokumen_ppdb', 'public');
        } elseif ($request->filled('file_kk')) {
            $fileKkPath = $request->input('file_kk');
        }

        // Simpan data relasi daftar ulang / berkas
        $siswa->daftarUlang()->create([
            'nama_wali' => $request->nama_wali ?? '-',
            'no_whatsapp' => $request->no_whatsapp ?? '-',
            'tanggal_verifikasi' => $request->tanggal_verifikasi,
            'jam_verifikasi' => $request->jam_verifikasi,
            'file_akta' => $fileAktaPath,
            'file_kk' => $fileKkPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dikirim dan tersimpan di database web!',
            'data' => $siswa->load('daftarUlang'),
        ], 200);
    }

    // Melihat detail satu siswa berdasarkan ID
    public function show($id)
    {
        $siswa = SiswaPpdb::with('daftarUlang')->find($id);

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

    public function validasi(Request $request, $id)
    {
        try {
            // 1. GUNAKAN SiswaPpdb (sesuai nama model Anda) dan relasi daftarUlang
            $siswa = SiswaPpdb::with('daftarUlang')->findOrFail($id);

            // 2. Pastikan relasi daftarUlang ada
            if ($siswa->daftarUlang) {
                $siswa->daftarUlang->update([
                    'status' => $request->status,
                ]);

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
