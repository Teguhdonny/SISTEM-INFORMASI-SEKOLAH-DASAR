<x-layouts.app title="Program Sekolah - SDN Pojok 2 Kota Kediri">
    <section class="border-b bg-blue-50 py-10">
        <div class="mx-auto w-full px-6 lg:px-16 xl:px-24" data-aos="fade-right">
            <p class="mb-2 text-sm text-gray-500"><i class="fas fa-home"></i> Beranda &gt; Program Sekolah</p>
            <h2 class="mb-1 text-sm font-bold uppercase tracking-wider text-blue-800">KEGIATAN & PEMBELAJARAN</h2>
            <h1 class="mb-3 text-4xl font-extrabold text-red-600">Program Sekolah SDN Pojok 2</h1>
            <p class="max-w-3xl text-gray-600">Beragam kegiatan pembelajaran dan pembiasaan untuk mendukung potensi, karakter, dan kesehatan siswa SDN Pojok 2 Kota Kediri.</p>
        </div>
    </section>

    <section class="mx-auto w-full px-6 py-10 lg:px-16 xl:px-24">
        <div class="mb-8 flex flex-col gap-4 border-b border-gray-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-blue-900"><i class="fas fa-book-reader text-blue-600"></i> Program Unggulan</h2>
                <p class="mt-1 text-sm text-gray-500">Kegiatan yang membangun kebiasaan baik dan pengalaman belajar siswa.</p>
            </div>
            <div class="inline-flex items-center gap-2 self-start rounded-lg bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-800 sm:self-auto"><i class="fas fa-list-check"></i>{{ count($programs) }} program sekolah</div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($programs as $index => $program)
                @php
                    $tone = match ($program->warna) {
                        'red', 'rose' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'label' => 'bg-red-50 text-red-700'],
                        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'bg-green-50 text-green-700'],
                        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'bg-purple-50 text-purple-700'],
                        default => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'bg-blue-50 text-blue-700'],
                    };
                @endphp
                <article class="group border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <div class="flex items-start gap-4 border-b border-gray-200 p-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl {{ $tone['bg'] }} text-2xl {{ $tone['text'] }}"><i class="fas {{ $program->ikon }}"></i></div>
                        <div class="min-w-0 flex-1">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $tone['label'] }}">{{ $program->kategori }}</span>
                            <h3 class="mt-2 text-lg font-bold text-blue-900 group-hover:text-blue-700">{{ $program->nama_program }}</h3>
                        </div>
                    </div>
                    <div class="p-5">
                        <p class="min-h-20 text-sm leading-6 text-gray-600">{{ $program->deskripsi }}</p>
                        <div class="mt-5 flex items-center gap-2 border-t border-gray-100 pt-4 text-sm text-gray-600"><i class="far fa-clock text-red-500"></i><span class="font-semibold">Jadwal:</span>{{ $program->jadwal }}</div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10 flex flex-col gap-4 border-l-4 border-red-500 bg-gray-50 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-6">
            <div><h3 class="font-bold text-blue-900">Informasi program sekolah</h3><p class="mt-1 text-sm text-gray-600">Hubungi sekolah untuk mengetahui lebih lanjut tentang kegiatan siswa.</p></div>
            <a href="/profil" class="inline-flex shrink-0 items-center gap-2 self-start font-bold text-red-600 transition hover:text-red-700 sm:self-auto">Lihat profil sekolah <i class="fas fa-arrow-right text-sm"></i></a>
        </div>
        <p class="mt-4 text-center text-xs text-gray-400">Informasi program diperbarui oleh pihak sekolah.</p>
    </section>
</x-layouts.app>
