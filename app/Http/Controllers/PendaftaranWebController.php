<?php

namespace App\Http\Controllers;

use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use App\Models\User;
use App\Notifications\AdminNewApplicationNotification;
use App\Services\FonnteWhatsAppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PendaftaranWebController extends Controller
{
    public function create(): View
    {
        $registrationPeriod = RegistrationPeriod::query()->first();
        $registrationOpen = $registrationPeriod?->acceptsRegistrationsAt(now('Asia/Jakarta')) ?? false;

        return view('pages.public.registrasi.ppdb', compact('registrationPeriod', 'registrationOpen'));
    }

    public function lookupStudent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nisn' => ['required', 'digits:10'],
        ]);

        $tahunAjaran = RegistrationPeriod::query()->value('tahun_ajaran');
        $siswa = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('status_diterima', true)
            ->where('nisn', $validated['nisn'])
            ->with('daftarUlang:id,id_siswa_ppdb,status')
            ->first(['id', 'nama_siswa']);

        if (! $siswa) {
            return response()->json(['message' => 'NISN belum terdaftar sebagai siswa yang diterima.'], 404);
        }

        $statusPendaftaran = $siswa->daftarUlang?->status;

        return response()->json([
            'nama_siswa' => $siswa->nama_siswa,
            'message' => match ($statusPendaftaran) {
                'Ditolak' => 'Pengajuan sebelumnya ditolak. Anda dapat mengirim ulang formulir selama masa pendaftaran masih dibuka.',
                'Perlu Perbaikan' => 'Berkas perlu diperbaiki. Silakan perbarui data dan kirim ulang formulir selama masa pendaftaran masih dibuka.',
                'Diterima' => 'Pendaftaran siswa ini sudah diterima.',
                'Menunggu Verifikasi' => 'Pendaftaran siswa ini masih menunggu verifikasi.',
                default => 'Nama siswa berhasil ditemukan.',
            },
        ]);
    }

    public function publicPpdbPdf(): View
    {
        $tahunAjaran = RegistrationPeriod::query()->value('tahun_ajaran');
        $rows = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->where('status_diterima', true)
            ->when($tahunAjaran, fn ($query) => $query->where('tahun_ajaran', $tahunAjaran))
            ->orderBy('nama_siswa')
            ->get(['nama_siswa', 'jenis_kelamin'])
            ->values()
            ->map(fn (SiswaPpdb $student, int $index): array => [
                $index + 1,
                $student->nama_siswa,
                $student->jenis_kelamin ?: '-',
            ])
            ->all();

        return view('pages.admin.exports.print', [
            'title' => 'Daftar Siswa Diterima PPDB'.($tahunAjaran ? ' '.$tahunAjaran : ''),
            'columns' => ['No.', 'Nama Siswa', 'Jenis Kelamin'],
            'rows' => $rows,
            'printedAt' => now('Asia/Jakarta')->format('d/m/Y H:i').' WIB',
        ]);
    }

    public function store(Request $request, FonnteWhatsAppService $whatsapp): RedirectResponse
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
                ->with('registration_notice', 'NISN atau nama siswa belum terdaftar pada daftar siswa yang diterima. Periksa kembali data yang diisi.')
                ->withInput();
        }

        $daftarUlangLama = $siswa->daftarUlang;
        if ($daftarUlangLama && ! in_array($daftarUlangLama->status, ['Perlu Perbaikan', 'Ditolak'], true)) {
            return back()
                ->withErrors(['nisn' => 'Siswa sudah melakukan daftar ulang dan pengajuan tidak dapat dikirim kembali saat ini.'])
                ->with('registration_notice', 'Pendaftaran gagal dikirim karena siswa sudah melakukan daftar ulang dan statusnya masih diproses. Silakan tunggu verifikasi admin.')
                ->withInput();
        }

        $aktaPath = $request->hasFile('file_akta') ? $request->file('file_akta')->store('dokumen_ppdb', 'public') : null;
        $kkPath = $request->hasFile('file_kk') ? $request->file('file_kk')->store('dokumen_ppdb', 'public') : null;

        $data = [
            'nama_wali' => $validated['nama_wali'],
            'no_whatsapp' => $validated['no_whatsapp'],
            'file_akta' => $aktaPath,
            'file_kk' => $kkPath,
            'status' => 'Menunggu Verifikasi',
            'catatan_admin' => null,
        ];

        if ($daftarUlangLama) {
            Storage::disk('public')->delete(array_filter([$daftarUlangLama->file_akta, $daftarUlangLama->file_kk]));
            $daftarUlangLama->update($data);
            $daftarUlang = $daftarUlangLama->refresh();
        } else {
            $daftarUlang = $siswa->daftarUlang()->create($data);
        }

        Notification::send(
            User::query()->where('role', 'admin')->get(),
            new AdminNewApplicationNotification('daftar_ulang', $siswa->nama_siswa, $siswa->nisn),
        );

        $whatsapp->sendAdminNewApplicationAlert('daftar_ulang', $siswa->nama_siswa, $siswa->nisn);
        $whatsapp->sendDaftarUlangSubmitted($daftarUlang);

        return redirect()->back()->with('success', 'Pendaftaran berhasil dikirim! Data Anda sudah masuk ke sistem.');
    }

    // Menampilkan data dan statistik registrasi daftar ulang di Panel Admin
    public function adminIndex(Request $request): View|JsonResponse
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();
        $pendaftars = SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->whereHas('daftarUlang')
            ->with('daftarUlang')
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_siswa', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('no_pendaftaran', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['Menunggu Verifikasi', 'Perlu Perbaikan', 'Diterima', 'Ditolak'], true), function ($query) use ($status): void {
                $query->whereHas('daftarUlang', function ($daftarUlangQuery) use ($status): void {
                    $daftarUlangQuery->where('status', $status);

                    if ($status === 'Menunggu Verifikasi') {
                        $daftarUlangQuery->orWhereNull('status');
                    }
                });
            })
            ->latest()
            ->get();

        // Hitung statistik untuk 4 kartu ringkasan
        $totalPendaftar = $pendaftars->count();
        $menungguVerifikasi = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status == 'Menunggu Verifikasi' || ! optional($item->daftarUlang)->status;
        })->count();
        $diterima = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status == 'Diterima';
        })->count();
        $perluPerbaikan = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status === 'Perlu Perbaikan';
        })->count();
        $ditolak = $pendaftars->filter(function ($item) {
            return optional($item->daftarUlang)->status === 'Ditolak';
        })->count();

        if ($request->expectsJson()) {
            return response()->json([
                'rows' => view('pages.admin.data-ppdb.rows', compact('pendaftars', 'search', 'status'))->render(),
                'modals' => view('pages.admin.data-ppdb.modals', compact('pendaftars'))->render(),
                'total' => $totalPendaftar,
                'waiting' => $menungguVerifikasi,
                'accepted' => $diterima,
                'needs_revision' => $perluPerbaikan,
                'rejected' => $ditolak,
            ]);
        }

        return view('pages.admin.data-ppdb.index', compact(
            'pendaftars', 'totalPendaftar', 'menungguVerifikasi', 'diterima', 'perluPerbaikan', 'ditolak', 'search', 'status'
        ));
    }

    // Mengubah status verifikasi pendaftar oleh admin
    public function updateStatus(Request $request, int $id, FonnteWhatsAppService $whatsapp): RedirectResponse
    {
        $siswa = SiswaPpdb::query()->where('dicatat_admin', true)->whereHas('daftarUlang')->findOrFail($id);
        $daftarUlang = $siswa->daftarUlang;

        $validated = $request->validate([
            'status' => ['required', 'in:Diterima,Ditolak,Perlu Perbaikan'],
            'catatan_admin' => ['nullable', 'required_if:status,Ditolak,Perlu Perbaikan', 'string', 'max:2000'],
        ], [
            'catatan_admin.required_if' => 'Catatan admin wajib diisi jika status Ditolak atau Perlu Perbaikan.',
        ]);

        $catatanAdmin = in_array($validated['status'], ['Ditolak', 'Perlu Perbaikan'], true)
            ? trim($validated['catatan_admin'])
            : null;

        if (in_array($validated['status'], ['Ditolak', 'Perlu Perbaikan'], true) && $catatanAdmin === '') {
            throw ValidationException::withMessages([
                'catatan_admin' => 'Catatan admin wajib diisi jika status Ditolak atau Perlu Perbaikan.',
            ]);
        }

        $daftarUlang->update([
            'status' => $validated['status'],
            'catatan_admin' => $catatanAdmin,
        ]);

        $notificationSent = $whatsapp->sendStatusUpdate($daftarUlang);

        return redirect()->back()
            ->with('success', 'Status verifikasi siswa berhasil diperbarui.')
            ->with('whatsapp_notification_sent', $notificationSent);
    }
}
