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
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800" 
      x-data="{ 
          sidebarOpen: true, 
          activeTab: @js(in_array(old('section', request('tab', 'profil')), ['profil', 'sejarah', 'identitas', 'struktur', 'fasilitas', 'visimisi', 'tujuan'], true) ? old('section', request('tab', 'profil')) : 'profil'),
          editModal: {{ $errors->any() && in_array(old('section'), ['profil', 'sejarah', 'identitas', 'struktur', 'fasilitas', 'visimisi', 'tujuan'], true) ? 'true' : 'false' }},
          visiSekolah: @js($visi),
          notifSuccess: {{ session('success') ? 'true' : 'false' }}
      }">
    @php
        $adminUser = auth()->user();
        $adminName = $adminUser?->name ?: ($adminUser?->username ?: 'Admin');
    @endphp

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
                </div>

                <div class="flex items-center gap-5">
                    <x-admin.notifications />
                    <div class="relative border-l border-gray-200 pl-5" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                        <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true" class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <span class="sr-only">{{ $adminName }}</span>
                            </div>
                            <div class="hidden text-left sm:block">
                                <p class="text-xs font-bold text-gray-800 leading-tight">{{ $adminName }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ ucfirst($adminUser?->role ?? 'Admin') }}</p>
                            </div>
                            <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform" :class="{ 'rotate-180': profileOpen }"></i>
                        </button>
                        <div x-show="profileOpen" x-transition @keydown.escape.window="profileOpen = false" class="absolute right-0 top-full z-50 mt-2 w-64 rounded-xl border border-gray-100 bg-white p-2 shadow-lg" style="display: none;">
                            <div class="border-b border-gray-100 px-3 py-2">
                                <p class="text-sm font-semibold text-gray-800">{{ $adminName }}</p>
                                <p class="text-xs text-gray-500">{{ $adminUser?->username ?? 'Akun admin' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50"><i class="fas fa-right-from-bracket w-4"></i>Keluar</button>
                            </form>
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
                        <span class="text-xs font-semibold">{{ session('success') }}</span>
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
                        <a href="{{ route('profil.public.index') }}" target="_blank" rel="noopener" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-external-link-alt text-blue-600"></i> Lihat Halaman Publik
                        </a>
                        <button @click="editModal = true" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-edit"></i>
                            <span x-text="activeTab === 'profil' ? 'Edit Profil Sekolah' : (activeTab === 'sejarah' ? 'Edit Sejarah Singkat' : (activeTab === 'identitas' ? 'Edit Identitas Sekolah' : (activeTab === 'struktur' ? 'Edit Struktur Organisasi' : (activeTab === 'fasilitas' ? 'Edit Fasilitas Sekolah' : (activeTab === 'visimisi' ? 'Edit Visi & Misi' : 'Edit Tujuan Sekolah')))))">Edit Profil Sekolah</span>
                        </button>
                    </div>
                </div>

                <!-- Tab Navigasi -->
                <div class="flex flex-wrap gap-2 border-b border-gray-200 pb-3">
                    <button @click="activeTab = 'profil'" 
                            :class="activeTab === 'profil' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-school"></i> Profil Sekolah
                    </button>
                    <button @click="activeTab = 'sejarah'"
                            :class="activeTab === 'sejarah' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-file-lines"></i> Sejarah Singkat
                    </button>
                    <button @click="activeTab = 'identitas'"
                            :class="activeTab === 'identitas' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-id-card"></i> Identitas Sekolah
                    </button>
                    <button @click="activeTab = 'struktur'"
                            :class="activeTab === 'struktur' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-sitemap"></i> Struktur Organisasi
                    </button>
                    <button @click="activeTab = 'fasilitas'"
                            :class="activeTab === 'fasilitas' ? 'bg-blue-900 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-100'"
                            class="px-5 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i class="fas fa-building"></i> Fasilitas Sekolah
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

                @include('pages.admin.profil.sejarah')
                @include('pages.admin.profil.identitas')
                @include('pages.admin.profil.struktur')
                @include('pages.admin.profil.fasilitas')

                <!-- TAB 1: IDENTITAS & PROFIL SEKOLAH -->
                <div x-show="activeTab === 'profil'" style="display: none;" class="grid grid-cols-1 gap-6">
                    <!-- Kartu Info Singkat -->
                    <div class="mx-auto w-full max-w-2xl bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col items-center text-center">
                        <div class="w-24 h-24 bg-blue-50 text-blue-900 rounded-3xl flex items-center justify-center text-4xl shadow-inner border border-blue-100 mb-4">
                            <i class="fas fa-school"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-900">{{ $profilSekolah->nama_sekolah }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Dinas Pendidikan Pemerintah Kota Kediri</p>
                        <span class="mt-3 px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-[11px] font-bold">Status: {{ $profilSekolah->status_sekolah ?: 'Negeri / Aktif' }}</span>
                        
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
                            @foreach ($tujuan['pendek'] as $objective)<li>{{ $objective }}</li>@endforeach
                        </ul>
                    </div>

                    <!-- Jangka Menengah -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <span class="px-3 py-1 bg-amber-50 text-amber-700 rounded-lg text-[11px] font-bold">2 - 3 Tahun ke Depan</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-3 mb-4">Tujuan Jangka Menengah</h3>
                        <ul class="space-y-2.5 text-xs text-gray-600 list-disc list-inside leading-relaxed">
                            @foreach ($tujuan['menengah'] as $objective)<li>{{ $objective }}</li>@endforeach
                        </ul>
                    </div>

                    <!-- Jangka Panjang -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-bold">4 Tahun ke Depan</span>
                        <h3 class="text-base font-extrabold text-gray-900 mt-3 mb-4">Tujuan Jangka Panjang</h3>
                        <ul class="space-y-2.5 text-xs text-gray-600 list-disc list-inside leading-relaxed">
                            @foreach ($tujuan['panjang'] as $objective)<li>{{ $objective }}</li>@endforeach
                        </ul>
                    </div>
                </div>

                <!-- MODAL EDIT KONTEN PROFIL -->
                <div x-show="editModal" style="display: none;" @keydown.escape.window="editModal = false" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
                    <div class="bg-white rounded-3xl shadow-2xl max-h-[90vh] max-w-3xl w-full overflow-y-auto p-8 relative my-8">
                        <button @click="editModal = false" class="absolute top-6 right-6 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1" x-text="activeTab === 'profil' ? 'Edit Profil Sekolah' : (activeTab === 'sejarah' ? 'Edit Sejarah Singkat' : (activeTab === 'identitas' ? 'Edit Identitas Sekolah' : (activeTab === 'struktur' ? 'Edit Struktur Organisasi' : (activeTab === 'fasilitas' ? 'Edit Fasilitas Sekolah' : (activeTab === 'visimisi' ? 'Edit Visi & Misi' : 'Edit Tujuan Sekolah')))))"></h3>
                        <p class="text-xs text-gray-500 mb-6">Perubahan hanya diterapkan pada tab yang sedang terbuka.</p>

                        @if ($errors->any())<div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-xs text-red-700">{{ $errors->first() }}</div>@endif

                        <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-4 text-xs">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="section" x-bind:value="activeTab">
                            <div x-show="activeTab === 'profil'" class="space-y-4">
                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Nama Sekolah</label>
                                    <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $profilSekolah->nama_sekolah) }}" x-bind:disabled="activeTab !== 'profil'" required maxlength="150" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Email Resmi</label>
                                    <input type="email" name="email_sekolah" value="{{ old('email_sekolah', $profilSekolah->email_sekolah) }}" x-bind:disabled="activeTab !== 'profil'" required maxlength="100" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                              </div>
                              <div>
                                <label class="font-bold text-gray-700 block mb-1">Alamat Sekolah</label>
                                <input type="text" name="alamat" value="{{ old('alamat', $profilSekolah->alamat) }}" x-bind:disabled="activeTab !== 'profil'" required maxlength="1000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                              </div>
                              <div>
                                <label class="font-bold text-gray-700 block mb-1">Tentang Sekolah</label>
                                <textarea name="tentang_sekolah" rows="4" x-bind:disabled="activeTab !== 'profil'" required maxlength="5000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('tentang_sekolah', $profilSekolah->tentang_sekolah ?: $profilSekolah->sejarah) }}</textarea>
                              </div>
                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tahun Berdiri</label>
                                    <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri', $profilSekolah->tahun_berdiri ?: 1982) }}" x-bind:disabled="activeTab !== 'profil'" required min="1800" max="{{ now()->year }}" step="1" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Status Sekolah</label>
                                    <input type="text" name="status_sekolah" value="{{ old('status_sekolah', $profilSekolah->status_sekolah ?: 'Negeri / Aktif') }}" x-bind:disabled="activeTab !== 'profil'" required maxlength="100" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                              </div>
                            </div>
                            <div x-show="activeTab === 'visimisi'" style="display: none;" class="space-y-4">
                              <div>
                                <label class="font-bold text-gray-700 block mb-1">Visi Sekolah</label>
                                <textarea name="visi" rows="2" x-bind:disabled="activeTab !== 'visimisi'" required maxlength="5000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('visi', $visi) }}</textarea>
                              </div>
                              <div>
                                <label class="font-bold text-gray-700 block mb-1">Daftar Misi Sekolah (Satu per baris)</label>
                                <textarea name="misi" rows="5" x-bind:disabled="activeTab !== 'visimisi'" required maxlength="10000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('misi', implode("\n", $misi)) }}</textarea>
                              </div>
                            </div>
                            <div x-show="activeTab === 'tujuan'" style="display: none;" class="space-y-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tujuan Jangka Pendek (Satu per baris)</label>
                                    <textarea name="tujuan_pendek" rows="5" x-bind:disabled="activeTab !== 'tujuan'" required maxlength="10000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('tujuan_pendek', implode("\n", $tujuan['pendek'])) }}</textarea>
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tujuan Jangka Menengah (Satu per baris)</label>
                                    <textarea name="tujuan_menengah" rows="5" x-bind:disabled="activeTab !== 'tujuan'" required maxlength="10000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('tujuan_menengah', implode("\n", $tujuan['menengah'])) }}</textarea>
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tujuan Jangka Panjang (Satu per baris)</label>
                                    <textarea name="tujuan_panjang" rows="5" x-bind:disabled="activeTab !== 'tujuan'" required maxlength="10000" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">{{ old('tujuan_panjang', implode("\n", $tujuan['panjang'])) }}</textarea>
                                </div>
                            </div>
                            @include('pages.admin.profil.sejarah-form')
                            @include('pages.admin.profil.identitas-form')
                            @include('pages.admin.profil.struktur-form')
                            @include('pages.admin.profil.fasilitas-form')
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
