<x-layouts.admin>
    <div x-data="{ modalOpen: {{ $editGaleri ? 'true' : 'false' }} }" class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold text-blue-700">Konten Profil Sekolah</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Kelola Galeri Foto</h1>
                <p class="mt-1 text-sm text-gray-500">Kelompokkan beberapa foto dalam satu album acara. Album akan tampil di galeri publik.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('profil.public.index', ['tab' => 'galeri']) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <i class="fas fa-external-link-alt text-blue-700"></i>Lihat Galeri Publik
                </a>
                <button type="button" @click="modalOpen = true" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800">
                    <i class="fas fa-plus"></i>Tambah Album
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
            @forelse ($albums as $album)
                @php($item = $album->first())
                <article class="flex min-h-[340px] flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="relative h-48 shrink-0 bg-gray-100">
                        <img src="{{ asset('storage/'.$item->foto) }}" alt="{{ $item->judul }}" class="h-full w-full object-cover">
                        <span class="absolute left-3 top-3 rounded-full bg-blue-100 px-3 py-1 text-[11px] font-bold text-blue-700">{{ $item->kategori }}</span>
                        <span class="absolute right-3 top-3 rounded-full bg-gray-900/75 px-3 py-1 text-[11px] font-bold text-white">{{ $album->count() }} foto</span>
                    </div>
                    <div class="flex flex-1 flex-col p-4">
                        <h2 class="line-clamp-2 font-bold text-gray-800">{{ $item->judul }}</h2>
                        @if ($item->deskripsi)
                            <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-gray-500">{{ $item->deskripsi }}</p>
                        @endif
                        <div class="mt-auto flex justify-end gap-2 pt-4">
                            <a href="{{ route('admin.galeri.index', ['edit' => $item->id]) }}" class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100" aria-label="Edit album {{ $item->judul }}"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="{{ route('admin.galeri.destroy', $item) }}" onsubmit="return confirm('Hapus album {{ addslashes($item->judul) }} beserta seluruh fotonya?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus album {{ $item->judul }}"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-200 bg-white px-6 py-16 text-center md:col-span-2 xl:col-span-4">
                    <i class="fas fa-images text-4xl text-gray-300"></i>
                    <p class="mt-4 font-semibold text-gray-700">Belum ada foto galeri</p>
                    <p class="mt-1 text-sm text-gray-500">Tambahkan dokumentasi acara, fasilitas sekolah, ruang kelas, atau kegiatan siswa.</p>
                </div>
            @endforelse
        </section>

        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-gray-900/60 p-4 backdrop-blur-sm" style="display: none;" @keydown.escape.window="modalOpen = false">
            <div @click.away="modalOpen = false" class="my-8 w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">{{ $editGaleri ? 'Ubah Album Galeri' : 'Tambah Album Galeri' }}</h2>
                        <p class="mt-1 text-sm text-gray-500">Satu album berisi foto-foto dari acara yang sama.</p>
                    </div>
                    <button type="button" @click="modalOpen = false" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-500 transition hover:bg-gray-200" aria-label="Tutup"><i class="fas fa-times"></i></button>
                </div>

                <form method="POST" enctype="multipart/form-data" action="{{ $editGaleri ? route('admin.galeri.update', $editGaleri) : route('admin.galeri.store') }}" class="mt-6 space-y-5">
                    @csrf
                    @if ($editGaleri) @method('PUT') @endif
                    <div>
                        <label for="judul" class="mb-2 block text-sm font-semibold text-gray-700">Nama Acara / Album <span class="text-red-600">*</span></label>
                        <input id="judul" name="judul" value="{{ old('judul', $editGaleri?->judul) }}" required maxlength="200" placeholder="Contoh: Pengajian Sekolah" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    </div>
                    <div>
                        <label for="kategori" class="mb-2 block text-sm font-semibold text-gray-700">Kategori <span class="text-red-600">*</span></label>
                        <input id="kategori" name="kategori" list="kategori-galeri" value="{{ old('kategori', $editGaleri?->kategori ?? 'Kegiatan') }}" required maxlength="100" placeholder="Pilih atau tulis kategori" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <datalist id="kategori-galeri">
                            @foreach (['Kegiatan', 'Fasilitas', 'Ruang Kelas', 'Prestasi'] as $kategori)
                                <option value="{{ $kategori }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <label for="foto" class="mb-2 block text-sm font-semibold text-gray-700">{{ $editGaleri ? 'Tambah Foto ke Album' : 'Pilih Foto' }} <span class="text-red-600">{{ $editGaleri ? '' : '*' }}</span> <span class="text-xs font-normal text-gray-400">(JPG/PNG/WEBP maks. 5 MB per foto)</span></label>
                        @if ($editGaleri)
                            <p class="mb-2 text-xs font-semibold text-gray-500">Foto dalam album saat ini ({{ $editAlbum->count() }})</p>
                            <div class="mb-3 grid grid-cols-2 gap-2 sm:grid-cols-4">
                                @foreach ($editAlbum as $fotoAlbum)
                                    <img src="{{ asset('storage/'.$fotoAlbum->foto) }}" alt="{{ $editGaleri->judul }}" class="h-24 w-full rounded-lg object-cover">
                                @endforeach
                            </div>
                        @endif
                        <input id="foto" name="foto[]" type="file" accept="image/jpeg,image/png,image/webp" {{ $editGaleri ? '' : 'required' }} multiple class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-700">
                        @if ($editGaleri)
                            <p class="mt-1 text-xs text-gray-400">Pilih beberapa foto sekaligus untuk menambahkannya ke album ini. Foto lama tetap tersimpan.</p>
                        @else
                            <p class="mt-1 text-xs text-gray-400">Pilih beberapa foto sekaligus (maks. 20 foto). Semua foto akan masuk ke album acara ini.</p>
                        @endif
                    </div>
                    <div>
                        <label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi <span class="text-xs font-normal text-gray-400">(Opsional)</span></label>
                        <textarea id="deskripsi" name="deskripsi" rows="3" maxlength="2000" placeholder="Ceritakan sedikit tentang foto ini..." class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('deskripsi', $editGaleri?->deskripsi) }}</textarea>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-gray-100 pt-5">
                        <button type="button" @click="modalOpen = false" class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-200">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-blue-800"><i class="fas fa-save mr-2"></i>{{ $editGaleri ? 'Simpan & Tambah Foto' : 'Simpan Foto' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
