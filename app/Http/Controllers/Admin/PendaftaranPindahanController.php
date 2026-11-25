<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranPindahan;
use App\Services\FonnteWhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PendaftaranPindahanController extends Controller
{
    public function destroy(PendaftaranPindahan $pendaftaranPindahan): RedirectResponse
    {
        foreach ([
            $pendaftaranPindahan->file_kk,
            $pendaftaranPindahan->file_akta,
            $pendaftaranPindahan->file_rapor,
            $pendaftaranPindahan->file_surat_pindah,
            $pendaftaranPindahan->file_rekomendasi_diterima,
            $pendaftaranPindahan->file_surat_permohonan,
            $pendaftaranPindahan->file_rekomendasi_dinas,
        ] as $path) {
            if ($path) {
                Storage::disk('local')->delete($path);
            }
        }

        $pendaftaranPindahan->delete();

        return redirect()->route('admin.pendaftaran-pindahan.index')->with('success', 'Data murid pindahan berhasil dihapus.');
    }

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();
        $statuses = ['Diterima', 'Ditolak'];
        $filterStatuses = ['Menunggu Verifikasi', 'Perlu Perbaikan', ...$statuses, 'Selesai Daftar'];

        $pendaftaran = PendaftaranPindahan::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($query) use ($search): void {
                    $query->where('nama_siswa', 'like', "%{$search}%")
                        ->orWhere('nisn', 'like', "%{$search}%")
                        ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, $filterStatuses, true), fn ($query) => $query->where('status', $status))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('pages.admin.pendaftaran-pindahan.index', compact('pendaftaran', 'search', 'status', 'statuses', 'filterStatuses'));
    }

    public function updateStatus(
        Request $request,
        PendaftaranPindahan $pendaftaranPindahan,
        FonnteWhatsAppService $whatsapp,
    ): RedirectResponse {
        $redirect = redirect()->route('admin.pendaftaran-pindahan.index');

        if (! in_array($pendaftaranPindahan->status, ['Menunggu Verifikasi', 'Perlu Perbaikan'], true)) {
            return $redirect->with('warning', "Status pengajuan {$pendaftaranPindahan->no_pendaftaran} sudah tersimpan dan tidak dapat diubah.");
        }

        $validated = $request->validate([
            'status' => ['required', 'in:Diterima,Ditolak'],
            'catatan_admin' => ['nullable', 'required_if:status,Ditolak', 'string', 'max:2000'],
        ], [
            'catatan_admin.required_if' => 'Catatan admin wajib diisi jika pengajuan ditolak.',
        ]);

        $catatanAdmin = $validated['status'] === 'Ditolak'
            ? trim($validated['catatan_admin'])
            : null;

        if ($validated['status'] === 'Ditolak' && $catatanAdmin === '') {
            throw ValidationException::withMessages([
                'catatan_admin' => 'Catatan penolakan wajib diisi.',
            ]);
        }

        if (! $whatsapp->sendPendaftaranPindahanStatusUpdate($pendaftaranPindahan, $validated['status'], $catatanAdmin)) {
            return $redirect->with('warning', "Status pengajuan {$pendaftaranPindahan->no_pendaftaran} belum disimpan karena WhatsApp gagal dikirim. Periksa konfigurasi Fonnte, lalu coba lagi.");
        }

        $attributes = ['status' => $validated['status']];
        if ($validated['status'] === 'Ditolak') {
            $attributes['catatan_admin'] = $catatanAdmin;
        }

        $pendaftaranPindahan->update($attributes);

        return $redirect->with('success', "Status {$validated['status']} berhasil dikirim ke WhatsApp wali dan dikunci untuk pengajuan {$pendaftaranPindahan->no_pendaftaran}.");
    }

    public function downloadDocument(PendaftaranPindahan $pendaftaranPindahan, string $document): StreamedResponse
    {
        $path = $this->documentPath($pendaftaranPindahan, $document);

        return Storage::disk('local')->download($path);
    }

    public function previewDocument(PendaftaranPindahan $pendaftaranPindahan, string $document): StreamedResponse
    {
        $path = $this->documentPath($pendaftaranPindahan, $document);
        $mimeType = Storage::disk('local')->mimeType($path);

        abort_unless(in_array($mimeType, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp'], true), 404);

        return Storage::disk('local')->response($path, null, [
            'Content-Type' => $mimeType,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function documentPath(PendaftaranPindahan $pendaftaranPindahan, string $document): string
    {
        $field = match ($document) {
            'kk' => 'file_kk',
            'akta' => 'file_akta',
            'rapor' => 'file_rapor',
            'surat-pindah' => 'file_surat_pindah',
            'rekomendasi-diterima' => 'file_rekomendasi_diterima',
            'surat-permohonan' => 'file_surat_permohonan',
            'rekomendasi-dinas' => 'file_rekomendasi_dinas',
            default => abort(404),
        };
        $path = $pendaftaranPindahan->{$field};

        abort_unless(is_string($path) && Storage::disk('local')->exists($path), 404);

        return $path;
    }
}
