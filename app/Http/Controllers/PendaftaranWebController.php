<?php

namespace App\Http\Controllers;

use App\Models\SiswaPpdb;
use App\Models\Setting;
use Illuminate\Http\Request;

class PendaftaranWebController extends Controller
{
    /**
     * Menampilkan halaman formulir pendaftaran PPDB (Sisi Publik)
     */
    public function create()
    {
        $setting = Setting::first();
        $isOpen = $setting ? (bool) $setting->is_registration_open : false;

        return view('pages.public.registrasi.ppdb', compact('isOpen'));
    }

    /**
     * API / AJAX Endpoint untuk mengecek data siswa PPDB secara langsung di form (Opsional & Interaktif)
     */
    public function cekSiswa(Request $request)
    {
        $noPendaftaran = trim($request->query('no_pendaftaran', ''));
        $nisn = trim($request->query('nisn', ''));

        if (!$noPendaftaran && !$nisn) {
            return response()->json([
                'found' => false,
                'message' => 'Masukkan Nomor Pendaftaran atau NISN terlebih dahulu.'
            ], 400);
        }

        $query = SiswaPpdb::query()->where('status_diterima', true);

        if ($noPendaftaran) {
            $query->where('no_pendaftaran', $noPendaftaran);
        }
        if ($nisn) {
            $query->where('nisn', $nisn);
        }

        $siswa = $query->first();

        if (!$siswa) {
            return response()->json([
                'found' => false,
                'message' => 'Data tidak ditemukan pada daftar siswa diterima PPDB.'
            ]);
        }

        if ($siswa->daftarUlang) {
            return response()->json([
                'found' => false,
                'already_registered' => true,
                'message' => 'Siswa atas nama ' . $siswa->nama_siswa . ' sudah melakukan daftar ulang sebelumnya (Status: ' . $siswa->daftarUlang->status . ').'
            ]);
        }

        return response()->json([
            'found' => true,
            'message' => 'Data kelulusan PPDB ditemukan! Silakan ketik Nama Lengkap sesuai data PPDB untuk validasi.',
            'data' => [
                'no_pendaftaran' => $siswa->no_pendaftaran,
                'nisn' => $siswa->nisn,
                'jenjang' => $siswa->jenjang ?? 'Kelas 1',
                'asal_sekolah' => $siswa->asal_sekolah,
                // Berikan petunjuk huruf depan nama agar wali murid yakin namun tetap wajib mengetik nama lengkap untuk validasi
                'nama_hint' => substr($siswa->nama_siswa, 0, 3) . '***'
            ]
        ]);
    }

