<section x-show="activeTab === 'fasilitas'" style="display: none;" class="space-y-6">
    <div>
        <p class="text-xs font-bold text-blue-600">Profil Sekolah</p>
        <h2 class="mt-1 text-xl font-extrabold text-gray-900">Fasilitas Sekolah</h2>
        <p class="mt-1 text-xs text-gray-500">Daftar fasilitas dan keterangan singkat yang ditampilkan pada halaman publik.</p>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @foreach ($fasilitasSekolah as $item)
            <article class="flex items-start gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xl text-blue-700"><i class="fas {{ ['kelas' => 'fa-chalkboard', 'buku' => 'fa-book-reader', 'laboratorium' => 'fa-desktop', 'uks' => 'fa-briefcase-medical', 'lapangan' => 'fa-futbol', 'toilet' => 'fa-restroom'][$item['ikon']] ?? 'fa-building' }}"></i></span>
                <div>
                    <h3 class="font-bold text-blue-950">{{ $item['nama'] }}</h3>
                    <p class="mt-1 text-xs leading-relaxed text-gray-600">{{ $item['ket'] }}</p>
                </div>
            </article>
        @endforeach
    </div>
</section>
