<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalPelajaranController extends Controller
{
    public function index(Request $request): View
    {
        $kelas = $request->string('kelas', 'Kelas 1')->toString();
        $semester = $request->string('semester')->toString();
        $tahunAjaran = $request->string('tahun_ajaran')->toString();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $jadwal = JadwalPelajaran::query()
            ->with('guru')
            ->where('kelas', $kelas)
            ->when($semester !== '', fn ($query) => $query->where('semester', $semester))
            ->when($tahunAjaran !== '', fn ($query) => $query->where('tahun_ajaran', $tahunAjaran))
            ->when($request->filled('hari'), fn ($query) => $query->where('hari', $request->string('hari')->toString()))
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        return view('pages.public.jadwal', compact('kelas', 'semester', 'tahunAjaran', 'hari', 'jadwal'));
    }
}
