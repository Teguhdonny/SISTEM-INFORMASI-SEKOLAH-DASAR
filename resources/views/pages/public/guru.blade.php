<x-layouts.app>
    <section class="border-b bg-blue-50 py-10">
        <div class="mx-auto w-full px-6 lg:px-16 xl:px-24" data-aos="fade-right">
            <p class="mb-2 text-sm text-gray-500"><i class="fas fa-home"></i> Beranda &gt; Daftar Guru</p>
            <h2 class="mb-1 text-sm font-bold uppercase tracking-wider text-blue-800">TENAGA PENDIDIK</h2>
            <h1 class="mb-3 text-4xl font-extrabold text-red-600">Guru & Staff SDN Pojok 2 Kota Kediri</h1>
            <p class="max-w-3xl text-gray-600">Kenali para guru dan tenaga pendidik yang mendampingi siswa belajar dan berkembang setiap hari.</p>
        </div>
    </section>

    <section class="mx-auto w-full px-6 py-10 lg:px-16 xl:px-24">
        <div class="mb-6 flex flex-col gap-4 border-b border-gray-200 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="flex items-center gap-2 text-2xl font-bold text-blue-900"><i class="fas fa-user-group text-blue-600"></i>Daftar Guru & Staff</h2>
                <p class="mt-1 text-sm text-gray-500">Tenaga pendidik SDN Pojok 2 Kota Kediri</p>
            </div>
            <div class="inline-flex items-center gap-2 self-start rounded-lg bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800 sm:self-auto"><i class="fas fa-users"></i>{{ $guru->total() }} tenaga pendidik</div>
        </div>

        @if ($guru->isNotEmpty())
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                @foreach ($guru as $item)
                    <article class="overflow-hidden border border-gray-300 bg-white shadow-sm transition hover:shadow-md" data-aos="fade-up">
                        <div class="flex min-h-32 gap-3 p-2.5 sm:gap-4 sm:p-3">
                            <div class="h-32 w-24 shrink-0 overflow-hidden border border-gray-300 bg-gray-100 sm:h-36 sm:w-28">
                                @if ($item->foto)
                                    <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->nama }}" loading="lazy" class="h-full w-full object-cover object-center">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-gray-300"><i class="fas fa-user-tie text-4xl"></i></div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1 py-0.5 text-xs sm:text-sm">
                                <h3 class="mb-1.5 border-b border-gray-200 pb-1 font-bold uppercase leading-tight text-blue-900">{{ $item->nama }}</h3>
                                <dl class="space-y-1.5 leading-tight">
                                    <div>
                                        <dt class="font-bold text-blue-900">NIP</dt>
                                        <dd class="break-words text-gray-700">{{ $item->nip ?: '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="font-bold text-blue-900">Status & Tugas</dt>
                                        <dd class="break-words text-gray-700">{{ $item->bagian }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 px-3 py-1.5 text-center text-xs text-gray-600 sm:text-sm">
                            <span class="font-semibold">Email:</span>
                            @if ($item->email)
                                <a href="mailto:{{ $item->email }}" class="break-all hover:text-red-600">{{ $item->email }}</a>
                            @else
                                —
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            @if ($guru->hasPages())
                <div class="mt-10">{{ $guru->links() }}</div>
            @endif
        @else
            <div class="rounded-lg border border-gray-100 bg-white px-6 py-14 text-center shadow-sm">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-2xl text-blue-700"><i class="fas fa-users"></i></div>
                <h3 class="mt-5 text-lg font-bold text-blue-900">Informasi guru sedang disiapkan</h3>
                <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-gray-500">Daftar guru akan tersedia di halaman ini. Silakan kembali lagi untuk melihat informasi terbaru.</p>
            </div>
        @endif
    </section>
</x-layouts.app>