    /**
     * Menyimpan data registrasi daftar ulang dengan Validasi Nama terhadap Data PPDB (Tugas Doni - Sprint 3)
     */
    public function store(Request $request)
    {
        // 1. Proteksi Backend: Cek apakah pendaftaran sedang dibuka
        $setting = Setting::first();
        if (!$setting || !$setting->is_registration_open) {
            return redirect()->back()->with('error', 'Mohon maaf, registrasi daftar ulang saat ini sedang ditutup.');
        }

        // 2. Validasi Format Input Dasar & Berkas
        $request->validate([
            'no_pendaftaran' => 'required|string|max:50',
            'nisn' => 'required|digits:10',
            'nama_siswa' => 'required|string|max:150',
            'jenjang' => 'required|string',
            'asal_sekolah' => 'nullable|string|max:150',
            'nama_wali' => 'required|string|max:100',
            'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            'file_akta' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_kk' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'no_pendaftaran.required' => 'Nomor Pendaftaran wajib diisi.',
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits' => 'NISN harus berjumlah tepat 10 digit angka.',
            'nama_siswa.required' => 'Nama Lengkap Siswa wajib diisi.',
            'nama_wali.required' => 'Nama Orang Tua / Wali wajib diisi.',
            'no_whatsapp.required' => 'Nomor WhatsApp aktif wajib diisi.',
            'no_whatsapp.regex' => 'Nomor WhatsApp harus berupa angka (8-20 digit).',
            'file_akta.required' => 'Dokumen Akta Kelahiran wajib diunggah.',
            'file_akta.mimes' => 'Format file Akta harus berupa JPG, PNG, atau PDF.',
            'file_akta.max' => 'Ukuran file Akta maksimal 2MB.',
            'file_kk.required' => 'Dokumen Kartu Keluarga (KK) wajib diunggah.',
            'file_kk.mimes' => 'Format file KK harus berupa JPG, PNG, atau PDF.',
            'file_kk.max' => 'Ukuran file KK maksimal 2MB.',
        ]);

        // 3. VALIDASI INTI SPRINT 3: Cek kecocokan Nomor Pendaftaran / NISN di Tabel siswa_ppdb
        $inputNoPendaftaran = trim($request->no_pendaftaran);
        $inputNisn = trim($request->nisn);
        // Normalisasi spasi ganda dan huruf kecil untuk perbandingan nama yang akurat
        $inputNama = strtolower(preg_replace('/\s+/', ' ', trim($request->nama_siswa)));

        $siswaPpdb = SiswaPpdb::where('no_pendaftaran', $inputNoPendaftaran)
            ->where('nisn', $inputNisn)
            ->first();

        // Jika Nomor Pendaftaran & NISN tidak ditemukan di database PPDB
        if (!$siswaPpdb) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['no_pendaftaran' => 'Kombinasi Nomor Pendaftaran dan NISN tidak ditemukan dalam data PPDB sekolah.'])
                ->with('error', 'Validasi Gagal: Nomor Pendaftaran atau NISN tidak terdaftar di data kelulusan PPDB.');
        }

        // 4. VALIDASI NAMA SISWA TERHADAP DATA PPDB
        $namaTerdaftar = strtolower(preg_replace('/\s+/', ' ', trim($siswaPpdb->nama_siswa)));

        if ($inputNama !== $namaTerdaftar) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['nama_siswa' => 'Nama siswa tidak sesuai dengan data kelulusan PPDB untuk Nomor Pendaftaran & NISN tersebut.'])
                ->with('error', 'Validasi Nama Gagal: Nama Lengkap Siswa yang Anda masukkan tidak cocok dengan data kelulusan PPDB.');
        }

        // 5. Cek apakah status_diterima bernilai true (Lulus PPDB)
        if (!$siswaPpdb->status_diterima) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mohon maaf, status peserta didik tersebut tidak tercatat sebagai siswa diterima pada seleksi PPDB.');
        }

        // 6. Cek apakah siswa sudah pernah melakukan daftar ulang sebelumnya
        if ($siswaPpdb->daftarUlang()->exists()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Siswa atas nama "' . $siswaPpdb->nama_siswa . '" sudah melakukan registrasi daftar ulang sebelumnya.');
        }

        // 7. Perbarui asal sekolah / jenjang jika diisi saat daftar ulang
        $siswaPpdb->update([
            'jenjang' => $request->jenjang,
            'asal_sekolah' => $request->filled('asal_sekolah') ? $request->asal_sekolah : $siswaPpdb->asal_sekolah,
        ]);

        // 8. Simpan file dokumen ke storage public
        $aktaPath = $request->file('file_akta')->store('dokumen_ppdb', 'public');
        $kkPath = $request->file('file_kk')->store('dokumen_ppdb', 'public');

        // 9. Simpan data ke tabel daftar_ulang yang berelasi dengan siswa_ppdb
        $siswaPpdb->daftarUlang()->create([
            'nama_wali' => $request->nama_wali,
            'no_whatsapp' => $request->no_whatsapp,
            'file_akta' => $aktaPath,
            'file_kk' => $kkPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        return redirect()->back()->with(
            'success',
            'Registrasi Daftar Ulang atas nama ' . $siswaPpdb->nama_siswa . ' (' . $siswaPpdb->no_pendaftaran . ') berhasil dikirim! Silakan tunggu verifikasi dari Admin Sekolah.'
        );
    }

    /**
     * Menampilkan data dan statistik pendaftar di Panel Admin
     */
    public function adminIndex()
    {
        $pendaftars = SiswaPpdb::with('daftarUlang')->latest()->get();

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

    /**
     * Mengubah status verifikasi pendaftar oleh admin (Diterima/Ditolak)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Verifikasi,Diterima,Ditolak',
        ]);

        $siswa = SiswaPpdb::findOrFail($id);

        if ($siswa->daftarUlang) {
            $siswa->daftarUlang->update(['status' => $request->status]);
        } else {
            $siswa->daftarUlang()->create([
                'nama_wali' => '-',
                'no_whatsapp' => '-',
                'status' => $request->status,
            ]);
        }

        return redirect()->back()->with('success', 'Status verifikasi siswa berhasil diperbarui!');
    }

    /**
     * Mengubah status Buka/Tutup Pendaftaran secara langsung (Toggle On/Off)
     */
    public function toggleStatus()
    {
        $setting = Setting::first() ?? new Setting();
        $setting->is_registration_open = ! (bool) $setting->is_registration_open;
        $setting->save();

        $statusTeks = $setting->is_registration_open ? 'DIBUKA (ON)' : 'DITUTUP (OFF)';

        return redirect()->back()->with('success', "Status registrasi daftar ulang berhasil diubah menjadi: {$statusTeks}!");
    }
}