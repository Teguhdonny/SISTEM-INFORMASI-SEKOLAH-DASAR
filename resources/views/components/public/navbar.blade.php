<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="w-full px-6 lg:px-16 xl:px-24 mx-auto">
        <div class="flex justify-between items-center py-4">
            
            <!-- Bagian Kiri: Logo (Kini bisa diklik untuk ke Beranda) -->
            <a href="/" class="flex items-center gap-3 hover:opacity-80 transition">
                <div class="w-10 h-10 bg-blue-600 text-white flex items-center justify-center rounded">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-blue-900 leading-none">SDN POJOK 2</h1>
                    <p class="text-xs text-gray-500">Kota Kediri</p>
                </div>
            </a>

            <!-- Bagian Tengah: Pautan Menu dengan Deteksi Halaman Aktif -->
            <div class="hidden lg:flex items-center space-x-6 text-sm font-semibold text-gray-600">
                <a href="/" class="{{ request()->is('/') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Beranda</a>
                <a href="/profil" class="{{ request()->is('profil') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Profil Sekolah</a>
                
                <!-- Menu di bawah ini biarkan # dulu karena halamannya belum kita buat -->
                <a href="#" class="hover:text-red-600 transition">Visi & Misi</a>
                <a href="#" class="hover:text-red-600 transition">Daftar Guru</a>
                <a href="#" class="hover:text-red-600 transition">Ekstrakurikuler</a>
                <a href="#" class="hover:text-red-600 transition">Program Sekolah</a>
                <a href="#" class="hover:text-red-600 transition">Registrasi Daftar Ulang</a>
            </div>

            <!-- Bagian Kanan: Carian & Butang Log Masuk -->
            <!-- Bahagian Kanan: Carian & Butang Log Masuk -->
            <div class="flex items-center gap-4">
                <button class="text-gray-500 hover:text-blue-600"><i class="fas fa-search"></i></button>
                <!-- Pastikan href ini menghala ke /login -->
                <a href="/login" class="bg-red-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow hover:bg-red-700 transition shrink-0">
                    <i class="fas fa-user mr-1"></i> Login Admin
                </a>
            </div>
            
        </div>
    </div>
</nav>