<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-6 pb-3 border-b border-gray-100">Fasilitas Sekolah</h2>
    @php
        $fasilitas = $profilSekolah?->fasilitas_sekolah ?: [
            ['nama' => 'Ruang Kelas', 'ikon' => 'kelas', 'ket' => 'Ruang kelas yang nyaman dan mendukung proses belajar mengajar.'],
            ['nama' => 'Perpustakaan', 'ikon' => 'buku', 'ket' => 'Tersedia koleksi buku yang lengkap untuk meningkatkan minat baca siswa.'],
            ['nama' => 'Laboratorium', 'ikon' => 'laboratorium', 'ket' => 'Fasilitas praktikum dan komputer untuk mendukung pembelajaran berbasis sains & digital.'],
            ['nama' => 'UKS', 'ikon' => 'uks', 'ket' => 'Unit Kesehatan Sekolah dengan layanan kesehatan dasar bagi warga sekolah.'],
            ['nama' => 'Lapangan', 'ikon' => 'lapangan', 'ket' => 'Lapangan olahraga dan upacara untuk kegiatan ekstrakurikuler serta kebugaran.'],
            ['nama' => 'Toilet Bersih', 'ikon' => 'toilet', 'ket' => 'Toilet bersih dan terawat untuk mendukung gerakan BERSEHAT bagi siswa.'],
        ];
        $ikonFasilitas = [
            'kelas' => ['fa' => 'fa-chalkboard', 'warna' => 'bg-blue-100 text-blue-700'],
            'buku' => ['fa' => 'fa-book-reader', 'warna' => 'bg-amber-100 text-amber-700'],
            'laboratorium' => ['fa' => 'fa-desktop', 'warna' => 'bg-indigo-100 text-indigo-700'],
            'uks' => ['fa' => 'fa-briefcase-medical', 'warna' => 'bg-emerald-100 text-emerald-700'],
            'lapangan' => ['fa' => 'fa-futbol', 'warna' => 'bg-green-100 text-green-700'],
            'toilet' => ['fa' => 'fa-restroom', 'warna' => 'bg-sky-100 text-sky-700'],
        ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach ($fasilitas as $f)
            @php($tampilanIkon = $ikonFasilitas[$f['ikon']] ?? $ikonFasilitas['kelas'])
            <div class="rounded-xl border border-gray-200 p-4 flex gap-4 items-center hover:shadow-md transition bg-gray-50/40">
                <div class="w-16 h-16 rounded-lg {{ $tampilanIkon['warna'] }} flex items-center justify-center text-2xl shrink-0">
                    <i class="fas {{ $tampilanIkon['fa'] }}"></i>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-blue-950 mb-1">{{ $f['nama'] }}</h4>
                    <p class="text-xs text-gray-600 leading-relaxed">{{ $f['ket'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</div>