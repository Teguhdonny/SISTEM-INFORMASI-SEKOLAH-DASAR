<div x-data="{ searchGaleri: '', kategoriGaleri: 'Semua Kategori', previewFoto: null }" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-4">Galeri Foto</h2>
    <div class="flex flex-col sm:flex-row gap-3 mb-6 pb-4 border-b border-gray-100">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
            <input type="text" x-model="searchGaleri" placeholder="Cari foto atau dokumentasi..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <select x-model="kategoriGaleri" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-700 font-semibold outline-none">
            <option value="Semua Kategori">Semua Kategori</option>
            <option value="Kegiatan">Kegiatan</option>
            <option value="Fasilitas">Fasilitas</option>
            <option value="Prestasi">Prestasi</option>
        </select>
    </div>

    @php
        // Menggunakan latest() agar aman meskipun kolom tanggal belum ada di database teman Anda
        $daftarGaleri = \App\Models\Galeri::query()->latest()->get();
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($daftarGaleri as $g)
            @php
                $tema = [
                    'Kegiatan' => ['ikon' => 'fa-flag', 'bg' => 'bg-blue-100 text-blue-600'],
                    'Fasilitas' => ['ikon' => 'fa-book-open', 'bg' => 'bg-emerald-100 text-emerald-600'],
                    'Prestasi' => ['ikon' => 'fa-running', 'bg' => 'bg-rose-100 text-rose-600'],
                ][$g->kategori ?? 'Kegiatan'] ?? ['ikon' => 'fa-image', 'bg' => 'bg-blue-100 text-blue-600'];
                
                $tglFormatted = isset($g->tanggal) && $g->tanggal ? $g->tanggal->translatedFormat('d F Y') : $g->created_at->translatedFormat('d F Y');
            @endphp
            <div x-show="(kategoriGaleri === 'Semua Kategori' || kategoriGaleri === '{{ $g->kategori ?? 'Kegiatan' }}') && '{{ strtolower($g->judul) }}'.includes(searchGaleri.toLowerCase())"
                 class="rounded-xl border border-gray-200 overflow-hidden group hover:shadow-md transition bg-white">
                <div class="h-40 {{ $tema['bg'] }} flex items-center justify-center overflow-hidden relative">
                    @if(!empty($g->foto))
                        <img src="{{ asset('storage/' . $g->foto) }}" alt="{{ $g->judul }}" 
                             @click="previewFoto = '{{ asset('storage/' . $g->foto) }}'"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300 cursor-pointer">
                    @else
                        <i class="fas {{ $tema['ikon'] }} text-4xl group-hover:scale-110 transition"></i>
                    @endif
                </div>
                <div class="p-4 flex items-center justify-between gap-2">
                    <div>
                        <h4 class="text-xs font-extrabold text-blue-950">{{ $g->judul }}</h4>
                        <p class="text-[11px] text-gray-400 mt-0.5"><i class="far fa-calendar-alt mr-1"></i> {{ $tglFormatted }}</p>
                    </div>
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-[10px] font-bold shrink-0">{{ $g->kategori ?? 'Kegiatan' }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-8 text-xs text-gray-400">
                Belum ada dokumentasi foto di galeri.
            </div>
        @endforelse
    </div>

    <!-- Lightbox Preview Foto Saat Diklik -->
    <div x-show="previewFoto" style="display: none;" x-transition.opacity @click="previewFoto = null" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
        <img :src="previewFoto" class="max-w-3xl max-h-[85vh] rounded-2xl shadow-2xl object-contain">
    </div>
</div>