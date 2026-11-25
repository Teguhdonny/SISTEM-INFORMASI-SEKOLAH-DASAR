<x-layouts.admin>
    <div class="mx-auto max-w-6xl space-y-6">
        <header class="rounded-2xl bg-gradient-to-r from-blue-800 to-indigo-800 px-6 py-7 text-white shadow-sm sm:px-8">
            <p class="text-sm font-semibold text-blue-200">Pengaturan konten</p>
            <h1 class="mt-1 text-2xl font-bold sm:text-3xl">Kelola Kategori</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-100">Atur kategori untuk Program Sekolah dan Ekstrakurikuler. Kategori yang dipakai konten tidak dapat dihapus.</p>
        </header>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <div class="grid items-start gap-5 xl:grid-cols-2">
            @foreach ([
                [
                    'type' => \App\Models\KategoriKonten::TYPE_PROGRAM,
                    'title' => 'Program Sekolah',
                    'description' => 'Program pembelajaran dan pembiasaan sekolah.',
                    'icon' => 'fa-book-open',
                    'categories' => $programCategories,
                    'usage' => $programUsage,
                ],
                [
                    'type' => \App\Models\KategoriKonten::TYPE_EKSTRAKURIKULER,
                    'title' => 'Ekstrakurikuler',
                    'description' => 'Kegiatan pilihan untuk minat dan bakat siswa.',
                    'icon' => 'fa-person-running',
                    'categories' => $extracurricularCategories,
                    'usage' => $extracurricularUsage,
                ],
            ] as $section)
                <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-gray-100 px-5 py-4 sm:px-6">
                        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-lg text-blue-700"><i class="fas {{ $section['icon'] }}"></i></span>
                        <div class="min-w-0 flex-1">
                            <h2 class="font-bold text-gray-900">{{ $section['title'] }}</h2>
                            <p class="mt-1 text-sm text-gray-500">{{ $section['description'] }}</p>
                        </div>
                        <span class="shrink-0 rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-600">{{ $section['categories']->count() }} kategori</span>
                    </div>

                    <form method="POST" action="{{ route('admin.kategori-konten.store') }}" class="border-b border-gray-100 bg-gray-50/70 p-4 sm:p-5">
                        @csrf
                        <input type="hidden" name="tipe" value="{{ $section['type'] }}">
                        <label for="nama-{{ $section['type'] }}" class="mb-2 block text-xs font-bold uppercase tracking-wide text-gray-500">Tambah kategori</label>
                        <div class="flex flex-col gap-2 sm:flex-row">
                            <input id="nama-{{ $section['type'] }}" name="nama" required maxlength="50" placeholder="Contoh: Akademik" class="min-w-0 flex-1 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-800"><i class="fas fa-plus"></i>Tambah</button>
                        </div>
                    </form>

                    <div class="space-y-2 p-4 sm:p-5">
                        @forelse ($section['categories'] as $category)
                            @php($usageCount = $section['usage'][$category->nama] ?? 0)
                            <div class="rounded-xl border border-gray-200 bg-white p-3 sm:p-3.5">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                    <form method="POST" action="{{ route('admin.kategori-konten.update', $category) }}" class="flex min-w-0 flex-1 gap-2">
                                        @csrf
                                        @method('PUT')
                                        <label for="kategori-{{ $category->id }}" class="sr-only">Nama kategori {{ $category->nama }}</label>
                                        <input id="kategori-{{ $category->id }}" name="nama" value="{{ $category->nama }}" required maxlength="50" class="min-w-0 flex-1 rounded-lg border border-gray-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                        <button type="submit" class="inline-flex shrink-0 items-center justify-center gap-2 whitespace-nowrap rounded-lg bg-blue-50 px-3 py-2.5 text-xs font-bold text-blue-700 transition hover:bg-blue-100"><i class="fas fa-check"></i><span>Simpan</span></button>
                                    </form>
                                    <div class="flex items-center justify-between gap-2 sm:justify-end">
                                        <span class="rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600">Dipakai {{ $usageCount }} konten</span>
                                        <form method="POST" action="{{ route('admin.kategori-konten.destroy', $category) }}" onsubmit="return confirm('Hapus kategori {{ addslashes($category->nama) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" @disabled($usageCount > 0) title="{{ $usageCount > 0 ? 'Ubah kategori konten terlebih dahulu' : 'Hapus kategori' }}" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-lg px-3 py-2.5 text-xs font-bold transition {{ $usageCount > 0 ? 'cursor-not-allowed bg-gray-100 text-gray-400' : 'bg-red-50 text-red-700 hover:bg-red-100' }}" aria-label="Hapus kategori {{ $category->nama }}"><i class="fas fa-trash"></i><span>Hapus</span></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-gray-300 px-5 py-8 text-center">
                                <i class="fas fa-tags text-2xl text-gray-300"></i>
                                <p class="mt-2 text-sm font-semibold text-gray-600">Belum ada kategori</p>
                                <p class="mt-1 text-xs text-gray-500">Tambahkan kategori melalui kolom di atas.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>

        <div class="flex gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-4 text-sm text-blue-900">
            <i class="fas fa-circle-info mt-0.5 text-blue-600"></i>
            <p><span class="font-bold">Tips:</span> Mengganti nama kategori juga memperbarui konten terkait. Untuk menghapus kategori, pindahkan atau hapus dulu konten yang masih menggunakannya.</p>
        </div>
    </div>
</x-layouts.admin>
