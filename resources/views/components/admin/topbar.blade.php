<!-- Topbar -->
@php
    $adminUser = auth()->user();
    $adminName = $adminUser?->name ?: ($adminUser?->username ?: 'Admin');
@endphp
<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10 shrink-0">
    <!-- Kiri: Toggle -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-600 focus:outline-none">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>

    <!-- Kanan: Notifikasi & Profil -->
    <div class="flex items-center gap-6">
        <button class="relative text-gray-500 hover:text-blue-600">
            <i class="far fa-bell text-xl"></i>
            <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 w-2.5 h-2.5 rounded-full border border-white"></span>
        </button>
        <div class="relative border-l pl-6" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
            <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true" class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                    <i class="fas fa-user text-sm" aria-hidden="true"></i>
                    <span class="sr-only">{{ $adminName }}</span>
                </div>
                <div class="hidden text-left md:block">
                    <p class="text-sm font-bold text-gray-700 leading-none">{{ $adminName }}</p>
                    <p class="text-xs text-gray-500">{{ ucfirst($adminUser?->role ?? 'Admin') }}</p>
                </div>
                <i class="fas fa-chevron-down text-xs text-gray-400 ml-1 transition-transform" :class="{ 'rotate-180': profileOpen }"></i>
            </button>

            <div x-show="profileOpen" x-transition @keydown.escape.window="profileOpen = false" class="absolute right-0 top-full z-50 mt-2 w-64 rounded-xl border border-gray-100 bg-white p-2 shadow-lg" style="display: none;">
                <div class="border-b border-gray-100 px-3 py-2">
                    <p class="text-sm font-semibold text-gray-800">{{ $adminName }}</p>
                    <p class="text-xs text-gray-500">{{ $adminUser?->username ?? 'Akun admin' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50">
                        <i class="fas fa-right-from-bracket w-4"></i>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
