<x-layouts.app>
    <!-- Bagian Header Profil -->
    <section class="bg-blue-50 py-10 border-b">
        <!-- Diubah agar melebar penuh dengan jarak sisi yang aman -->
        <div class="w-full px-6 lg:px-16 xl:px-24 mx-auto" data-aos="fade-right">
            <p class="text-sm text-gray-500 mb-2"><i class="fas fa-home"></i> Beranda &gt; Profil Sekolah</p>
            <h2 class="text-blue-800 font-bold uppercase tracking-wider text-sm mb-1">PROFIL SEKOLAH</h2>
            <h1 class="text-4xl font-extrabold text-red-600 mb-3">SDN Pojok 2 Kota Kediri</h1>
            <p class="text-gray-600 max-w-3xl">Sarana informasi sekolah untuk masyarakat, wali murid, dan calon siswa. Dapatkan informasi profil, program, dan layanan registrasi daftar ulang secara mudah dan terpercaya.</p>
        </div>
    </section>

    <!-- Bagian Konten Utama (Grid Layout) -->
    <!-- Diubah agar melebar penuh mengikuti header -->
    <section class="w-full px-6 lg:px-16 xl:px-24 mx-auto py-10">
        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- Sidebar Kiri -->
            <div class="w-full md:w-1/4 xl:w-1/5" data-aos="fade-up">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                    <a href="#" class="block bg-blue-700 text-white font-bold p-4"><i class="fas fa-school mr-2"></i> Profil Sekolah</a>
                    <a href="#" class="block p-4 text-gray-600 border-b hover:bg-blue-50 hover:text-blue-700"><i class="far fa-file-alt mr-2 w-5"></i> Sejarah Singkat</a>
                    <a href="#" class="block p-4 text-gray-600 border-b hover:bg-blue-50 hover:text-blue-700"><i class="far fa-id-card mr-2 w-5"></i> Identitas Sekolah</a>
                    <a href="#" class="block p-4 text-gray-600 border-b hover:bg-blue-50 hover:text-blue-700"><i class="fas fa-sitemap mr-2 w-5"></i> Struktur Organisasi</a>
                    <a href="#" class="block p-4 text-gray-600 border-b hover:bg-blue-50 hover:text-blue-700"><i class="fas fa-building mr-2 w-5"></i> Fasilitas Sekolah</a>
                    <a href="#" class="block p-4 text-gray-600 hover:bg-blue-50 hover:text-blue-700"><i class="far fa-image mr-2 w-5"></i> Galeri</a>
                </div>
            </div>

            <!-- Konten Kanan -->
            <div class="w-full md:w-3/4 xl:w-4/5 space-y-6" data-aos="fade-left">
                <!-- Kotak 1: Tentang Sekolah -->
                <div class="bg-white p-6 md:p-8 rounded-lg shadow-sm border border-gray-100 flex flex-col xl:flex-row gap-8">
                    <div class="w-full xl:w-1/3 bg-gray-200 rounded-lg h-64 flex items-center justify-center text-gray-400">
                        [Foto Gedung Sekolah]
                    </div>
                    <div class="w-full xl:w-2/3">
                        <h3 class="text-2xl font-bold text-blue-900 mb-3 border-b pb-2">Tentang Sekolah</h3>
                        <p class="text-gray-600 text-base leading-relaxed mb-6">
                            SDN Pojok 2 Kota Kediri adalah sekolah dasar negeri yang berkomitmen untuk memberikan pendidikan berkualitas dengan lingkungan belajar yang aman, nyaman, dan berprestasi.
                        </p>
                        <!-- Kotak Statistik -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-blue-50 p-5 rounded-lg">
                            <div class="text-center border-r border-blue-200">
                                <i class="fas fa-users text-blue-700 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Jumlah Siswa</p>
                                <p class="font-bold text-blue-900 text-lg">&plusmn; 320</p>
                            </div>
                            <div class="text-center border-r border-blue-200">
                                <i class="fas fa-chalkboard-teacher text-blue-700 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Jumlah Guru</p>
                                <p class="font-bold text-blue-900 text-lg">24</p>
                            </div>
                            <div class="text-center border-r border-blue-200">
                                <i class="fas fa-building text-blue-700 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Tahun Berdiri</p>
                                <p class="font-bold text-blue-900 text-lg">1982</p>
                            </div>
                            <div class="text-center">
                                <i class="fas fa-map-marker-alt text-blue-700 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">Status</p>
                                <p class="font-bold text-blue-900 text-lg">Negeri</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kotak 2: Visi, Misi & Nilai Sekolah (Kini Boleh Diklik) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <!-- Kad Visi (Diubah menjadi tag <a>) -->
                    <a href="/visi-misi" class="block bg-white p-6 rounded-lg shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-bullseye"></i>
                            </div>
                            <h3 class="font-bold text-blue-900">Visi Sekolah</h3>
                        </div>
                        <p class="text-gray-600 text-sm italic">"Terwujudnya peserta didik yang beriman, berilmu, berkarakter, dan berprestasi."</p>
                    </a>

                    <!-- Kad Misi (Diubah menjadi tag <a>) -->
                    <a href="/visi-misi" class="block bg-white p-6 rounded-lg shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-blue-100 text-blue-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-list-ol"></i>
                            </div>
                            <h3 class="font-bold text-blue-900">Misi Sekolah</h3>
                        </div>
                        <ul class="text-gray-600 text-sm space-y-2">
                            <li class="flex gap-2"><span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shrink-0">1</span> Meningkatkan kualitas pembelajaran...</li>
                            <li class="flex gap-2"><span class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shrink-0">2</span> Menumbuhkan sikap religius...</li>
                        </ul>
                    </a>

                    <!-- Kad Nilai-Nilai (Diubah menjadi tag <a>) -->
                    <a href="/visi-misi" class="block bg-white p-6 rounded-lg shadow-sm border border-gray-100 relative overflow-hidden hover:shadow-md hover:-translate-y-1 transition duration-300 cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center">
                                <i class="fas fa-leaf"></i>
                            </div>
                            <h3 class="font-bold text-blue-900">Nilai-Nilai Sekolah</h3>
                        </div>
                        <ul class="text-gray-600 text-sm space-y-2">
                            <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Religius</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Disiplin</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Tanggung Jawab</li>
                            <li class="flex items-center gap-2"><i class="fas fa-check-circle text-green-500"></i> Peduli</li>
                        </ul>
                    </a>

                </div>

            </div>
            
        </div>
    </section>
</x-layouts.app>