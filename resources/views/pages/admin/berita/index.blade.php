<x-layouts.admin>
    <div
        x-data="{ modalOpen: {{ $editBerita ? 'true' : 'false' }} }"
        class="space-y-6"
    >
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold text-blue-700">Konten Beranda</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Kelola Berita & Pengumuman</h1>
                <p class="mt-1 text-sm text-gray-500">Berita berstatus Aktif akan ditampilkan di halaman beranda publik.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('home') }}#berita" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <i class="fas fa-external-link-alt text-blue-700"></i>Lihat di Beranda
                </a>
                <button type="button" @click="modalOpen = true" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800">
                    <i class="fas fa-plus"></i>Tambah Berita Baru
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
            @forelse ($berita as $item)
                <article class="flex min-h-[390px] flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative h-44 shrink-0 bg-gray-100">
                        @if ($item->foto)
                            <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->judul }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full items-center justify-center text-3xl text-gray-300"><i class="fas fa-image"></i></div>
                        @endif
                        <span class="absolute left-3 top-3 rounded-full bg-blue-100 px-3 py-1 text-[11px] font-bold text-blue-700">{{ $item->kategori }}</span>
                        <span class="absolute right-3 top-3 rounded-full px-3 py-1 text-[11px] font-bold {{ $item->status === 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">{{ $item->status }}</span>
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <p class="text-xs text-gray-400"><i class="fas fa-calendar-days mr-1"></i>{{ $item->tanggal_kegiatan->translatedFormat('d F Y') }}</p>
                        <h2 class="mt-2 line-clamp-2 font-bold text-gray-800">{{ $item->judul }}</h2>
                        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-gray-500">{{ $item->isi }}</p>
                        <div class="mt-auto flex gap-2 pt-4">
                            <a href="{{ route('admin.berita.index', ['edit' => $item->id]) }}" class="inline-flex flex-1 items-center justify-center gap-1 rounded-xl bg-amber-50 px-3 py-2 text-xs font-bold text-amber-700 transition hover:bg-amber-100"><i class="fas fa-pen"></i>Edit / Foto</a>
                            <form method="POST" action="{{ route('admin.berita.destroy', $item) }}" onsubmit="return confirm('Hapus berita {{ addslashes($item->judul) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus berita"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-200 bg-white px-6 py-16 text-center md:col-span-2 xl:col-span-4">
                    <i class="fas fa-newspaper text-4xl text-gray-300"></i>
                    <p class="mt-4 font-semibold text-gray-700">Belum ada berita</p>
                    <p class="mt-1 text-sm text-gray-500">Klik tombol Tambah Berita Baru untuk membuat berita pertama.</p>
                </div>
            @endforelse
        </section>

        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/60 p-4 backdrop-blur-sm" style="display: none;" @keydown.escape.window="modalOpen = false">
            <div @click.away="modalOpen = false" class="my-8 w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $editBerita ? 'Ubah Berita & Pengumuman' : 'Tambah Berita & Pengumuman' }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Unggah foto kegiatan dan isi informasi berita sekolah.</p>
                    </div>
                    <button type="button" @click="modalOpen = false" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition hover:bg-gray-200" aria-label="Tutup"><i class="fas fa-times"></i></button>
                </div>

                <form method="POST" enctype="multipart/form-data" action="{{ $editBerita ? route('admin.berita.update', $editBerita) : route('admin.berita.store') }}" class="mt-6 space-y-5">
                    @csrf
                    @if ($editBerita) @method('PUT') @endif
                    <div>
                        <label for="judul" class="mb-2 block text-sm font-semibold text-gray-700">Judul Berita / Pengumuman <span class="text-red-600">*</span></label>
                        <input id="judul" name="judul" value="{{ old('judul', $editBerita?->judul) }}" required maxlength="200" placeholder="Contoh: Upacara HUT RI ke-81 di SDN Pojok 2" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="kategori" class="mb-2 block text-sm font-semibold text-gray-700">Kategori Label</label>
                            <select id="kategori" name="kategori" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                @foreach (['Pengumuman', 'Kegiatan', 'Informasi'] as $kategori)
                                    <option value="{{ $kategori }}" @selected(old('kategori', $editBerita?->kategori ?? 'Pengumuman') === $kategori)>{{ $kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="tanggal_kegiatan" class="mb-2 block text-sm font-semibold text-gray-700">Tanggal Kegiatan <span class="text-red-600">*</span></label>
                            <input id="tanggal_kegiatan" name="tanggal_kegiatan" type="date" value="{{ old('tanggal_kegiatan', $editBerita?->tanggal_kegiatan?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        </div>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="foto" class="mb-2 block text-sm font-semibold text-gray-700">Upload Foto Kegiatan <span class="text-xs font-normal text-gray-400">(JPG/PNG maks. 2 MB)</span></label>
                            <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-700">
                        </div>
                        <div>
                            <label for="status" class="mb-2 block text-sm font-semibold text-gray-700">Status Tampil</label>
                            <select id="status" name="status" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <option value="Aktif" @selected(old('status', $editBerita?->status ?? 'Aktif') === 'Aktif')>Aktif (Tampil di Beranda)</option>
                                <option value="Draft" @selected(old('status', $editBerita?->status) === 'Draft')>Draft</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="isi" class="mb-2 block text-sm font-semibold text-gray-700">Isi Lengkap Berita / Pengumuman <span class="text-red-600">*</span></label>
                        <textarea id="isi" name="isi" rows="5" required maxlength="10000" placeholder="Tuliskan detail berita atau pengumuman..." class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('isi', $editBerita?->isi) }}</textarea>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                        <button type="button" @click="modalOpen = false" class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-200">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-800"><i class="fas fa-save mr-2"></i>{{ $editBerita ? 'Simpan Perubahan' : 'Simpan Berita' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
