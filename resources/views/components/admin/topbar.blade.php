<!-- Topbar -->
<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 z-10 shrink-0">
    <!-- Kiri: Toggle & Search -->
    <div class="flex items-center gap-4 w-1/2">
        <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-600 focus:outline-none">
            <i class="fas fa-bars text-lg"></i>
        </button>
        <div class="relative w-full max-w-md hidden md:block">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" class="bg-gray-100 text-sm rounded-full w-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cari menu atau informasi...">
        </div>
    </div>

    <!-- Kanan: Notifikasi & Profil -->
    <div class="flex items-center gap-6">
        <button class="relative text-gray-500 hover:text-blue-600">
            <i class="far fa-bell text-xl"></i>
            <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 w-2.5 h-2.5 rounded-full border border-white"></span>
        </button>
        <div class="flex items-center gap-3 border-l pl-6">
            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold">
                AF
            </div>
            <div class="hidden md:block">
                <p class="text-sm font-bold text-gray-700 leading-none">Ahmad Fauzi</p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
            <i class="fas fa-chevron-down text-xs text-gray-400 ml-1"></i>
        </div>
    </div>
</header>