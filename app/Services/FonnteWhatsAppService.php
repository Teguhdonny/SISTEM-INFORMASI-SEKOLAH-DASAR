<?php

namespace App\Services;

use App\Models\Berita;
use App\Models\DaftarUlang;
use App\Models\PendaftaranPindahan;
use App\Models\ProfilSekolah;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FonnteWhatsAppService
{
    public function sendAdminNewApplicationAlert(string $applicationType, string $studentName, string $reference): bool
    {
        [$applicationLabel, $reviewUrl] = match ($applicationType) {
            'daftar_ulang' => ['daftar ulang', route('admin.pendaftaran.index')],
            'murid_pindahan' => ['murid pindahan', route('admin.pendaftaran-pindahan.index')],
            default => throw new \InvalidArgumentException('Jenis pengajuan tidak valid.'),
        };

        return $this->sendAdminMessage(implode("\n", [
            'Pendaftaran baru menunggu verifikasi.',
            "Jenis: {$applicationLabel}",
            "Nama siswa: {$studentName}",
            "Nomor referensi: {$reference}",
            '',
            "Periksa pengajuan: {$reviewUrl}",
            'SDN Pojok 2 Kota Kediri',
        ]));
    }

    public function sendAdminPendingApplicationReminder(string $message): bool
    {
        return $this->sendAdminMessage($message);
    }

    public function sendDaftarUlangSubmitted(DaftarUlang $daftarUlang): bool
    {
        $daftarUlang->loadMissing('siswaPpdb');

        return $this->send(
            $daftarUlang->no_whatsapp,
            implode("\n", [
                "Halo {$daftarUlang->nama_wali},",
                '',
                "Data daftar ulang untuk siswa {$daftarUlang->siswaPpdb?->nama_siswa} sudah kami terima.",
                'Status: Menunggu Verifikasi.',
                '',
                'Mohon menunggu proses verifikasi dari pihak sekolah.',
                '',
                'SDN Pojok 2 Kota Kediri',
            ])
        );
    }

    public function sendStatusUpdate(DaftarUlang $daftarUlang): bool
    {
        $daftarUlang->loadMissing('siswaPpdb');

        $statusMessage = match ($daftarUlang->status) {
            'Diterima' => 'Data daftar ulang telah disetujui oleh pihak sekolah.',
            'Ditolak' => 'Data daftar ulang belum dapat diterima oleh pihak sekolah.',
            'Perlu Perbaikan' => 'Silakan perbaiki berkas sesuai catatan admin, lalu kirim ulang melalui formulir daftar ulang.',
            default => 'Data daftar ulang sedang dalam proses verifikasi.',
        };

        return $this->send(
            $daftarUlang->no_whatsapp,
            implode("\n", [
                "Halo {$daftarUlang->nama_wali},",
                '',
                "Pembaruan status daftar ulang siswa {$daftarUlang->siswaPpdb?->nama_siswa}:",
                "Status: {$daftarUlang->status}",
                $statusMessage,
                $daftarUlang->catatan_admin ? "Catatan admin: {$daftarUlang->catatan_admin}" : '',
                '',
                'SDN Pojok 2 Kota Kediri',
            ])
        );
    }

    public function sendNewsAnnouncement(Berita $berita): int
    {
        $sent = 0;
        $url = route('home').'#berita';

        DaftarUlang::query()
            ->whereNotNull('no_whatsapp')
            ->whereIn('status', ['Menunggu Verifikasi', 'Perlu Perbaikan', 'Diterima'])
            ->get(['no_whatsapp'])
            ->each(function (DaftarUlang $daftarUlang) use ($berita, $url, &$sent): void {
                if ($this->send($daftarUlang->no_whatsapp, implode("\n", [
                    'Halo, ada berita baru dari SDN Pojok 2 Kota Kediri.',
                    '',
                    $berita->judul,
                    $url,
                    '',
                    'SDN Pojok 2 Kota Kediri',
                ]))) {
                    $sent++;
                }
            });

        return $sent;
    }

    public function sendPendaftaranPindahanStatusUpdate(
        PendaftaranPindahan $pendaftaranPindahan,
        string $status,
        ?string $catatanAdmin = null,
    ): bool {
        $statusMessage = match ($status) {
            'Diterima' => 'Pengajuan murid pindahan telah diterima oleh pihak sekolah.',
            'Ditolak' => 'Pengajuan murid pindahan belum dapat diterima oleh pihak sekolah. Silakan hubungi sekolah untuk informasi lebih lanjut.',
            default => throw new \InvalidArgumentException('Status pendaftaran pindahan tidak valid.'),
        };

        $message = implode("\n", [
            "Halo {$pendaftaranPindahan->nama_wali},",
            '',
            "Status pengajuan murid pindahan untuk {$pendaftaranPindahan->nama_siswa} ({$pendaftaranPindahan->no_pendaftaran}): {$status}.",
            '',
            $statusMessage,
        ]);

        if ($status === 'Ditolak') {
            $message .= "\n\nCatatan dari admin:\n".(string) $catatanAdmin;
        }

        $message .= "\n\nSDN Pojok 2 Kota Kediri";

        return $this->send($pendaftaranPindahan->no_whatsapp, $message);
    }

    private function send(string $target, string $message): bool
    {
        $token = config('services.fonnte.token');

        if (! config('services.fonnte.enabled') || ! filled($token)) {
            Log::warning('Notifikasi WhatsApp Fonnte dilewati karena belum aktif atau token belum tersedia.');

            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asForm()->timeout(15)->post(config('services.fonnte.url'), [
                'target' => $this->normalizeTarget($target),
                'message' => $message,
                'countryCode' => '62',
                'connectOnly' => false,
            ]);

            $isAccepted = filter_var($response->json('status'), FILTER_VALIDATE_BOOLEAN);

            if (! $response->successful() || ! $isAccepted) {
                Log::warning('Pengiriman notifikasi WhatsApp Fonnte gagal.', [
                    'http_status' => $response->status(),
                    'reason' => $response->json('reason'),
                    'detail' => $response->json('detail'),
                ]);

                return false;
            }

            Log::info('Notifikasi WhatsApp Fonnte masuk antrean pengiriman.', [
                'detail' => $response->json('detail'),
                'process' => $response->json('process'),
                'request_id' => $response->json('requestid'),
                'message_id' => $response->json('id'),
            ]);

            return true;
        } catch (Throwable $exception) {
            Log::error('Terjadi kesalahan saat mengirim notifikasi WhatsApp Fonnte.', [
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    private function sendAdminMessage(string $message): bool
    {
        $target = ProfilSekolah::query()->value('no_whatsapp_admin');

        if (! filled($target)) {
            Log::warning('Notifikasi WhatsApp admin dilewati karena nomor WhatsApp admin belum diatur.');

            return false;
        }

        return $this->send($target, $message);
    }

    private function normalizeTarget(string $target): string
    {
        return preg_replace('/\D+/', '', $target) ?? '';
    }
}
