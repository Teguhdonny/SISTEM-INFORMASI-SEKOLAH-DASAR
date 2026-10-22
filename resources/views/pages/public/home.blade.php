<x-layouts.app>
    <!-- 1. Bahagian Sepanduk Utama (Hero Section) - Direvisi -->
    <!-- Perbaikan 1: Tinggi diturunkan menjadi h-[70vh] (sekitar 70% layar) -->
    <section x-data="{
        activeSlide: 0,
        slides: [
            {
                motto: '&quot;Maju Bersama, Raih Prestasi&quot;',
                desc: 'SDN Pojok 2 Kota Kediri berkomitmen untuk memberikan pendidikan berkualitas, membentuk karakter yang mulia, serta mempersiapkan generasi unggul di masa depan.'
            },
            {
                motto: '&quot;Berakhlak Mulia, Mandiri &amp; Peduli Lingkungan&quot;',
                desc: 'Mewujudkan generasi muda yang berakhlak mulia, mandiri, kreatif berwawasan global dan peduli lingkungan melalui pembelajaran yang interaktif.'
            },
            {
                motto: '&quot;Layanan Informasi &amp; Daftar Ulang Terpadu&quot;',
                desc: 'Kemudahan akses informasi profil sekolah, kegiatan siswa, serta layanan registrasi daftar ulang peserta didik baru secara mudah dan terpercaya.'
            }
        ],
        init() {
            setInterval(() => {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            }, 5000);
        }
    }" class="relative w-full h-[70vh] min-h-[480px] flex items-center justify-center overflow-hidden">
        <!-- Gambar Latar Belakang -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/sekolah.jpg') }}" alt="Siswa SDN Pojok 2" class="w-full h-full object-cover object-center">
            <!-- Perbaikan 2: Gradient putih pekat di kiri, transparan di kanan -->
            <div class="absolute inset-0 bg-gradient-to-r from-white from-30% via-white/80 via-60% to-transparent"></div>
        </div>

        <!-- Kandungan Teks -->
        <div class="relative z-10 w-full px-6 lg:px-16 xl:px-24 mx-auto flex">
            <div class="w-full md:w-3/4 lg:w-3/5 space-y-4" data-aos="fade-right">
                
                <div class="inline-flex items-center gap-2 bg-blue-100/80 text-blue-900 font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm shadow-sm mb-1">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="text-sm">Selamat Datang di</span>
                </div>
                
                <!-- Perbaikan 3: Tipografi "SDN Pojok 2" lebih dominan, "Kota Kediri" sedikit lebih kecil, jarak dirapatkan -->
                <div class="space-y-1">
                    <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight text-blue-900 drop-shadow-sm">
                        SDN Pojok 2
                    </h1>
                    <h2 class="text-3xl lg:text-5xl font-extrabold text-red-600 drop-shadow-sm">
                        Kota Kediri
                    </h2>
                </div>
                
                <!-- Motto didekatkan ke judul -->
                <p class="text-lg lg:text-xl font-medium text-gray-800 italic border-l-4 border-red-500 pl-4 py-1" x-html="slides[activeSlide].motto">
                    "Maju Bersama, Raih Prestasi"
                </p>
                
                <p class="text-gray-800 text-base lg:text-lg leading-relaxed max-w-lg bg-white/40 p-2 rounded backdrop-blur-sm" x-text="slides[activeSlide].desc">
                    SDN Pojok 2 Kota Kediri berkomitmen untuk memberikan pendidikan berkualitas, membentuk karakter yang mulia, serta mempersiapkan generasi unggul di masa depan.
                </p>
                
                <!-- Perbaikan 4: Dua CTA (Utama & Sekunder) -->
                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a href="{{ url('/profil') }}" class="inline-block bg-red-600 text-white font-bold px-8 py-3 rounded-full shadow-lg shadow-red-600/30 hover:bg-red-700 hover:-translate-y-1 transition transform duration-300">
                        Profil Sekolah &rarr;
                    </a>
                    <a href="{{ url('/registrasi-daftar-ulang') }}" class="inline-block border-2 border-blue-700 text-blue-900 font-bold px-8 py-3 rounded-full hover:bg-blue-50 hover:-translate-y-1 transition transform duration-300 bg-white/50 backdrop-blur-sm">
                        Informasi Daftar Ulang
                    </a>
                </div>
            </div>
        </div>

        <!-- Perbaikan 5: Indikator Slider di bawah -->
        <div class="absolute bottom-6 left-6 lg:left-16 xl:left-24 flex gap-2 z-20">
            <button type="button" @click="activeSlide = 0" :class="activeSlide === 0 ? 'w-8 bg-blue-700' : 'w-2.5 bg-gray-400 hover:bg-blue-400'" class="h-2.5 rounded-full transition-all"></button>
            <button type="button" @click="activeSlide = 1" :class="activeSlide === 1 ? 'w-8 bg-blue-700' : 'w-2.5 bg-gray-400 hover:bg-blue-400'" class="h-2.5 rounded-full transition-all"></button>
            <button type="button" @click="activeSlide = 2" :class="activeSlide === 2 ? 'w-8 bg-blue-700' : 'w-2.5 bg-gray-400 hover:bg-blue-400'" class="h-2.5 rounded-full transition-all"></button>
        </div>
    </section>

    <!-- 2. Bahagian Ucapan & Kad Maklumat (Dilaraskan Jaraknya) -->
    <section class="w-full px-6 lg:px-16 xl:px-24 mx-auto py-10 bg-gray-50/50">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sambutan Kepala Sekolah (Sudah Responsif) -->
            <div class="w-full lg:w-2/5 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6" data-aos="fade-up">
                <!-- Foto: Lebar penuh di HP, 1/3 di Desktop -->
                <div class="w-full md:w-1/3 shrink-0">
                    <div class="w-full h-48 md:h-full min-h-[160px] bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center text-gray-400 text-xs">
                        [Foto Kepsek]
                    </div>
                </div>
                
                <!-- Teks: Lebar penuh di HP, 2/3 di Desktop -->
                <div class="w-full md:w-2/3 flex flex-col justify-center">
                    <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fas fa-user-tie"></i> Sambutan Kepala Sekolah
                    </h3>
                    <h4 class="text-lg font-bold text-blue-950 leading-tight mb-2">Assalamu'alaikum Warahmatullahi Wabarakatuh</h4>
                    <p class="text-gray-600 text-sm md:text-xs leading-relaxed mb-4 line-clamp-4">
                        Kami ucapkan selamat datang di website resmi SDN Pojok 2 Kota Kediri. Semoga kehadiran website ini dapat menjadi jembatan informasi dan komunikasi antara sekolah, siswa, orang tua, dan masyarakat.
                    </p>
                    <div class="mt-auto">
                        <p class="font-bold text-sm text-blue-900">H. Ahmad Fauzi, S.Pd</p>
                        <p class="text-xs text-gray-500">Kepala SDN Pojok 2 Kota Kediri</p>
                    </div>
                </div>
            </div>

            <!-- Grid Kad Maklumat (Info Cards) -->
            <div class="w-full lg:w-3/5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kad 1: PPDB -->
                <a href="{{ url('/registrasi-daftar-ulang') }}" class="group bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-blue-900 mb-1">Informasi PPDB</h4>
                        <p class="text-xs text-gray-500 line-clamp-2">Lihat informasi penerimaan peserta didik baru di sini.</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 group-hover:bg-blue-100">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </a>

                <!-- Kad 2: Ekstrakurikuler -->
                <a href="{{ route('ekstrakurikuler.public.index') }}" class="group bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-green-600 group-hover:text-white transition">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-green-900 mb-1">Kegiatan Ekstrakurikuler</h4>
                        <p class="text-xs text-gray-500 line-clamp-2">Temukan berbagai kegiatan ekstrakurikuler yang tersedia.</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0 group-hover:bg-green-100">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </a>

                <!-- Kad 3: Jadwal -->
                <a href="{{ url('/jadwal-pelajaran') }}" class="group bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-yellow-500 group-hover:text-white transition">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-yellow-900 mb-1">Jadwal Pelajaran</h4>
                        <p class="text-xs text-gray-500 line-clamp-2">Lihat jadwal pelajaran kelas setiap hari.</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center shrink-0 group-hover:bg-yellow-100">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </a>

                <!-- Kad 4: Program Sekolah -->
                <a href="{{ route('program.public.index') }}" class="group bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl shrink-0 group-hover:bg-purple-600 group-hover:text-white transition">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-purple-900 mb-1">Program Sekolah</h4>
                        <p class="text-xs text-gray-500 line-clamp-2">Kenali program unggulan sekolah kami.</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center shrink-0 group-hover:bg-purple-100">
                        <i class="fas fa-arrow-right text-sm"></i>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- 3. Bahagian Berita & Pengumuman (Dilengkapi Modal Baca Berita) -->
    <section x-data="{ 
        modalBerita: false, 
        beritaAktif: { kategori: '', warna: '', tanggal: '', judul: '', isi: '', link: '' },
        bukaBerita(item) {
            this.beritaAktif = item;
            this.modalBerita = true;
        }
    }" class="w-full px-6 lg:px-16 xl:px-24 mx-auto py-12">
        
        <div class="flex justify-between items-end mb-8 border-b pb-4">
            <h2 class="text-2xl font-bold text-blue-900 flex items-center gap-2">
                <i class="fas fa-bullhorn text-blue-600"></i> Berita & Pengumuman Terbaru
            </h2>
            <a href="{{ url('/profil') }}" class="text-sm font-semibold text-blue-600 hover:text-red-600 transition">Lihat Semua &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Item Berita 1 -->
            <div @click="bukaBerita({
                    kategori: 'Pengumuman',
                    tanggal: '20 Agustus 2026',
                    judul: 'Upacara HUT RI ke-81 di SDN Pojok 2 Kota Kediri',
                    isi: 'Seluruh warga SDN Pojok 2 Kota Kediri mengikuti upacara peringatan Hari Ulang Tahun Kemerdekaan Republik Indonesia dengan khidmat. Kegiatan dilanjutkan dengan berbagai perlombaan edukatif dan kreatif antar kelas untuk menumbuhkan semangat nasionalisme serta kebersamaan siswa.',
                    link: '{{ url('/profil') }}'
                })" 
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition cursor-pointer" data-aos="fade-up" data-aos-delay="100">
                <div class="h-40 bg-gradient-to-br from-red-50 to-gray-200 overflow-hidden relative flex items-center justify-center">
                    <img src="{{ asset('images/sekolah.jpg') }}" onerror="this.style.display='none'" alt="Upacara HUT RI" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <i class="fas fa-flag text-4xl text-red-300 absolute pointer-events-none opacity-40"></i>
                    <span class="absolute top-2 left-2 bg-red-100 text-red-600 text-[10px] font-bold px-2 py-1 rounded z-10">Pengumuman</span>
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-400 mb-2">20 Agustus 2026</p>
                    <h3 class="font-bold text-sm text-blue-900 mb-3 group-hover:text-blue-600 transition">Upacara HUT RI ke-81 di SDN Pojok 2 Kota Kediri</h3>
                    <div class="flex justify-end text-gray-400 group-hover:text-blue-600"><i class="fas fa-arrow-right text-sm"></i></div>
                </div>
            </div>

            <!-- Item Berita 2 -->
            <div @click="bukaBerita({
                    kategori: 'Prestasi',
                    tanggal: '15 Agustus 2026',
                    judul: 'Siswa SDN Pojok 2 Raih Juara 1 Lomba Matematika Tingkat Kota',
                    isi: 'Kebanggaan bagi SDN Pojok 2 Kota Kediri atas prestasi gemilang siswa dalam ajang Olimpiade Matematika Tingkat Kota Kediri. Prestasi ini merupakan wujud nyata dari program Kelas Numerasi dan pembinaan intensif sesuai minat serta bakat peserta didik.',
                    link: '{{ route('program.public.index') }}'
                })" 
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition cursor-pointer" data-aos="fade-up" data-aos-delay="200">
                <div class="h-40 bg-gradient-to-br from-blue-50 to-gray-200 overflow-hidden relative flex items-center justify-center">
                    <i class="fas fa-trophy text-5xl text-blue-400/70 group-hover:scale-110 transition duration-500"></i>
                    <span class="absolute top-2 left-2 bg-blue-100 text-blue-600 text-[10px] font-bold px-2 py-1 rounded z-10">Prestasi</span>
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-400 mb-2">15 Agustus 2026</p>
                    <h3 class="font-bold text-sm text-blue-900 mb-3 group-hover:text-blue-600 transition">Siswa SDN Pojok 2 Raih Juara 1 Lomba Matematika Tingkat Kota</h3>
                    <div class="flex justify-end text-gray-400 group-hover:text-blue-600"><i class="fas fa-arrow-right text-sm"></i></div>
                </div>
            </div>

            <!-- Item Berita 3 -->
            <div @click="bukaBerita({
                    kategori: 'Kegiatan',
                    tanggal: '10 Agustus 2026',
                    judul: 'MPLS Tahun Ajaran 2026/2027 Berjalan Lancar',
                    isi: 'Kegiatan Masa Pengenalan Lingkungan Sekolah (MPLS) bagi peserta didik baru kelas 1 berjalan dengan ceria, aman, dan menyenangkan. Siswa diperkenalkan dengan lingkungan sekolah, bapak/ibu guru, fasilitas belajar, serta pembiasaan karakter positif.',
                    link: '{{ route('ekstrakurikuler.public.index') }}'
                })" 
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition cursor-pointer" data-aos="fade-up" data-aos-delay="300">
                <div class="h-40 bg-gradient-to-br from-green-50 to-gray-200 overflow-hidden relative flex items-center justify-center">
                    <i class="fas fa-users text-5xl text-green-400/70 group-hover:scale-110 transition duration-500"></i>
                    <span class="absolute top-2 left-2 bg-green-100 text-green-600 text-[10px] font-bold px-2 py-1 rounded z-10">Kegiatan</span>
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-400 mb-2">10 Agustus 2026</p>
                    <h3 class="font-bold text-sm text-blue-900 mb-3 group-hover:text-blue-600 transition">MPLS Tahun Ajaran 2026/2027 Berjalan Lancar</h3>
                    <div class="flex justify-end text-gray-400 group-hover:text-blue-600"><i class="fas fa-arrow-right text-sm"></i></div>
                </div>
            </div>

            <!-- Item Berita 4 -->
            <div @click="bukaBerita({
                    kategori: 'Informasi',
                    tanggal: '5 Agustus 2026',
                    judul: 'Daftar Ulang dan Pembagian Kelas Tahun Ajaran 2026/2027',
                    isi: 'Informasi bagi seluruh orang tua/wali calon peserta didik baru bahwa proses registrasi daftar ulang dapat dilakukan secara online melalui menu Registrasi Daftar Ulang dengan mengunggah dokumen Akta Kelahiran dan Kartu Keluarga.',
                    link: '{{ url('/registrasi-daftar-ulang') }}'
                })" 
                class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition cursor-pointer" data-aos="fade-up" data-aos-delay="400">
                <div class="h-40 bg-gradient-to-br from-purple-50 to-gray-200 overflow-hidden relative flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-5xl text-purple-400/70 group-hover:scale-110 transition duration-500"></i>
                    <span class="absolute top-2 left-2 bg-purple-100 text-purple-600 text-[10px] font-bold px-2 py-1 rounded z-10">Informasi</span>
                </div>
                <div class="p-4">
                    <p class="text-xs text-gray-400 mb-2">5 Agustus 2026</p>
                    <h3 class="font-bold text-sm text-blue-900 mb-3 group-hover:text-blue-600 transition">Daftar Ulang dan Pembagian Kelas Tahun Ajaran 2026/2027</h3>
                    <div class="flex justify-end text-gray-400 group-hover:text-blue-600"><i class="fas fa-arrow-right text-sm"></i></div>
                </div>
            </div>
        </div>

        <!-- Modal Pop-up Detail Berita -->
        <div x-show="modalBerita" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition.opacity>
            <div @click.away="modalBerita = false" class="bg-white rounded-2xl max-w-lg w-full p-6 md:p-8 shadow-2xl relative">
                <button @click="modalBerita = false" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
                <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full mb-3" x-text="beritaAktif.kategori"></span>
                <p class="text-xs text-gray-400 mb-1" x-text="beritaAktif.tanggal"></p>
                <h3 class="text-xl font-extrabold text-blue-950 mb-4 leading-snug" x-text="beritaAktif.judul"></h3>
                <p class="text-sm text-gray-600 leading-relaxed mb-6" x-text="beritaAktif.isi"></p>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button @click="modalBerita = false" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold">Tutup</button>
                    <a :href="beritaAktif.link" class="px-5 py-2 rounded-xl bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold inline-flex items-center gap-2">
                        <span>Buka Halaman Terkait</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>