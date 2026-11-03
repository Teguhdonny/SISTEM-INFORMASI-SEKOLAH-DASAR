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
            
            <!-- Sambutan Kepala Sekolah (Dinamis dari Profil Sekolah Admin) -->
            <div class="w-full lg:w-2/5 bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col md:flex-row gap-6" data-aos="fade-up">
                <!-- Foto Kepala Sekolah -->
                <div class="w-full md:w-1/3 shrink-0">
                    @if(!empty($profilSekolah?->foto_kepsek))
                        <img src="{{ asset('storage/' . $profilSekolah->foto_kepsek) }}" 
                             alt="{{ $profilSekolah->nama_kepsek ?? 'Kepala Sekolah' }}" 
                             class="w-full h-48 md:h-full min-h-[160px] object-cover rounded-xl shadow-sm border border-gray-100">
                    @else
                        <div class="w-full h-48 md:h-full min-h-[160px] bg-blue-50 text-blue-300 rounded-xl overflow-hidden flex flex-col items-center justify-center border border-blue-100 text-xs gap-2">
                            <i class="fas fa-user-tie text-4xl text-blue-400"></i>
                            <span class="text-[11px] text-blue-700 font-semibold">Foto Kepsek</span>
                        </div>
                    @endif
                </div>
                
                <!-- Teks Sambutan -->
                <div class="w-full md:w-2/3 flex flex-col justify-center">
                    <h3 class="text-sm font-bold text-blue-800 uppercase tracking-wider mb-2 flex items-center gap-2">
                        <i class="fas fa-user-tie"></i> Sambutan Kepala Sekolah
                    </h3>
                    <h4 class="text-lg font-bold text-blue-950 leading-tight mb-2">Assalamu'alaikum Warahmatullahi Wabarakatuh</h4>
                    <p class="text-gray-600 text-sm md:text-xs leading-relaxed mb-4 line-clamp-4">
                        {{ $profilSekolah?->sambutan_kepsek ?? 'Kami ucapkan selamat datang di website resmi SDN Pojok 2 Kota Kediri. Semoga kehadiran website ini dapat menjadi jembatan informasi dan komunikasi antara sekolah, siswa, orang tua, dan masyarakat.' }}
                    </p>
                    <div class="mt-auto">
                        <p class="font-bold text-sm text-blue-900">{{ $profilSekolah?->nama_kepsek ?? 'H. Ahmad Fauzi, S.Pd' }}</p>
                        <p class="text-xs text-gray-500">Kepala {{ $profilSekolah?->nama_sekolah ?? 'SDN Pojok 2 Kota Kediri' }}</p>
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

   <!-- 3. Bahagian Berita & Pengumuman Terbaru (Dinamis dari Admin + Modal Detail) -->
    <section x-data="{ detailModal: false, beritaAktif: null }" class="w-full px-6 lg:px-16 xl:px-24 mx-auto py-12">
        <div class="flex justify-between items-end mb-8 border-b pb-4">
            <h2 class="text-2xl font-bold text-blue-900 flex items-center gap-2">
                <i class="fas fa-bullhorn text-blue-600"></i> Berita & Pengumuman Terbaru
            </h2>
            <a href="{{ route('program.public.index') }}" class="text-sm font-semibold text-blue-600 hover:text-red-600 transition">Lihat Semua &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($beritas ?? [] as $berita)
                @php
                    $badgeStyle = [
                        'Pengumuman' => 'bg-red-100 text-red-600',
                        'Prestasi' => 'bg-blue-100 text-blue-600',
                        'Kegiatan' => 'bg-green-100 text-green-600',
                        'Informasi' => 'bg-purple-100 text-purple-600',
                    ][$berita->kategori] ?? 'bg-blue-100 text-blue-600';

                    $placeholderIcon = [
                        'Pengumuman' => 'fa-bullhorn text-red-300 bg-red-50',
                        'Prestasi' => 'fa-trophy text-blue-300 bg-blue-50',
                        'Kegiatan' => 'fa-users text-emerald-300 bg-emerald-50',
                        'Informasi' => 'fa-clipboard-check text-purple-300 bg-purple-50',
                    ][$berita->kategori] ?? 'fa-newspaper text-blue-300 bg-blue-50';
                @endphp

                <div @click="beritaAktif = {
                        judul: @js($berita->judul),
                        kategori: '{{ $berita->kategori }}',
                        tanggal: '{{ $berita->tanggal->translatedFormat('d F Y') }}',
                        isi: @js($berita->isi),
                        gambar: '{{ $berita->gambar ? asset('storage/' . $berita->gambar) : '' }}'
                     }; detailModal = true"
                     class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-lg transition cursor-pointer flex flex-col">
                    
                    <div class="h-44 overflow-hidden relative flex items-center justify-center {{ $placeholderIcon }}">
                        <span class="absolute top-2.5 left-2.5 {{ $badgeStyle }} text-[10px] font-bold px-2.5 py-1 rounded z-10 shadow-sm">
                            {{ $berita->kategori }}
                        </span>
                        @if($berita->gambar)
                            <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <i class="fas {{ explode(' ', $placeholderIcon)[0] }} text-5xl"></i>
                        @endif
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <p class="text-xs text-gray-400 mb-2">{{ $berita->tanggal->translatedFormat('d F Y') }}</p>
                        <h3 class="font-bold text-sm text-blue-900 mb-3 group-hover:text-blue-600 transition line-clamp-2">{{ $berita->judul }}</h3>
                        <div class="mt-auto flex justify-end text-gray-400 group-hover:text-blue-600">
                            <i class="fas fa-arrow-right text-sm"></i>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-8 text-sm text-gray-500">
                    Belum ada berita atau pengumuman terbaru.
                </div>
            @endforelse
        </div>

        <!-- POPUP MODAL BACA DETAIL BERITA DI BERANDA -->
        <div x-show="detailModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
            <div @click.away="detailModal = false" class="bg-white rounded-3xl shadow-2xl max-w-xl w-full max-h-[90vh] overflow-y-auto relative">
                <button type="button" @click="detailModal = false" class="absolute top-4 right-4 z-10 w-9 h-9 bg-white/90 text-gray-700 hover:bg-white rounded-full flex items-center justify-center shadow">
                    <i class="fas fa-times"></i>
                </button>
                <template x-if="beritaAktif">
                    <div>
                        <template x-if="beritaAktif.gambar">
                            <img :src="beritaAktif.gambar" :alt="beritaAktif.judul" class="w-full h-64 object-cover">
                        </template>
                        <div class="p-6 sm:p-8 space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 bg-blue-50 text-blue-700 font-bold text-xs rounded-lg" x-text="beritaAktif.kategori"></span>
                                <span class="text-xs text-gray-400" x-text="beritaAktif.tanggal"></span>
                            </div>
                            <h3 class="text-xl font-extrabold text-blue-950 leading-snug" x-text="beritaAktif.judul"></h3>
                            <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line pt-2" x-text="beritaAktif.isi"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>
</x-layouts.app>