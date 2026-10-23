<?php

namespace App\Http\Controllers;

use App\Models\SiswaPpdb;
use Illuminate\Http\Request;

class PendaftaranWebController extends Controller
{
    public function create()
    {
        return view('pages.public.registrasi.ppdb');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pendaftaran' => 'required|unique:siswa_ppdb,no_pendaftaran',
            'nisn' => 'required|digits:10|unique:siswa_ppdb,nisn',
            'nama_siswa' => 'required|string|max:150',
            'jenjang' => 'required|string',
            'asal_sekolah' => 'nullable|string',
            'nama_wali' => 'required|string|max:100',
            'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            'file_akta' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'file_kk' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $siswa = SiswaPpdb::create([
            'no_pendaftaran' => $request->no_pendaftaran,
            'nisn' => $request->nisn,
            'nama_siswa' => $request->nama_siswa,
            'jenjang' => $request->jenjang,
            'asal_sekolah' => $request->asal_sekolah ?? '-',
            'status_diterima' => true,
        ]);

        $aktaPath = $request->hasFile('file_akta') ? $request->file('file_akta')->store('dokumen_ppdb', 'public') : null;
        $kkPath = $request->hasFile('file_kk') ? $request->file('file_kk')->store('dokumen_ppdb', 'public') : null;

        $siswa->daftarUlang()->create([
            'nama_wali' => $request->nama_wali,
            'no_whatsapp' => $request->no_whatsapp,
            'file_akta' => $aktaPath,
            'file_kk' => $kkPath,
            'status' => 'Menunggu Verifikasi',
        ]);

        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim! Data Anda sudah masuk ke sistem.');
    }

    // Menampilkan data dan statistik di Panel Admin
    public function adminIndex()
    {
        $pendaftars = SiswaPpdb::with('daftarUlang')->latest()->get();

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
}
