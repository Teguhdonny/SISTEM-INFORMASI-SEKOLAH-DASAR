<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SDN Pojok 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Alpine.js untuk interaksi sidebar/dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Kiri -->
        <aside class="bg-[#1e3a8a] text-white flex flex-col transition-all duration-300" 
               :class="sidebarOpen ? 'w-64' : 'w-20'">
            
            <!-- Logo Area -->
            <div class="h-16 flex items-center px-4 bg-[#172554] shrink-0 border-b border-blue-800">
                <div class="w-8 h-8 bg-white text-blue-900 rounded flex items-center justify-center shrink-0">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="ml-3 font-bold truncate transition-opacity duration-300" x-show="sidebarOpen">
                    <p class="text-sm leading-tight">SDN POJOK 2</p>
                    <p class="text-[10px] text-blue-300 font-normal">Kota Kediri</p>
                </div>
            </div>

            <!-- Menu Navigasi -->
            <nav class="flex-1 overflow-y-auto py-4 space-y-1 custom-scrollbar">
                <a href="/admin/dashboard" class="flex items-center px-4 py-2.5 mx-2 rounded hover:bg-blue-800 transition {{ request()->is('admin/dashboard') ? 'bg-blue-600' : '' }}">
                    <i class="fas fa-home w-5 text-center shrink-0"></i>
                    <span class="ml-3 text-sm" x-show="sidebarOpen">Dashboard</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded hover:bg-blue-800 transition">
                    <i class="fas fa-building w-5 text-center shrink-0"></i>
                    <span class="ml-3 text-sm" x-show="sidebarOpen">Profil Sekolah</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded hover:bg-blue-800 transition">
                    <i class="fas fa-users w-5 text-center shrink-0"></i>
                    <span class="ml-3 text-sm" x-show="sidebarOpen">Guru & Staff</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded hover:bg-blue-800 transition">
                    <i class="fas fa-clipboard-list w-5 text-center shrink-0"></i>
                    <span class="ml-3 text-sm" x-show="sidebarOpen">Program Sekolah</span>
                </a>
                <!-- Tambahkan menu lain sesuai desain di sini -->
            </nav>

            <!-- Logout Bottom -->
            <div class="p-4 border-t border-blue-800">
                <a href="/" class="flex items-center px-4 py-2 text-red-300 hover:text-red-100 hover:bg-blue-800 rounded transition">
                    <i class="fas fa-sign-out-alt w-5 text-center shrink-0"></i>
                    <span class="ml-3 text-sm" x-show="sidebarOpen">Logout</span>
                </a>
            </div>
        </aside>

        <!-- Area Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
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

            <!-- Main Content Slot -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

</body>
</html>