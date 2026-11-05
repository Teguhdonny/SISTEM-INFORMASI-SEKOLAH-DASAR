<aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-blue-950 text-white min-h-screen transition-all duration-300 flex flex-col shrink-0 shadow-xl z-30">
    <!-- Logo & Nama Sekolah -->
    <div class="h-16 flex items-center px-4 border-b border-blue-900/60">
        <div class="w-10 h-10 bg-white text-blue-950 rounded-xl flex items-center justify-center font-extrabold text-sm shrink-0 shadow">
            <i class="fas fa-school text-blue-900 text-lg"></i>
        </div>
        <div class="ml-3 overflow-hidden" x-show="sidebarOpen" x-transition>
            <h1 class="font-extrabold text-sm tracking-wide leading-tight truncate">SDN POJOK 2</h1>
            <p class="text-[11px] text-blue-300 truncate">Kota Kediri</p>
        </div>
    </div>

    <!-- Daftar Menu Navigasi -->
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto text-blue-100">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-home w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Dashboard</span>
        </a>

        <a href="{{ route('admin.profil.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.profil*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-building w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Profil Sekolah</span>
        </a>

        <a href="{{ route('admin.guru.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.guru*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-chalkboard-teacher w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Guru & Staff</span>
        </a>

        <a href="{{ route('admin.program.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.program*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-book-open w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Program Sekolah</span>
        </a>

        <a href="{{ route('admin.ekstrakurikuler.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.ekstrakurikuler*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-running w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Ekstrakurikuler</span>
        </a>

        <a href="{{ route('admin.jadwal.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->is('admin/jadwal-pelajaran*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-calendar-alt w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Jadwal Pelajaran</span>
        </a>

        <a href="{{ route('admin.berita.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.berita*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-newspaper w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Berita / Artikel</span>
        </a>

        <a href="{{ route('admin.galeri.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->routeIs('admin.galeri*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-images w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Galeri Foto</span>
        </a>

        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-object-group w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Slider / Banner</span>
        </a>

        <a href="{{ route('admin.ppdb.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->is('admin/data-ppdb*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-user-graduate w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">PPDB</span>
        </a>

        <a href="{{ route('admin.pendaftaran.index') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->is('admin/pendaftaran*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-user-plus w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Registrasi Daftar Ulang</span>
        </a>

        <a href="{{ route('admin.registration-period.edit') }}" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800 {{ request()->is('admin/jadwal-registrasi*') ? 'bg-blue-600 text-white shadow-sm' : '' }}">
            <i class="fas fa-calendar-days w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Jadwal Registrasi</span>
        </a>

        <a href="#" class="flex items-center px-4 py-2.5 mx-2 rounded transition hover:bg-blue-800">
            <i class="fas fa-envelope w-5 text-center shrink-0"></i>
            <span class="ml-3 text-sm font-medium" x-show="sidebarOpen">Kontak Masuk</span>
        </a>
    </nav>

    <!-- Tombol Logout -->
    <div class="p-4 border-t border-blue-900/60">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 rounded-xl text-red-300 hover:bg-red-600 hover:text-white transition">
                <i class="fas fa-sign-out-alt w-5 text-center shrink-0"></i>
                <span class="ml-3 text-sm font-bold" x-show="sidebarOpen">Logout</span>
            </button>
        </form>
    </div>
</aside>