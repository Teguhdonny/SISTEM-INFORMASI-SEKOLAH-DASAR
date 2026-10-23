<x-layouts.admin>
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h1 class="text-xl font-bold text-gray-800">Hasil Pencarian</h1>
            @if ($query !== '')
                <p class="mt-1 text-sm text-gray-500">Hasil untuk <span class="font-semibold text-gray-700">“{{ $query }}”</span></p>
            @else
                <p class="mt-1 text-sm text-gray-500">Masukkan kata kunci untuk mencari menu atau informasi.</p>
            @endif
        </section>

        @if ($query !== '')
            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-800">Menu</h2>
                @forelse ($menu as $item)
                    <a href="{{ $item['url'] }}" class="mb-3 flex items-center justify-between rounded-xl border border-gray-100 p-4 transition hover:border-blue-200 hover:bg-blue-50 last:mb-0">
                        <span><span class="block text-sm font-semibold text-gray-800">{{ $item['judul'] }}</span><span class="mt-1 block text-xs text-gray-500">{{ $item['deskripsi'] }}</span></span>
                        <i class="fas fa-arrow-right text-sm text-blue-600"></i>
                    </a>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada menu yang cocok.</p>
                @endforelse
            </section>

            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="mb-4 font-semibold text-gray-800">Informasi</h2>
                @forelse ($informasi as $item)
                    <a href="{{ $item['url'] }}" class="mb-3 block rounded-xl border border-gray-100 p-4 transition hover:border-blue-200 hover:bg-blue-50 last:mb-0">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-blue-600">{{ $item['jenis'] }}</span>
                        <span class="mt-1 block text-sm font-semibold text-gray-800">{{ $item['judul'] }}</span>
                        @if ($item['deskripsi'] !== '')
                            <span class="mt-1 block line-clamp-2 text-xs text-gray-500">{{ $item['deskripsi'] }}</span>
                        @endif
                    </a>
                @empty
                    <p class="text-sm text-gray-500">Tidak ada informasi yang cocok.</p>
                @endforelse
            </section>
        @endif
    </div>
</x-layouts.admin>
