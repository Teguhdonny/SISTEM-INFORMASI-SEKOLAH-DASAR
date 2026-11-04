<x-layouts.app>
    <div x-data="{ kelasAktif: 'Kelas 1' }" class="bg-gray-50/50 min-h-screen pb-20">
        <!-- Header -->
        <section class="w-full px-6 lg:px-16 xl:px-24 py-10 bg-gradient-to-r from-blue-50/80 via-indigo-50/40 to-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto">
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-3 font-medium">
                    <a href="/" class="hover:text-blue-600 transition"><i class="fas fa-home"></i> Beranda</a>
                    <span>&gt;</span>
                    <span class="text-blue-600 font-semibold">Jadwal Pelajaran</span>
                </div>
                <h4 class="text-blue-800 font-extrabold uppercase tracking-wider text-xs mb-1">AKADEMIK SEKOLAH</h4>
                <h1 class="text-3xl md:text-4xl font-extrabold text-red-600 mb-3">Jadwal Pelajaran SDN Pojok 2</h1>
                <p class="text-gray-600 text-sm md:text-base max-w-2xl">
                    Pilih jenjang kelas di bawah ini untuk melihat jadwal kegiatan belajar mengajar harian siswa.
                </p>
            </div>
        </section>

        <!-- Pilihan Tab Kelas 1 - 6 -->
        <section class="max-w-7xl mx-auto px-6 lg:px-16 pt-8">
            <div class="flex flex-wrap gap-2 mb-8">
                <template x-for="kls in ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6']" :key="kls">
                    <button @click="kelasAktif = kls"
                            :class="kelasAktif === kls ? 'bg-blue-800 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-blue-50 border border-gray-200'"
                            class="px-5 py-2.5 rounded-xl text-xs font-extrabold transition"
                            x-text="kls"></button>
                </template>
            </div>

            <!-- Grid Jadwal Senin - Sabtu -->
            @foreach (\App\Models\JadwalPelajaran::DAFTAR_KELAS as $namaKelas)
                @php
                    $jadwalHarian = $jadwalPerKelas->get($namaKelas, collect());
                @endphp

                <div x-show="kelasAktif === '{{ $namaKelas }}'" @if (! $loop->first) x-cloak style="display: none;" @endif>
                    @if ($jadwalHarian->isEmpty())
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-sm text-gray-500">
                            Jadwal pelajaran {{ $namaKelas }} belum tersedia.
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach (\App\Models\JadwalPelajaran::DAFTAR_HARI as $hari)
                                @continue(! $jadwalHarian->has($hari))
                                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                                    <div class="bg-blue-900 text-white px-5 py-3.5 flex items-center justify-between">
                                        <span class="font-extrabold text-sm"><i class="far fa-calendar-check mr-2"></i> {{ $hari }}</span>
                                        <span class="text-[11px] bg-white/20 px-2.5 py-0.5 rounded-full font-bold" x-text="kelasAktif"></span>
                                    </div>
                                    <div class="p-5 divide-y divide-gray-100 space-y-3">
                                        @foreach ($jadwalHarian->get($hari) as $pelajaran)
                                            <div class="pt-3 first:pt-0 flex items-start justify-between gap-3 text-xs">
                                                <span class="text-gray-400 font-semibold shrink-0">{{ $pelajaran->jam_label }}</span>
                                                <span class="font-bold text-gray-800 text-right">{{ $pelajaran->mata_pelajaran }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </section>
    </div>
</x-layouts.app>