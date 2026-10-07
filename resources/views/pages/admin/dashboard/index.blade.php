<x-layouts.admin>
    <div class="space-y-6">
        
        <!-- 1. Welcome Banner -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 flex flex-col md:flex-row justify-between items-center relative overflow-hidden">
            <div class="relative z-10 w-full md:w-2/3">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-2">
                    👋 Selamat Datang, Ahmad Fauzi!
                </h2>
                <p class="text-gray-500 text-sm">Kelola dan pantau seluruh informasi website SDN Pojok 2 Kota Kediri dengan mudah dan cepat.</p>
                <div class="flex gap-6 mt-5 text-sm font-medium text-gray-500">
                    <span class="flex items-center gap-2"><i class="far fa-calendar-alt text-blue-600"></i> Senin, 28 April 2025</span>
                    <span class="flex items-center gap-2"><i class="far fa-clock text-blue-600"></i> 10:24 WIB</span>
                </div>
            </div>
            <!-- Dekorasi Gambar Kanan -->
            <div class="hidden md:block absolute right-0 top-0 bottom-0 w-1/3 bg-blue-50/50">
                <img src="{{ asset('images/sekolah.jpg') }}" alt="Sekolah" class="w-full h-full object-cover opacity-40 mix-blend-multiply">
                <div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-transparent"></div>
            </div>
        </div>

        <!-- 2. Stat Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Total Guru & Staff</p>
                    <h3 class="text-3xl font-bold text-gray-800">25</h3>
                    <p class="text-green-500 text-xs mt-2 font-medium"><i class="fas fa-arrow-up"></i> 2% dari bulan lalu</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Total Siswa</p>
                    <h3 class="text-3xl font-bold text-gray-800">356</h3>
                    <p class="text-green-500 text-xs mt-2 font-medium"><i class="fas fa-arrow-up"></i> 5% dari bulan lalu</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Total Berita / Artikel</p>
                    <h3 class="text-3xl font-bold text-gray-800">18</h3>
                    <p class="text-green-500 text-xs mt-2 font-medium"><i class="fas fa-arrow-up"></i> 12% dari bulan lalu</p>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl mb-4">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Pendaftar PPDB</p>
                    <h3 class="text-3xl font-bold text-gray-800">42</h3>
                    <p class="text-green-500 text-xs mt-2 font-medium"><i class="fas fa-arrow-up"></i> 18% dari bulan lalu</p>
                </div>
            </div>
        </div>

        <!-- 3. Charts & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Chart Placeholder (2 Columns) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Statistik Pengunjung Website</h3>
                        <p class="text-xs text-gray-500">Jumlah pengunjung dalam 7 hari terakhir</p>
                    </div>
                    <select class="text-sm border-gray-300 rounded-lg text-gray-600 focus:ring-blue-500">
                        <option>21 Apr - 28 Apr 2025</option>
                    </select>
                </div>
                <!-- Area Grafik (Placeholder) -->
                <div class="w-full h-64 bg-gray-50 rounded-xl flex items-center justify-center border border-dashed border-gray-200">
                    <span class="text-gray-400 font-medium"><i class="fas fa-chart-line mr-2"></i> [ Area Grafik Garis Akan Dirender Di Sini ]</span>
                </div>
            </div>

            <!-- Recent Activity (1 Column) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Aktivitas Terbaru</h3>
                    <a href="#" class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua</a>
                </div>
                <div class="space-y-5">
                    <!-- Item 1 -->
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0 mt-1">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">Guru baru ditambahkan</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">Siti Nurhayati, S.Pd telah ditambahkan sebagai guru.</p>
                            <p class="text-[10px] text-gray-400 mt-1">2 jam lalu</p>
                        </div>
                    </div>
                    <!-- Item 2 -->
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 mt-1">
                            <i class="fas fa-image"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">Banner diperbarui</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">Banner utama website telah diperbarui.</p>
                            <p class="text-[10px] text-gray-400 mt-1">4 jam lalu</p>
                        </div>
                    </div>
                    <!-- Item 3 -->
                    <div class="flex gap-4 items-start">
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-1">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">Artikel dipublikasikan</p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-1">Kegiatan Hari Pendidikan Nasional telah dipublikasikan.</p>
                            <p class="text-[10px] text-gray-400 mt-1">6 jam lalu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Quick Menu -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800 text-sm mb-2">Menu Cepat</h3>
                <a href="#" class="text-sm text-blue-600 font-semibold hover:underline">Lihat Semua Menu &rarr;</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                <a href="#" class="flex flex-col items-center justify-center py-4 bg-gray-50 hover:bg-red-50 hover:border-red-100 rounded-xl border border-gray-100 transition group">
                    <div class="w-10 h-10 bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white transition rounded-lg flex items-center justify-center mb-3 shadow-sm"><i class="fas fa-edit"></i></div>
                    <span class="text-xs font-semibold text-gray-600">Tambah Berita</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center py-4 bg-gray-50 hover:bg-blue-50 hover:border-blue-100 rounded-xl border border-gray-100 transition group">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition rounded-lg flex items-center justify-center mb-3 shadow-sm"><i class="fas fa-users"></i></div>
                    <span class="text-xs font-semibold text-gray-600">Kelola Guru</span>
                </a>
                <a href="#" class="flex flex-col items-center justify-center py-4 bg-gray-50 hover:bg-blue-50 hover:border-blue-100 rounded-xl border border-gray-100 transition group">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition rounded-lg flex items-center justify-center mb-3 shadow-sm"><i class="fas fa-clipboard-list"></i></div>
                    <span class="text-xs font-semibold text-gray-600">Kelola Program</span>
                </a>
                <!-- Anda bisa menambahkan menu lain di sini -->
            </div>
        </div>

    </div>
</x-layouts.admin>