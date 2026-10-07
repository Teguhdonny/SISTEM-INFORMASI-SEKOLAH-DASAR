<x-layouts.app>
    <!-- 1. Header / Hero Section -->
    <section class="w-full px-6 lg:px-16 xl:px-24 py-12 bg-gradient-to-b from-blue-50/50 to-white relative overflow-hidden">
        <div class="flex flex-col md:flex-row items-center justify-between gap-10 relative z-10">
            <!-- Teks -->
            <div class="w-full md:w-1/2">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-6 font-medium">
                    <a href="/" class="hover:text-blue-600 transition"><i class="fas fa-home"></i> Beranda</a> 
                    <span>></span> 
                    <span class="text-blue-600">Visi & Misi</span>
                </div>
                <h4 class="text-blue-800 font-bold uppercase tracking-widest text-sm mb-2">VISI & MISI</h4>
                <h1 class="text-4xl md:text-5xl font-extrabold text-red-600 mb-6 leading-tight">SDN Pojok 2 Kota Kediri</h1>
                <p class="text-gray-600 leading-relaxed text-lg md:text-xl">
                    Landasan utama dalam mewujudkan sekolah yang unggul, berkarakter, berprestasi, dan berwawasan masa depan.
                </p>
            </div>
            <!-- Gambar -->
            <div class="w-full md:w-1/2">
                <div class="rounded-2xl overflow-hidden shadow-2xl border-8 border-white relative transform hover:-translate-y-2 transition duration-500">
                    <!-- Ganti src dengan gambar sekolah yang sesuai jika ada -->
                    <img src="{{ asset('images/sekolah.jpg') }}" alt="SDN Pojok 2" class="w-full h-auto object-cover bg-gray-200 min-h-[250px]">
                </div>
            </div>
        </div>
    </section>

    <!-- 2. Bagian Visi -->
    <section class="w-full px-6 lg:px-16 xl:px-24 py-8 relative z-20">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-12 flex flex-col md:flex-row gap-8 items-center transform hover:shadow-2xl transition duration-300">
            <!-- Ikon Target -->
            <div class="w-24 h-24 shrink-0 rounded-full bg-blue-50 flex items-center justify-center border-4 border-blue-100 shadow-inner">
                <i class="fas fa-bullseye text-5xl text-blue-600"></i>
            </div>
            <!-- Teks Visi -->
            <div>
                <h2 class="text-2xl font-extrabold text-blue-900 mb-3 uppercase tracking-wider">VISI</h2>
                <p class="text-2xl md:text-3xl font-bold text-blue-800 leading-snug">
                    "Terwujudnya peserta didik yang beriman, berilmu, berkarakter, dan berprestasi."
                </p>
            </div>
        </div>
    </section>

    <!-- 3. Bagian Misi -->
    <section class="w-full px-6 lg:px-16 xl:px-24 pb-20 relative">
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 md:p-14 relative overflow-hidden hover:shadow-2xl transition duration-300">
            
            <!-- Header Misi -->
            <div class="flex items-center gap-5 mb-10 relative z-10">
                <div class="w-16 h-16 shrink-0 rounded-2xl bg-blue-50 flex items-center justify-center border border-blue-100 shadow-sm">
                    <i class="fas fa-clipboard-list text-3xl text-blue-600"></i>
                </div>
                <h2 class="text-3xl font-extrabold text-blue-900 uppercase tracking-wider">MISI</h2>
            </div>
            
            <!-- Grid Daftar Misi (2 Kolom) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 relative z-10">
                <!-- Misi 1 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">1</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Meningkatkan kualitas pembelajaran yang aktif, kreatif, dan menyenangkan.</p>
                </div>
                <!-- Misi 2 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">2</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Menumbuhkan sikap religius dan berakhlak mulia.</p>
                </div>
                <!-- Misi 3 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">3</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Mengembangkan potensi, bakat, dan minat peserta didik.</p>
                </div>
                <!-- Misi 4 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">4</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Mewujudkan lingkungan sekolah yang aman, bersih, dan nyaman.</p>
                </div>
                <!-- Misi 5 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">5</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Meningkatkan kompetensi pendidik dan tenaga kependidikan secara berkelanjutan.</p>
                </div>
                <!-- Misi 6 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">6</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Mengoptimalkan sarana dan prasarana pendidikan yang memadai.</p>
                </div>
                <!-- Misi 7 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">7</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Menjalin kerja sama dengan orang tua, masyarakat, dan pihak terkait.</p>
                </div>
                <!-- Misi 8 -->
                <div class="flex items-start gap-4 group">
                    <div class="w-8 h-8 shrink-0 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-md group-hover:scale-110 group-hover:bg-red-600 transition duration-300 mt-1">8</div>
                    <p class="text-gray-600 leading-relaxed font-medium">Mewujudkan sekolah yang berprestasi di tingkat lokal, regional, maupun nasional.</p>
                </div>
            </div>

            <!-- Dekorasi Grafis Lengkungan di Kanan Bawah (Mirip di Desain) -->
            <div class="absolute bottom-0 right-0 w-64 h-64 pointer-events-none z-0 opacity-80 translate-x-10 translate-y-10">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full transform rotate-12">
                    <path fill="#dc2626" d="M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.3C91.4,-33.5,98.1,-18,97.7,-2.8C97.3,12.4,89.8,27.3,80.1,40.1C70.4,52.9,58.6,63.6,45.1,72.1C31.6,80.6,16.4,86.9,0.7,85.7C-15,84.5,-30,75.8,-42.6,65.9C-55.2,56,-65.4,44.9,-73.2,32.2C-81,19.5,-86.4,5.2,-85.4,-8.7C-84.4,-22.6,-77.1,-36,-67.2,-46.5C-57.3,-57,-44.8,-64.6,-31.8,-72.1C-18.8,-79.6,-5.3,-87,9.3,-88.1C23.9,-89.2,30.5,-83.6,44.7,-76.4Z" transform="translate(100 100) scale(1.1)" />
                    <path fill="#1e3a8a" d="M41.7,-68.4C55.9,-61.2,68.8,-51.1,78.6,-38.3C88.4,-25.5,95.1,-10,94.7,5.2C94.3,20.4,86.8,35.3,77.1,48.1C67.4,60.9,55.6,71.6,42.1,80.1C28.6,88.6,13.4,94.9,-2.3,98.7C-18,102.5,-33,97.8,-45.6,87.9C-58.2,78,-68.4,66.9,-76.2,54.2C-84,41.5,-89.4,27.2,-88.4,13.3C-87.4,-0.6,-80.1,-14,-70.2,-24.5C-60.3,-35,-47.8,-42.6,-34.8,-50.1C-21.8,-57.6,-8.3,-65,6.3,-66.1C20.9,-67.2,27.5,-61.6,41.7,-68.4Z" transform="translate(110 110) scale(0.9)" />
                </svg>
            </div>
        </div>
    </section>
</x-layouts.app>