<div>
    <!-- If you do not have a consistent goal in life, you can not live it in a consistent way. - Marcus Aurelius -->
</div>
@php
    $adminUser = auth()->user();
    $adminNotifications = $adminUser?->notifications()->latest()->limit(8)->get() ?? collect();
    $adminNotificationItems = $adminNotifications->map(fn ($notification): array => [
        'id' => $notification->id,
        'title' => $notification->data['title'] ?? 'Pengajuan baru',
        'message' => $notification->data['message'] ?? '',
        'created_at' => $notification->created_at?->timezone('Asia/Jakarta')->format('d/m/Y H:i'),
        'unread' => $notification->read_at === null,
        'open_url' => route('admin.notifications.open', $notification->id),
    ])->values();
    $unreadNotificationCount = $adminUser?->unreadNotifications()->count() ?? 0;
@endphp

<div class="relative" x-data="{
    open: false,
    unreadCount: @js($unreadNotificationCount),
    notifications: @js($adminNotificationItems),
    async refresh() {
        try {
            const response = await fetch(@js(route('admin.notifications.feed')), {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (response.ok) {
                const data = await response.json();
                this.unreadCount = data.unread_count;
                this.notifications = data.notifications;
            }
        } catch (error) {
            // Keep the current dropdown data if the network is temporarily unavailable.
        }
    },
}" x-init="window.setInterval(() => refresh(), 15000)" @click.away="open = false">
    <button type="button" @click="open = !open" :aria-expanded="open.toString()" aria-haspopup="true" :aria-label="`Notifikasi, ${unreadCount} belum dibaca`" class="relative rounded-lg p-2 text-gray-500 transition hover:bg-blue-50 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
        <i class="far fa-bell text-xl" aria-hidden="true"></i>
        <span x-cloak x-show="unreadCount > 0" x-text="unreadCount > 9 ? '9+' : unreadCount" class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full border-2 border-white bg-red-500 px-1 text-[10px] font-bold text-white"></span>
    </button>

    <div x-cloak x-show="open" x-transition @keydown.escape.window="open = false" class="absolute right-0 top-full z-50 mt-2 w-80 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl sm:w-96">
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <h2 class="text-sm font-bold text-gray-800">Notifikasi Pengajuan</h2>
            <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700"><span x-text="unreadCount"></span> belum dibaca</span>
        </div>
        <div class="max-h-96 overflow-y-auto">
            <template x-for="notification in notifications" :key="notification.id">
                <form method="POST" :action="notification.open_url">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="flex w-full gap-3 border-b border-gray-50 px-4 py-3 text-left transition hover:bg-blue-50" :class="notification.unread ? 'bg-blue-50/60' : 'bg-white'">
                        <span class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700"><i class="fas fa-file-circle-plus text-xs" aria-hidden="true"></i></span>
                        <span class="min-w-0 flex-1">
                            <span class="block text-sm font-semibold text-gray-800" x-text="notification.title"></span>
                            <span class="mt-0.5 block text-xs leading-relaxed text-gray-600" x-text="notification.message"></span>
                            <span class="mt-1 block text-[11px] text-gray-400" x-text="notification.created_at"></span>
                        </span>
                        <span x-show="notification.unread" class="mt-2 h-2 w-2 shrink-0 rounded-full bg-blue-600" aria-label="Belum dibaca"></span>
                    </button>
                </form>
            </template>
            <div x-show="notifications.length === 0" class="px-5 py-10 text-center">
                <i class="far fa-bell-slash text-2xl text-gray-300" aria-hidden="true"></i>
                <p class="mt-2 text-sm font-semibold text-gray-600">Belum ada notifikasi</p>
                <p class="mt-1 text-xs text-gray-400">Pengajuan baru akan muncul di sini.</p>
            </div>
        </div>
    </div>
</div>
