<?php

namespace App\Services;

use App\Models\DaftarUlang;
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
