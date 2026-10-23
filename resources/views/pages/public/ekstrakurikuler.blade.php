<x-layouts.app title="Ekstrakurikuler - SDN Pojok 2 Kota Kediri">
    <section class="border-b bg-blue-50 py-10">
        <div class="mx-auto w-full px-6 lg:px-16 xl:px-24" data-aos="fade-right">
            <p class="mb-2 text-sm text-gray-500"><i class="fas fa-home"></i> Beranda &gt; Ekstrakurikuler</p>
            <h2 class="mb-1 text-sm font-bold uppercase tracking-wider text-blue-800">MINAT, BAKAT & KREATIVITAS</h2>
            <h1 class="mb-3 text-4xl font-extrabold text-red-600">Ekstrakurikuler SDN Pojok 2</h1>
            <p class="max-w-3xl text-gray-600">Temukan kegiatan pilihan untuk mengembangkan minat, bakat, keterampilan, dan rasa percaya diri siswa di luar pembelajaran kelas.</p>
        </div>
    </section>

    <section class="mx-auto w-full px-6 py-10 lg:px-16 xl:px-24">
        <div class="mb-8 flex flex-col gap-4 border-b border-gray-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
            <div><h2 class="flex items-center gap-2 text-2xl font-bold text-blue-900"><i class="fas fa-person-running text-blue-600"></i> Pilihan Kegiatan</h2><p class="mt-1 text-sm text-gray-500">Belajar hal baru, berlatih bersama, dan tumbuh percaya diri.</p></div>
            <div class="inline-flex items-center gap-2 self-start rounded-lg bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-800 sm:self-auto"><i class="fas fa-users"></i>{{ count($ekstrakurikuler) }} kegiatan tersedia</div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($ekstrakurikuler as $index => $item)
                @php
                    $tone = match ($item->warna) {
                        'red' => ['bg' => 'bg-red-100', 'text' => 'text-red-600', 'label' => 'bg-red-50 text-red-700'],
                        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'bg-green-50 text-green-700'],
                        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'bg-purple-50 text-purple-700'],
                        default => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'bg-blue-50 text-blue-700'],
                    };
                @endphp
                <article class="group border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <div class="flex items-start gap-4 border-b border-gray-200 p-5">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl {{ $tone['bg'] }} text-2xl {{ $tone['text'] }}"><i class="fas {{ $item->ikon }}"></i></div>
                        <div class="min-w-0 flex-1"><span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $tone['label'] }}">{{ $item->kategori }}</span><h3 class="mt-2 text-lg font-bold text-blue-900 group-hover:text-blue-700">{{ $item->nama_ekskul }}</h3></div>
                    </div>
                    <div class="p-5">
                        <p class="min-h-20 text-sm leading-6 text-gray-600">{{ $item->deskripsi }}</p>
                        <dl class="mt-5 space-y-3 border-t border-gray-100 pt-4 text-sm">
                            <div class="flex items-center gap-2 text-gray-600"><i class="far fa-clock w-4 text-center text-red-500"></i><dt class="font-semibold text-blue-900">Jadwal</dt><dd>{{ $item->jadwal }}</dd></div>
                            <div class="flex items-center gap-2 text-gray-600"><i class="fas fa-user-tie w-4 text-center text-blue-600"></i><dt class="font-semibold text-blue-900">Pembina</dt><dd>{{ $item->pembina ?: '—' }}</dd></div>
                        </dl>
                        <div class="mt-4 inline-flex items-center gap-2 rounded-lg bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-600"><i class="fas fa-user-group text-blue-600"></i>{{ $item->peserta }} siswa mengikuti</div>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="mt-10 border-l-4 border-red-500 bg-gray-50 p-5 sm:p-6"><h3 class="font-bold text-blue-900">Ayo kembangkan potensimu!</h3><p class="mt-1 text-sm text-gray-600">Hubungi pihak sekolah untuk informasi pendaftaran dan jadwal kegiatan ekstrakurikuler.</p></div>
        <p class="mt-4 text-center text-xs text-gray-400">Informasi kegiatan diperbarui oleh pihak sekolah.</p>
    </section>
</x-layouts.app>
