<?php

namespace App\Console\Commands;

use App\Models\DaftarUlang;
use App\Models\PendaftaranPindahan;
use App\Services\FonnteWhatsAppService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-admin-pending-application-reminders')]
#[Description('Send a WhatsApp reminder for applications awaiting admin review')]
class SendAdminPendingApplicationReminders extends Command
{
    public function handle(FonnteWhatsAppService $whatsapp): int
    {
        $daftarUlang = DaftarUlang::query()
            ->where(function ($query): void {
                $query->where('status', 'Menunggu Verifikasi')->orWhereNull('status');
            })
            ->with('siswaPpdb:id,nama_siswa,nisn')
            ->get(['id', 'id_siswa_ppdb']);
        $muridPindahan = PendaftaranPindahan::query()
            ->where('status', 'Menunggu Verifikasi')
            ->get(['id', 'nama_siswa', 'no_pendaftaran']);

        if ($daftarUlang->isEmpty() && $muridPindahan->isEmpty()) {
            $this->info('Tidak ada pengajuan baru yang menunggu verifikasi.');

            return self::SUCCESS;
        }

        $messageLines = [
            'Pengingat: ada pengajuan baru yang belum diproses.',
            "Daftar ulang menunggu verifikasi: {$daftarUlang->count()}",
            ...$daftarUlang->take(5)->map(fn (DaftarUlang $item): string => '- '.($item->siswaPpdb?->nama_siswa ?? 'Siswa tanpa nama').' (NISN '.$item->siswaPpdb?->nisn.')')->all(),
            "Murid pindahan menunggu verifikasi: {$muridPindahan->count()}",
            ...$muridPindahan->take(5)->map(fn (PendaftaranPindahan $item): string => '- '.$item->nama_siswa.' ('.$item->no_pendaftaran.')')->all(),
            '',
            'Daftar ulang: '.route('admin.pendaftaran.index'),
            'Murid pindahan: '.route('admin.pendaftaran-pindahan.index'),
            'SDN Pojok 2 Kota Kediri',
        ];

        if (! $whatsapp->sendAdminPendingApplicationReminder(implode("\n", $messageLines))) {
            $this->error('Pengingat WhatsApp admin gagal dikirim. Periksa nomor admin dan konfigurasi Fonnte.');

            return self::FAILURE;
        }

        $this->info('Pengingat WhatsApp untuk pengajuan yang belum diproses berhasil dikirim.');

        return self::SUCCESS;
    }
}
