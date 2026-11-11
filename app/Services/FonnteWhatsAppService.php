<?php

namespace App\Services;

use App\Models\DaftarUlang;
use App\Models\PendaftaranPindahan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FonnteWhatsAppService
{
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
            'Ditolak' => 'Data daftar ulang belum dapat disetujui. Silakan hubungi pihak sekolah untuk informasi lebih lanjut.',
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
                '',
                'SDN Pojok 2 Kota Kediri',
            ])
        );
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

    private function normalizeTarget(string $target): string
    {
        return preg_replace('/\D+/', '', $target) ?? '';
    }
}
