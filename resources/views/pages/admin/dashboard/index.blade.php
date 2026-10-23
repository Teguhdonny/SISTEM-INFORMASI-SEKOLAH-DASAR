<x-layouts.admin>
    <div class="space-y-6">
        <section class="relative flex flex-col justify-between gap-6 overflow-hidden rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:flex-row md:items-center md:p-8">
            <div class="relative z-10">
                <h1 class="text-2xl font-bold text-gray-800">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm text-gray-500">Ringkasan data SDN Pojok 2 Kota Kediri.</p>
                <p class="mt-4 flex items-center gap-2 text-sm font-medium text-gray-500"><i class="far fa-calendar-alt text-blue-600"></i>{{ now('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
            </div>
            <div class="absolute inset-y-0 right-0 hidden w-1/3 bg-blue-50/50 md:block"><img src="{{ asset('images/sekolah.jpg') }}" alt="Sekolah" class="h-full w-full object-cover opacity-40 mix-blend-multiply"><div class="absolute inset-0 bg-gradient-to-r from-white via-white/80 to-transparent"></div></div>
        </section>

        <section class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm"><div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-xl text-blue-600"><i class="fas fa-user-tie"></i></div><p class="text-sm font-semibold text-gray-500">Total Guru &amp; Staf</p><p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($totalGuru, 0, ',', '.') }}</p><p class="mt-2 text-xs text-gray-500">Data tenaga pendidik tersimpan</p></article>
            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm"><div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-xl text-red-600"><i class="fas fa-user-graduate"></i></div><p class="text-sm font-semibold text-gray-500">Total Siswa</p><p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($totalSiswa, 0, ',', '.') }}</p><p class="mt-2 text-xs text-gray-500">Data siswa yang tercatat di PPDB</p></article>
            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm"><div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-xl text-blue-600"><i class="fas fa-newspaper"></i></div><p class="text-sm font-semibold text-gray-500">Berita &amp; Artikel</p><p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($totalBerita, 0, ',', '.') }}</p><p class="mt-2 text-xs text-gray-500">Berita yang tampil di halaman beranda</p></article>
            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm"><div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100 text-xl text-amber-600"><i class="fas fa-address-card"></i></div><p class="text-sm font-semibold text-gray-500">Data Daftar Ulang</p><p class="mt-1 text-3xl font-bold text-gray-800">{{ number_format($totalDaftarUlang, 0, ',', '.') }}</p><p class="mt-2 text-xs text-gray-500">Total data daftar ulang yang tercatat</p></article>
        </section>

        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm lg:col-span-2">
                <div class="mb-6 flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
                    <div><h2 class="font-bold text-gray-800">Statistik PPDB</h2><p class="mt-1 text-xs text-gray-500">Jumlah pendaftar PPDB per tahun</p></div>
                    <span class="text-xs font-medium text-gray-500">{{ $statistikPpdb->first()['tahun'] }} - {{ $statistikPpdb->last()['tahun'] }}</span>
                </div>
                @php
                    $skalaGrafik = max(1, $statistikPpdb->max('total'));
                    $titikGrafik = $statistikPpdb->values()->map(fn ($tahun, $indeks): array => [
                        'x' => 30 + ($indeks * 159),
                        'y' => 180 - (($tahun['total'] / $skalaGrafik) * 145),
                        'total' => $tahun['total'],
                    ]);
                    $koordinatGrafik = $titikGrafik->map(fn ($titik): string => $titik['x'].','.$titik['y'])->implode(' ');
                    $koordinatArea = '30,190 '.$koordinatGrafik.' 666,190';
                @endphp
                <div class="rounded-xl bg-gray-50 px-3 pb-2 pt-5 sm:px-5">
                    <svg viewBox="0 0 696 220" role="img" aria-label="Grafik jumlah pendaftar PPDB per tahun" class="h-52 w-full overflow-visible">
                        <defs><linearGradient id="ppdb-chart-fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#3b82f6" stop-opacity=".22"/><stop offset="100%" stop-color="#3b82f6" stop-opacity="0"/></linearGradient></defs>
                        @foreach ([35, 107.5, 180] as $garisY)
                            <line x1="30" y1="{{ $garisY }}" x2="666" y2="{{ $garisY }}" stroke="#e5e7eb" stroke-dasharray="4 5" />
                        @endforeach
                        <polygon points="{{ $koordinatArea }}" fill="url(#ppdb-chart-fill)" />
                        <polyline points="{{ $koordinatGrafik }}" fill="none" stroke="#2563eb" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                        @foreach ($titikGrafik as $indeks => $titik)
                            <circle cx="{{ $titik['x'] }}" cy="{{ $titik['y'] }}" r="4.5" fill="#fff" stroke="#2563eb" stroke-width="3"><title>{{ $statistikPpdb[$indeks]['tahun'] }}: {{ $titik['total'] }} pendaftar PPDB</title></circle>
                        @endforeach
                    </svg>
                    <div class="-mt-1 grid grid-cols-5 text-center text-[10px] font-medium text-gray-500 sm:text-xs">
                        @foreach ($statistikPpdb as $tahun)
                            <span>{{ $tahun['tahun'] }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2 text-xs text-gray-500"><span class="h-0.5 w-4 rounded bg-blue-600"></span>Jumlah pendaftar PPDB</div>
            </article>

            <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="font-bold text-gray-800">Riwayat Aktivitas</h2>
                <p class="mt-1 text-xs text-gray-500">Perubahan data terbaru di sistem</p>
                <div class="mt-5 space-y-5">
                    @forelse ($aktivitasTerbaru as $aktivitas)
                        <div class="flex gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-sm {{ match ($aktivitas['warna']) {'green' => 'bg-green-100 text-green-600', 'purple' => 'bg-purple-100 text-purple-600', 'amber' => 'bg-amber-100 text-amber-600', default => 'bg-blue-100 text-blue-600'} }}"><i class="fas {{ $aktivitas['ikon'] }}"></i></span>
                            <div class="min-w-0"><p class="text-sm font-semibold text-gray-700">{{ $aktivitas['judul'] }}</p><p class="truncate text-xs text-gray-500">{{ $aktivitas['keterangan'] }}</p><p class="mt-1 text-[11px] text-gray-400">{{ $aktivitas['waktu']->diffForHumans() }}</p></div>
                        </div>
                    @empty
                        <p class="rounded-xl bg-gray-50 px-4 py-6 text-center text-sm text-gray-500">Belum ada aktivitas tercatat.</p>
                    @endforelse
                </div>
            </article>
        </section>

        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
            <h2 class="mb-4 font-bold text-gray-800">Menu Cepat</h2>
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <a href="{{ route('admin.guru.index') }}" class="flex flex-col items-center rounded-xl border border-gray-100 bg-gray-50 py-4 transition hover:border-blue-100 hover:bg-blue-50"><i class="fas fa-users mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600"></i><span class="text-xs font-semibold text-gray-600">Kelola Guru</span></a>
                <a href="{{ route('admin.program.index') }}" class="flex flex-col items-center rounded-xl border border-gray-100 bg-gray-50 py-4 transition hover:border-blue-100 hover:bg-blue-50"><i class="fas fa-clipboard-list mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600"></i><span class="text-xs font-semibold text-gray-600">Kelola Program</span></a>
                <a href="{{ route('admin.ekstrakurikuler.index') }}" class="flex flex-col items-center rounded-xl border border-gray-100 bg-gray-50 py-4 transition hover:border-blue-100 hover:bg-blue-50"><i class="fas fa-person-running mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600"></i><span class="text-xs font-semibold text-gray-600">Ekstrakurikuler</span></a>
                <a href="{{ route('admin.pendaftaran.index') }}" class="flex flex-col items-center rounded-xl border border-gray-100 bg-gray-50 py-4 transition hover:border-blue-100 hover:bg-blue-50"><i class="fas fa-address-card mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600"></i><span class="text-xs font-semibold text-gray-600">Data PPDB</span></a>
            </div>
        </section>
    </div>
</x-layouts.admin>
