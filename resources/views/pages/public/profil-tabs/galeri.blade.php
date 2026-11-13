<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-4">Galeri Foto</h2>
    <div class="flex flex-col sm:flex-row gap-3 mb-6 pb-4 border-b border-gray-100">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
            <input type="text" x-model="searchGaleri" placeholder="Cari foto atau dokumentasi..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 outline-none">
        </div>
        <select x-model="kategoriGaleri" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 text-xs text-gray-700 font-semibold outline-none">
            <option>Semua Kategori</option>
            @foreach ($galeri->pluck('kategori')->unique()->sort() as $kategori)
                <option value="{{ $kategori }}">{{ $kategori }}</option>
            @endforeach
        </select>
    </div>

    @php
        $albums = $galeri
            ->groupBy(fn ($item) => $item->judul.'|'.$item->kategori)
            ->map(fn ($album) => $album->sortBy('id')->values());
    @endphp
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($albums as $album)
            @php
                $cover = $album->first();
                $photos = $album->map(fn ($photo) => [
                    'url' => asset('storage/'.$photo->foto),
                    'alt' => $photo->judul,
                    'description' => $photo->deskripsi,
                ])->values();
            @endphp
            <button type="button" @click="openGalleryAlbum(@js($cover->judul), @js($cover->kategori), @js($photos))" x-show="matchesGaleri(@js($cover->kategori), @js($cover->judul.' '.$album->pluck('deskripsi')->filter()->implode(' ')))" x-cloak class="group overflow-hidden rounded-xl border border-gray-200 bg-white text-left transition hover:-translate-y-0.5 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="relative h-56 overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/'.$cover->foto) }}" alt="{{ $cover->judul }}" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                    <span class="absolute bottom-3 right-3 rounded-full bg-gray-900/75 px-3 py-1.5 text-xs font-bold text-white"><i class="fas fa-images mr-1.5"></i>{{ $album->count() }} foto</span>
                </div>
                <div class="flex items-center justify-between gap-3 p-4">
                    <div class="min-w-0">
                        <h4 class="truncate text-sm font-extrabold text-blue-950">{{ $cover->judul }}</h4>
                        <p class="mt-1 text-[11px] text-gray-400"><i class="far fa-calendar-alt mr-1"></i>{{ $cover->created_at?->timezone('Asia/Jakarta')->format('d M Y') }}</p>
                    </div>
                    <span class="shrink-0 rounded-full bg-blue-50 px-2.5 py-1 text-[10px] font-bold text-blue-700">{{ $cover->kategori }}</span>
                </div>
                @if ($cover->deskripsi)
                    <p class="px-4 pb-4 text-sm leading-relaxed text-gray-600">{{ $cover->deskripsi }}</p>
                @endif
            </button>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 px-6 py-12 text-center sm:col-span-2 lg:col-span-3">
                <i class="fas fa-images text-4xl text-gray-300"></i>
                <p class="mt-4 font-semibold text-gray-700">Belum ada foto galeri</p>
                <p class="mt-1 text-sm text-gray-500">Dokumentasi sekolah akan ditampilkan di halaman ini.</p>
            </div>
        @endforelse
    </div>

    <div x-cloak x-show="albumOpen" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/90 p-4 sm:p-8" @click.self="closeGalleryAlbum()" @keydown.escape.window="closeGalleryAlbum()" @touchstart="touchStartX = $event.changedTouches[0].screenX" @touchend="finishGallerySwipe($event.changedTouches[0].screenX)">
        <div x-show="albumOpen" x-transition class="relative flex max-h-full w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl" role="dialog" aria-modal="true" aria-label="Galeri foto acara">
            <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 sm:px-6">
                <div class="min-w-0">
                    <h3 class="truncate text-lg font-bold text-blue-950" x-text="selectedAlbum.title"></h3>
                    <p class="mt-0.5 text-xs text-gray-500"><span x-text="selectedAlbum.category"></span> · Foto <span x-text="activePhotoIndex + 1"></span> dari <span x-text="selectedAlbum.photos.length"></span></p>
                </div>
                <button type="button" @click="closeGalleryAlbum()" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-600 transition hover:bg-gray-200" aria-label="Tutup galeri"><i class="fas fa-times"></i></button>
            </div>
            <div class="relative flex min-h-0 flex-1 items-center justify-center bg-gray-950 p-3 sm:p-6">
                <img :src="selectedAlbum.photos[activePhotoIndex]?.url" :alt="selectedAlbum.photos[activePhotoIndex]?.alt" class="max-h-[68vh] max-w-full select-none object-contain" draggable="false">
                <button type="button" x-show="selectedAlbum.photos.length > 1" @click="changeGalleryPhoto(-1)" class="absolute left-3 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow transition hover:bg-white sm:left-5" aria-label="Foto sebelumnya"><i class="fas fa-chevron-left"></i></button>
                <button type="button" x-show="selectedAlbum.photos.length > 1" @click="changeGalleryPhoto(1)" class="absolute right-3 flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-gray-800 shadow transition hover:bg-white sm:right-5" aria-label="Foto berikutnya"><i class="fas fa-chevron-right"></i></button>
            </div>
            <div x-show="selectedAlbum.photos[activePhotoIndex]?.description" class="border-t border-gray-100 px-5 py-3 text-sm leading-relaxed text-gray-600" x-text="selectedAlbum.photos[activePhotoIndex]?.description"></div>
        </div>
    </div>
</div>
