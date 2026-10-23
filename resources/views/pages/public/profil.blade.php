<x-layouts.app>
    <div x-data="{ 
        activeTab: 'profil',
        searchGaleri: '',
        kategoriGaleri: 'Semua Kategori',
        titles: {
            'profil': 'PROFIL SEKOLAH',
            'sejarah': 'SEJARAH SINGKAT',
            'identitas': 'IDENTITAS SEKOLAH',
            'struktur': 'STRUKTUR ORGANISASI',
            'fasilitas': 'FASILITAS SEKOLAH',
            'galeri': 'GALERI'
        },
        breadcrumbs: {
            'profil': 'Profil Sekolah',
            'sejarah': 'Sejarah Singkat',
            'identitas': 'Identitas Sekolah',
            'struktur': 'Struktur Organisasi',
            'fasilitas': 'Fasilitas Sekolah',
            'galeri': 'Galeri'
        }
    }" class="bg-gray-50/50 min-h-screen pb-20">

        <!-- 1. Header / Banner Atas -->
        <section class="w-full px-6 lg:px-16 xl:px-24 py-10 bg-gradient-to-r from-blue-50/80 via-indigo-50/40 to-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 font-medium">
                    <a href="/" class="hover:text-blue-600 transition flex items-center gap-1">
                        <i class="fas fa-home"></i> Beranda
                    </a>
                    <span>&gt;</span>
                    <span class="text-gray-700 font-semibold" x-text="breadcrumbs[activeTab]">Profil Sekolah</span>
                </div>
                <h4 class="text-blue-800 font-extrabold uppercase tracking-wider text-xs mb-1" x-text="titles[activeTab]">PROFIL SEKOLAH</h4>
                <h1 class="text-3xl md:text-4xl font-extrabold text-red-600 mb-3">{{ $profilSekolah?->nama_sekolah ?? 'SDN Pojok 2 Kota Kediri' }}</h1>
                <p class="text-gray-600 text-sm md:text-base max-w-2xl leading-relaxed">
                    Sarana informasi sekolah untuk masyarakat, wali murid, dan calon siswa. Dapatkan informasi profil, program, dan layanan registrasi daftar ulang secara mudah dan terpercaya.
                </p>
            </div>
        </section>

        <!-- 2. Konten Utama: Sidebar Kiri + Panel Dinamis Kanan -->
        <section class="w-full px-6 lg:px-16 xl:px-24 pt-8">
            <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8 items-start">

                <!-- SIDEBAR KIRI -->
                <div class="w-full lg:w-64 shrink-0 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                    <button @click="activeTab = 'profil'" :class="activeTab === 'profil' ? 'bg-blue-700 text-white font-bold' : 'text-gray-700 hover:bg-blue-50/60 font-medium'" class="w-full flex items-center gap-3.5 px-5 py-4 text-left text-sm transition">
                        <i class="fas fa-school w-5 text-center" :class="activeTab === 'profil' ? 'text-white' : 'text-gray-500'"></i>
                        <span>Profil Sekolah</span>
                    </button>
                    <button @click="activeTab = 'sejarah'" :class="activeTab === 'sejarah' ? 'bg-blue-700 text-white font-bold' : 'text-gray-700 hover:bg-blue-50/60 font-medium'" class="w-full flex items-center gap-3.5 px-5 py-4 text-left text-sm transition">
                        <i class="fas fa-file-alt w-5 text-center" :class="activeTab === 'sejarah' ? 'text-white' : 'text-gray-500'"></i>
                        <span>Sejarah Singkat</span>
                    </button>
                    <button @click="activeTab = 'identitas'" :class="activeTab === 'identitas' ? 'bg-blue-700 text-white font-bold' : 'text-gray-700 hover:bg-blue-50/60 font-medium'" class="w-full flex items-center gap-3.5 px-5 py-4 text-left text-sm transition">
                        <i class="fas fa-id-card w-5 text-center" :class="activeTab === 'identitas' ? 'text-white' : 'text-gray-500'"></i>
                        <span>Identitas Sekolah</span>
                    </button>
                    <button @click="activeTab = 'struktur'" :class="activeTab === 'struktur' ? 'bg-blue-700 text-white font-bold' : 'text-gray-700 hover:bg-blue-50/60 font-medium'" class="w-full flex items-center gap-3.5 px-5 py-4 text-left text-sm transition">
                        <i class="fas fa-sitemap w-5 text-center" :class="activeTab === 'struktur' ? 'text-white' : 'text-gray-500'"></i>
                        <span>Struktur Organisasi</span>
                    </button>
                    <button @click="activeTab = 'fasilitas'" :class="activeTab === 'fasilitas' ? 'bg-blue-700 text-white font-bold' : 'text-gray-700 hover:bg-blue-50/60 font-medium'" class="w-full flex items-center gap-3.5 px-5 py-4 text-left text-sm transition">
                        <i class="fas fa-building w-5 text-center" :class="activeTab === 'fasilitas' ? 'text-white' : 'text-gray-500'"></i>
                        <span>Fasilitas Sekolah</span>
                    </button>
                    <button @click="activeTab = 'galeri'" :class="activeTab === 'galeri' ? 'bg-blue-700 text-white font-bold' : 'text-gray-700 hover:bg-blue-50/60 font-medium'" class="w-full flex items-center gap-3.5 px-5 py-4 text-left text-sm transition">
                        <i class="fas fa-images w-5 text-center" :class="activeTab === 'galeri' ? 'text-white' : 'text-gray-500'"></i>
                        <span>Galeri</span>
                    </button>
                </div>

                <!-- AREA KONTEN KANAN (Memanggil 6 File Terpisah) -->
                <div class="flex-1 w-full">
                    <div x-show="activeTab === 'profil'" x-transition.opacity>
                        @include('pages.public.profil-tabs.tentang')
                    </div>
                    <div x-show="activeTab === 'sejarah'" x-cloak x-transition.opacity>
                        @include('pages.public.profil-tabs.sejarah')
                    </div>
                    <div x-show="activeTab === 'identitas'" x-cloak x-transition.opacity>
                        @include('pages.public.profil-tabs.identitas')
                    </div>
                    <div x-show="activeTab === 'struktur'" x-cloak x-transition.opacity>
                        @include('pages.public.profil-tabs.struktur')
                    </div>
                    <div x-show="activeTab === 'fasilitas'" x-cloak x-transition.opacity>
                        @include('pages.public.profil-tabs.fasilitas')
                    </div>
                    <div x-show="activeTab === 'galeri'" x-cloak x-transition.opacity>
                        @include('pages.public.profil-tabs.galeri')
                    </div>
                </div>

            </div>
        </section>
    </div>
</x-layouts.app>
