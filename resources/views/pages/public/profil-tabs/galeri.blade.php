<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-4">Galeri Foto</h2>
    <div class="flex flex-col sm:flex-row gap-3 mb-6 pb-4 border-b border-gray-100">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
            <input type="text" x-model="searchGaleri" placeholder="Cari foto atau dokumentasi..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <select x-model="kategoriGaleri" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-700 font-semibold outline-none">
            <option>Semua Kategori</option>
            <option>Kegiatan</option>
            <option>Fasilitas</option>
            <option>Prestasi</option>
        </select>
    </div>

    @php
        $galeri = [
            ['judul' => 'Kegiatan Upacara Bendera', 'tgl' => '17 Agustus 2026', 'kat' => 'Kegiatan', 'ikon' => 'fa-flag', 'bg' => 'bg-blue-100 text-blue-600'],
            ['judul' => 'Peringatan Hari Pendidikan', 'tgl' => '02 Mei 2026', 'kat' => 'Kegiatan', 'ikon' => 'fa-award', 'bg' => 'bg-indigo-100 text-indigo-600'],
            ['judul' => 'Kegiatan Belajar Mengajar', 'tgl' => '15 Juli 2026', 'kat' => 'Kegiatan', 'ikon' => 'fa-chalkboard-teacher', 'bg' => 'bg-amber-100 text-amber-600'],
            ['judul' => 'Perpustakaan Sekolah', 'tgl' => '10 Maret 2026', 'kat' => 'Fasilitas', 'ikon' => 'fa-book-open', 'bg' => 'bg-emerald-100 text-emerald-600'],
            ['judul' => 'Lomba Olahraga Siswa', 'tgl' => '22 Februari 2026', 'kat' => 'Prestasi', 'ikon' => 'fa-running', 'bg' => 'bg-rose-100 text-rose-600'],
            ['judul' => 'Lingkungan Sekolah', 'tgl' => '05 Januari 2026', 'kat' => 'Fasilitas', 'ikon' => 'fa-tree', 'bg' => 'bg-teal-100 text-teal-600'],
        ];
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($galeri as $g)
            <div class="rounded-xl border border-gray-200 overflow-hidden group hover:shadow-md transition">
                <div class="h-36 {{ $g['bg'] }} flex items-center justify-center">
                    <i class="fas {{ $g['ikon'] }} text-4xl group-hover:scale-110 transition"></i>
                </div>
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-extrabold text-blue-950">{{ $g['judul'] }}</h4>
                        <p class="text-[11px] text-gray-400 mt-0.5"><i class="far fa-calendar-alt mr-1"></i> {{ $g['tgl'] }}</p>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-bold">{{ $g['kat'] }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>