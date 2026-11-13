<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class AdminNewApplicationNotification extends Notification
{
    public function __construct(
        public string $applicationType,
        public string $studentName,
        public string $reviewSearch,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        [$title, $applicationLabel] = match ($this->applicationType) {
            'daftar_ulang' => ['Daftar ulang baru', 'daftar ulang'],
            'murid_pindahan' => ['Pengajuan murid pindahan baru', 'murid pindahan'],
            default => ['Pengajuan baru', 'pengajuan'],
        };

        return [
            'application_type' => $this->applicationType,
            'review_search' => $this->reviewSearch,
            'title' => $title,
            'message' => "{$this->studentName} mengirim pengajuan {$applicationLabel} yang perlu diperiksa.",
        ];
    }
}
