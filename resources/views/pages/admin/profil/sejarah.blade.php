<section x-show="activeTab === 'sejarah'" style="display: none;" class="space-y-6">
    <div>
        <p class="text-xs font-bold text-blue-600">Profil Sekolah</p>
        <h2 class="mt-1 text-xl font-extrabold text-gray-900">Sejarah Singkat</h2>
        <p class="mt-1 text-xs text-gray-500">Ringkasan dan perjalanan sekolah dari waktu ke waktu.</p>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h3 class="mb-2 font-bold text-gray-800">Ringkasan Sejarah</h3>
        <p class="text-sm leading-relaxed text-gray-600">{{ $profilSekolah->sejarah }}</p>
    </div>

    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
        <h3 class="mb-5 font-bold text-gray-800">Linimasa Sekolah</h3>
        <div class="space-y-4">
            @foreach ($riwayat as $item)
                <article class="flex gap-4 border-l-2 border-blue-200 pl-4">
                    <div class="min-w-24">
                        <span class="text-sm font-extrabold text-blue-800">{{ $item['tahun'] }}</span>
                    </div>
                    <div class="flex-1 border-b border-gray-100 pb-4">
                        <h4 class="text-sm font-bold text-gray-800">{{ $item['judul'] }}</h4>
                        <p class="mt-1 text-xs leading-relaxed text-gray-600">{{ $item['isi'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
