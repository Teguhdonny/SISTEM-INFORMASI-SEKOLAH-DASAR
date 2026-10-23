<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Profil & Visi Misi - Admin SDN Pojok 2</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800" 
      x-data="{ 
          sidebarOpen: true, 
          activeTab: 'profil',
          editModal: {{ $errors->any() ? 'true' : 'false' }},
          visiSekolah: @js($visi),
          notifSuccess: {{ session('success') ? 'true' : 'false' }}
      }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR KIRI -->
        @include('components.admin.sidebar')

        <!-- KONTEN UTAMA SEBELAH KANAN -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar Atas -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-20 shrink-0 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-900 focus:outline-none transition">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <form method="GET" action="{{ route('admin.search') }}" class="relative hidden sm:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari menu atau informasi..." aria-label="Cari menu atau informasi" class="w-80 bg-gray-50 border border-gray-200 rounded-full pl-10 pr-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </form>
                </div>

                <div class="flex items-center gap-5">
                    <div class="flex items-center gap-3 border-l pl-5 border-gray-200">
                        <div class="w-9 h-9 bg-blue-900 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-sm">
                            AF
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 leading-tight">Ahmad Fauzi</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Administrator</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Area Isi Utama -->
            <main class="flex-1 overflow-y-auto p-8 space-y-6">

                <!-- Alert Notifikasi Simpan -->
                <div x-show="notifSuccess" x-transition style="display: none;" class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600 text-base"></i>
                        <span class="text-xs font-semibold">Perubahan data Profil & Visi Misi sekolah berhasil disimpan!</span>
                    </div>
                    <button @click="notifSuccess = false" class="text-emerald-500 hover:text-emerald-700"><i class="fas fa-times"></i></button>
                </div>

                <!-- Header Halaman & Tombol Aksi -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-blue-600">Konten Website</span>
                        <h1 class="text-2xl font-extrabold text-gray-900 mt-0.5">Profil Sekolah & Visi Misi</h1>
                        <p class="text-xs text-gray-500 mt-1">Kelola identitas utama, visi, misi, dan tujuan strategis SDN Pojok 2 Kota Kediri.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/visi-misi') }}" target="_blank" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-external-link-alt text-blue-600"></i> Lihat Halaman Publik
                        </a>
                        <button @click="editModal = true" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-edit"></i> Edit Profil & Visi Misi
                        </button>
                    </div>
                </div>

                <!-- Tab Navigasi -->
                <div class="flex gap-2 border-b border-gray-200 pb-3">
                    <button @click="activeTab = 'profil'" 
                            :class="activeTab === 'profil' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-school"></i> Identitas & Profil Sekolah
                    </button>
                    <button @click="activeTab = 'visimisi'" 
                            :class="activeTab === 'visimisi' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-bullseye"></i> Visi & Misi (2026)
                    </button>
                    <button @click="activeTab = 'tujuan'" 
                            :class="activeTab === 'tujuan' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-flag-checkered"></i> Tujuan Sekolah
                    </button>
                </div>

                <!-- TAB 1: IDENTITAS & PROFIL SEKOLAH -->
                <div x-show="activeTab === 'profil'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Kartu Info Singkat -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-blue-50 text-blue-900 rounded-3xl flex items-center justify-center text-4xl shadow-inner border border-blue-100 mb-4">
                            <i class="fas fa-school"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900">{{ $profilSekolah->nama_sekolah }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Dinas Pendidikan Pemerintah Kota Kediri</p>
                        <span class="mt-3 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-bold">Status: Negeri / Aktif</span>
                        
                        <div class="w-full border-t border-gray-100 mt-6 pt-5 text-left space-y-3 text-xs">
                            <div>
                                <span class="text-gray-400 block">Alamat Lengkap:</span>
                                <strong class="text-gray-800">{{ $profilSekolah->alamat }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Email Resmi:</span>
                                <strong class="text-blue-600">{{ $profilSekolah->email_sekolah }}</strong>
                            </div>
                            <div>
                                <span class="text-gray-400 block">Kecamatan / Kota:</span>
                                <strong class="text-gray-800">Mojoroto / Kota Kediri</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Deskripsi Profil -->
                    <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-gray-100 space-y-6">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 mb-2 flex items-center gap-2">
                                <i class="fas fa-info-circle text-blue-600"></i> Tentang SD Negeri Pojok 2 Kota Kediri
                            </h3>
                            <p class="text-xs text-gray-600 leading-relaxed">
                                SD Negeri Pojok 2 Kota Kediri merupakan sekolah dasar negeri di bawah naungan Dinas Pendidikan Pemerintah Kota Kediri yang berlokasi di Jalan Mastrip Nomor 59, Kecamatan Mojoroto, Kota Kediri. Sekolah ini berkomitmen menyelenggarakan pendidikan dasar yang berfokus pada pembentukan akhlak mulia, kemandirian, kreativitas, wawasan global, serta kepedulian terhadap lingkungan sekitar.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                            <div class="bg-blue-50/60 p-4 rounded-2xl border border-blue-100">
                                <span class="text-[11px] text-blue-600 font-bold uppercase">Tenaga Pendidik</span>
                                <p class="text-2xl font-extrabold text-blue-900 mt-1">8 Guru</p>
                                <span class="text-[10px] text-gray-500">PNS & PPPK Tersertifikasi</span>
                            </div>
                            <div class="bg-amber-50/60 p-4 rounded-2xl border border-amber-100">
                                <span class="text-[11px] text-amber-600 font-bold uppercase">Program Unggulan</span>
                                <p class="text-2xl font-extrabold text-amber-800 mt-1">CERMAT</p>
                                <span class="text-[10px] text-gray-500">Cerdas, Mandiri & Terampil</span>
                            </div>
                            <div class="bg-emerald-50/60 p-4 rounded-2xl border border-emerald-100">
                                <span class="text-[11px] text-emerald-600 font-bold uppercase">Gerakan Lingkungan</span>
                                <p class="text-2xl font-extrabold text-emerald-800 mt-1">BERSEHAT</p>
                                <span class="text-[10px] text-gray-500">Bersih, Elok, Sehat, Rindang</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: VISI & MISI RESMI 2026 -->
                <div x-show="activeTab === 'visimisi'" style="display: none;" class="space-y-6">
                    <!-- Kartu Visi -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 flex flex-col md:flex-row items-center gap-6">
                        <div class="w-16 h-16 shrink-0 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl border border-blue-100">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-600">Visi Sekolah Tahun 2026</span>
                            <h3 class="text-lg md:text-xl font-extrabold text-blue-900 mt-1 italic" x-text="visiSekolah">
                                “MEWUJUDKAN GENERASI MUDA YANG BERAKHLAK MULIA, MANDIRI, KREATIF BERWAWASAN GLOBAL DAN PEDULI LINGKUNGAN”
                            </h3>
                        </div>
                    </div>

                    <!-- Kartu 5 Misi -->
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                        <h3 class="text-base font-bold text-gray-900 mb-6 flex items-center gap-2.5">
                            <i class="fas fa-clipboard-list text-blue-600"></i> 5 Misi Utama SD Negeri Pojok 2
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            @foreach ($misi as $deskripsiMisi)
                                <div class="p-4 rounded-2xl bg-gray-50 border border-gray-200 flex items-start gap-3.5 {{ $loop->last && $loop->iteration % 2 !== 0 ? 'md:col-span-2' : '' }}"><span class="w-6 h-6 shrink-0 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center">{{ $loop->iteration }}</span><p class="text-gray-700 leading-relaxed">{{ $deskripsiMisi }}</p></div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- TAB 3: TUJUAN SEKOLAH -->
                <div x-show="activeTab === 'tujuan'" style="display: none;" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Jangka Pendek -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-[11px] font-bold">1 Tahun ke Depan</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-3 mb-4">Tujuan Jangka Pendek</h3>
                        <ul class="space-y-2.5 text-xs text-gray-600 list-disc list-inside leading-relaxed">
                            <li>85% peserta didik memahami keimanan & nilai agama.</li>
                            <li>90% peserta didik menunjukkan keteladanan di dalam & luar kelas.</li>
                            <li>85% peserta didik mencintai potensi daerah.</li>
                            <li>85% peserta didik mengikuti program <strong>CERMAT</strong> (Cerdas, Mandiri dan Terampil).</li>
                            <li>85% peserta didik mengasah literasi, numerasi, dan pembelajaran HOTS.</li>
                            <li>85% peserta didik bijak bermedia sosial & menjaga kebersihan lingkungan.</li>
                        </ul>
                    </div>

                    <!-- Jangka Menengah -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-[11px] font-bold">2 - 3 Tahun ke Depan</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-3 mb-4">Tujuan Jangka Menengah</h3>
                        <ul class="space-y-2.5 text-xs text-gray-600 list-disc list-inside leading-relaxed">
                            <li>90% peserta didik mengamalkan nilai agama sehari-hari.</li>
                            <li>85% peserta didik mandiri dalam kepemimpinan proyek & ekstrakurikuler.</li>
                            <li>85% pendidik merancang pembelajaran sesuai bakat & meningkatkan literasi-numerasi.</li>
                            <li>85% peserta didik mampu mencegah hoax & menjaga etika digital.</li>
                            <li>85% peserta didik menerapkan gerakan <strong>BERSEHAT</strong> (Bersih, Elok, Sehat, Rindang dan Tentram).</li>
                        </ul>
                    </div>

                    <!-- Jangka Panjang -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-bold">4 Tahun ke Depan</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-3 mb-4">Tujuan Jangka Panjang</h3>
                        <ul class="space-y-2.5 text-xs text-gray-600 list-disc list-inside leading-relaxed">
                            <li>90% peserta didik berakhlak mulia & peduli sosial dalam toleransi beragama.</li>
                            <li>90% warga sekolah membangun kultur sekolah yang kompetitif dan positif.</li>
                            <li>90% pendidik menyusun pembelajaran berbasis proyek budaya lokal.</li>
                            <li>90% peserta didik mewujudkan budaya belajar mandiri & kolaborasi dengan mitra luar.</li>
                        </ul>
                    </div>
                </div>

                <!-- MODAL EDIT PROFIL & VISI MISI -->
                <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4 overflow-y-auto">
                    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full p-8 relative my-8">
                        <button @click="editModal = false" class="absolute top-6 right-6 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">Perbarui Profil & Visi Misi Sekolah</h3>
                        <p class="text-xs text-gray-500 mb-6">Ubah informasi profil, alamat, dan rumusan visi misi sekolah di bawah ini.</p>

                        @if ($errors->any())<div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-xs text-red-700">{{ $errors->first() }}</div>@endif

                        <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $profilSekolah->nama_sekolah) }}" required maxlength="150" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Email Resmi</label>
                                    <input type="email" name="email_sekolah" value="{{ old('email_sekolah', $profilSekolah->email_sekolah) }}" required maxlength="100" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Alamat Sekolah</label>
                                <input type="text" name="alamat" value="{{ old('alamat', $profilSekolah->alamat) }}" required maxlength="1000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Visi Sekolah</label>
                                <textarea name="visi" rows="2" required maxlength="5000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('visi', $visi) }}</textarea>
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Daftar Misi Sekolah (Satu per baris)</label>
                                <textarea name="misi" rows="5" required maxlength="10000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('misi', implode("\n", $misi)) }}</textarea>
                            </div>
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <button type="button" @click="editModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Batal</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>

</body>
</html>
