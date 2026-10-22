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
            @php
                $jadwalHarian = [
                    'Senin' => ['07.00 - 07.35' => 'Upacara Bendera', '07.35 - 08.45' => 'Pendidikan Pancasila', '08.45 - 09.55' => 'Bahasa Indonesia', '10.15 - 11.25' => 'Matematika'],
                    'Selasa' => ['07.00 - 07.15' => 'Literasi Pagi', '07.15 - 08.25' => 'Matematika / Numerasi', '08.25 - 09.35' => 'IPAS / Tematik', '09.55 - 11.05' => 'Seni dan Budaya'],
                    'Rabu' => ['07.00 - 07.15' => 'Literasi Pagi', '07.15 - 08.25' => 'Bahasa Indonesia', '08.25 - 09.35' => 'Pendidikan Agama & Budi Pekerti', '09.55 - 11.05' => 'PJOK (Olahraga)'],
                    'Kamis' => ['07.00 - 07.15' => 'Literasi Pagi', '07.15 - 08.25' => 'Matematika / Numerasi', '08.25 - 09.35' => 'Bahasa Jawa (Muatan Lokal)', '09.55 - 11.05' => 'Bahasa Inggris'],
                    'Jumat' => ['07.00 - 07.45' => 'Jumat Berkarakter / Jumat Sehat', '07.45 - 09.00' => 'Projek Penguatan Profil Pelajar (P5)', '09.15 - 10.30' => 'Bahasa Indonesia', '13.00 - 15.00' => 'Ekstrakurikuler Pramuka'],
                    'Sabtu' => ['07.00 - 07.30' => 'Gerakan Lingkungan BERSEHAT', '07.30 - 08.45' => 'Pengembangan Diri / CERMAT', '09.00 - 10.30' => 'Ekstrakurikuler Pilihan'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($jadwalHarian as $hari => $mapelList)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="bg-blue-900 text-white px-5 py-3.5 flex items-center justify-between">
                            <span class="font-extrabold text-sm"><i class="far fa-calendar-check mr-2"></i> {{ $hari }}</span>
                            <span class="text-[11px] bg-white/20 px-2.5 py-0.5 rounded-full font-bold" x-text="kelasAktif"></span>
                        </div>
                        <div class="p-5 divide-y divide-gray-100 space-y-3">
                            @foreach ($mapelList as $jam => $mapel)
                                <div class="pt-3 first:pt-0 flex items-start justify-between gap-3 text-xs">
                                    <span class="text-gray-400 font-semibold shrink-0">{{ $jam }}</span>
                                    <span class="font-bold text-gray-800 text-right">{{ $mapel }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</x-layouts.app>