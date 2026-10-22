<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-6 pb-3 border-b border-gray-100">Sejarah Singkat Sekolah</h2>
    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <div class="flex-1 relative pl-6 border-l-2 border-blue-200 space-y-6">
            @php
                $riwayat = [
                    ['tahun' => '1982', 'judul' => 'Pendirian sekolah', 'isi' => 'SDN Pojok 2 Kota Kediri resmi berdiri pada tahun 1982 sebagai sekolah dasar negeri di wilayah Kecamatan Mojoroto.'],
                    ['tahun' => '1995', 'judul' => 'Pengembangan sarana prasarana', 'isi' => 'Mulai dibangun ruang kelas baru dan perpustakaan sekolah untuk menunjang proses belajar mengajar.'],
                    ['tahun' => '2010', 'judul' => 'Akreditasi', 'isi' => 'SDN Pojok 2 mendapatkan akreditasi B dari BAN-S/M serta terus meningkatkan mutu pembelajaran.'],
                    ['tahun' => '2020', 'judul' => 'Renovasi gedung', 'isi' => 'Dilakukan renovasi beberapa ruang kelas dan fasilitas penunjang demi kenyamanan warga sekolah.'],
                    ['tahun' => '2024 - Sekarang', 'judul' => 'Menuju sekolah unggul', 'isi' => 'Terus berinovasi dalam pembelajaran digital, program CERMAT, gerakan BERSEHAT, dan pengembangan karakter siswa.'],
                ];
            @endphp
            @foreach ($riwayat as $item)
                <div class="relative">
                    <span class="absolute -left-[31px] top-1 w-4 h-4 rounded-full bg-blue-600 border-4 border-white shadow"></span>
                    <div class="flex flex-col sm:flex-row sm:items-baseline gap-1 sm:gap-6">
                        <span class="text-sm font-extrabold text-blue-900 w-28 shrink-0">{{ $item['tahun'] }}</span>
                        <div class="{{ !$loop->last ? 'pb-4 border-b border-gray-100' : '' }} flex-1">
                            <h4 class="text-sm font-bold text-blue-950">{{ $item['judul'] }}</h4>
                            <p class="text-xs text-gray-600 mt-1">{{ $item['isi'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="w-full lg:w-64 shrink-0 bg-blue-50/60 border border-blue-100 rounded-2xl p-6 text-center">
            <div class="w-20 h-20 mx-auto mb-4 bg-white rounded-2xl shadow-sm border border-blue-100 flex items-center justify-center text-blue-700 text-4xl">
                <i class="fas fa-university"></i>
            </div>
            <h4 class="text-sm font-extrabold text-blue-950 mb-2">Sejarah Singkat</h4>
            <p class="text-xs text-gray-600 leading-relaxed">
                SDN Pojok 2 Kota Kediri terus berkembang menjadi sekolah unggul dengan dukungan guru, orang tua, dan masyarakat.
            </p>
        </div>
    </div>
</div>