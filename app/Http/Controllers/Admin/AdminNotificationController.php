<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function feed(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn ($notification): array => [
                'id' => $notification->id,
                'title' => $notification->data['title'] ?? 'Pengajuan baru',
                'message' => $notification->data['message'] ?? '',
                'created_at' => $notification->created_at?->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
                'unread' => $notification->read_at === null,
                'open_url' => route('admin.notifications.open', $notification->id),
            ])
            ->values();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications,
        ]);
    }

    public function open(Request $request, string $notificationId): RedirectResponse
    {
        $notification = $request->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        $reviewSearch = $notification->data['review_search'] ?? '';

        return match ($notification->data['application_type'] ?? null) {
            'daftar_ulang' => redirect()->route('admin.pendaftaran.index', ['search' => $reviewSearch]),
            'murid_pindahan' => redirect()->route('admin.pendaftaran-pindahan.index', ['search' => $reviewSearch]),
            default => redirect()->route('admin.dashboard'),
        };
    }
}
