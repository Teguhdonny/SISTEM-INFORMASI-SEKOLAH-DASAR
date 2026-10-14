<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiswaPpdb;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    // 1. READ: Mengambil semua data pendaftar (untuk ditampilkan di list Android)
    public function index(): JsonResponse
    {
        $data = SiswaPpdb::with('daftarUlang')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar data pendaftaran berhasil diambil',
            'data'    => $data,
        ], 200);
    }

    // 2. CREATE: Menambah pendaftaran baru dari aplikasi Android
    public function store(Request $request)
    {
        // Validasi data yang dikirim dari Android
        $request->validate([
            'no_pendaftaran' => 'required|unique:siswa_ppdb,no_pendaftaran',
            'nisn'           => 'required|unique:siswa_ppdb,nisn',
            'nama_siswa'     => 'required|string|max:150',
            'jenjang'        => 'required|string',
            'asal_sekolah'   => 'required|string',
            // File dokumen (opsional atau wajib sesuai kebutuhan)
            'file_kk'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Simpan data utama siswa
        $siswa = SiswaPpdb::create([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nisn'           => $request->nisn,
            'nama_siswa'     => $request->nama_siswa,
            'jenjang'        => $request->jenjang,
            'asal_sekolah'   => $request->asal_sekolah,
            'status_diterima'=> true,
        ]);

        // Jika ada upload file KK dari Android
        $fileKkPath = null;
        if ($request->hasFile('file_kk')) {
            $fileKkPath = $request->file('file_kk')->store('dokumen_ppdb', 'public');
        }

        // Simpan data relasi daftar ulang / berkas
        $siswa->daftarUlang()->create([
            'nama_wali'     => $request->nama_wali ?? '-',
            'no_whatsapp'   => $request->no_whatsapp ?? '-',
            'file_kk'       => $fileKkPath,
            'status'        => 'Menunggu Verifikasi'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil dikirim dan tersimpan di database web!',
            'data'    => $siswa
        ], 201);
    }

    // 3. SHOW: Melihat detail satu siswa berdasarkan ID
    public function show($id)
    {
        $siswa = SiswaPpdb::with('daftarUlang')->find($id);

        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $siswa], 200);
    }

    // 4. UPDATE: Mengubah data pendaftar dari Android
    public function update(Request $request, $id)
    {
        $siswa = SiswaPpdb::find($id);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $siswa->update($request->only(['nama_siswa', 'nisn', 'jenjang', 'asal_sekolah']));

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil diperbarui!',
            'data'    => $siswa
        ], 200);
    }

    // 5. DELETE: Menghapus data pendaftar
    public function destroy($id)
    {
        $siswa = SiswaPpdb::find($id);
        if (!$siswa) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        $siswa->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pendaftaran berhasil dihapus!'
        ], 200);
    }

p
}