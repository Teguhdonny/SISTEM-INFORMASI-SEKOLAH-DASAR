<!-- Sidebar Kiri -->
<aside class="bg-[#1e3a8a] text-white flex flex-col transition-all duration-300 relative overflow-hidden" 
       :class="sidebarOpen ? 'w-64' : 'w-20'">
    
    <!-- Logo Area -->
    <div class="h-16 flex items-center px-4 bg-[#172554] shrink-0 border-b border-blue-800 relative z-10">
        <div class="w-8 h-8 bg-white text-blue-900 rounded flex items-center justify-center shrink-0">
            <i class="fas fa-book-open"></i>
        </div>
        <div class="ml-3 font-bold truncate transition-opacity duration-300" x-show="sidebarOpen">
            <p class="text-sm leading-tight">SDN POJOK 2</p>
            <p class="text-[10px] text-blue-300 font-normal">Kota Kediri</p>
        </div>
    </div>

    <!-- Menu Navigasi -->
    <nav class="flex-1 overflow-y-auto py-4 space-y-1 custom-scrollbar relative z-10">
        <a href="/admin/dashboard" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->is('admin/dashboard') ? 'bg-blue-600 shadow-sm' : '' }}">
            <i class="fas fa-home w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Dashboard</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-building w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Profil Sekolah</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-users w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Guru & Staff</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-clipboard-list w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Program Sekolah</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-running w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Ekstrakurikuler</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-newspaper w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Berita / Artikel</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-images w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Galeri Foto</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-object-group w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Slider / Banner</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-user-plus w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">PPDB / Registrasi</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-envelope w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Kontak Masuk</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-user-shield w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Pengguna Admin</span>
        </a>
        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-cog w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Pengaturan Website</span>
        </a>
    </nav>

    <!-- Dekorasi Grafis Merah Putih -->
    <div class="absolute bottom-0 left-0 w-full h-32 pointer-events-none z-0 opacity-90">
        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
            <!-- Bentuk Merah -->
            <polygon points="0,100 100,100 0,30" fill="#dc2626" />
            <!-- Bentuk Putih -->
            <polygon points="0,100 60,100 0,55" fill="#ffffff" />
        </svg>
    </div>

    <!-- Logout Bottom -->
    <div class="p-4 border-t border-blue-800/50 relative z-10">
        <a href="/" class="flex items-center px-4 py-2 text-white hover:bg-black/20 rounded transition font-bold">
            <i class="fas fa-sign-out-alt w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm" x-show="sidebarOpen">Logout</span>
        </a>
    </div>
</aside>