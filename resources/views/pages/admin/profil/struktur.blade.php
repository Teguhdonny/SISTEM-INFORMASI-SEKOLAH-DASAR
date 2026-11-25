<section x-show="activeTab === 'struktur'" style="display: none;" class="space-y-6">
    <div>
        <p class="text-xs font-bold text-blue-600">Profil Sekolah</p>
        <h2 class="mt-1 text-xl font-extrabold text-gray-900">Struktur Organisasi</h2>
        <p class="mt-1 text-xs text-gray-500">Susunan jabatan sekolah berdasarkan tingkat organisasi.</p>
    </div>

    @foreach ([1 => 'Pimpinan', 2 => 'Koordinator', 3 => 'Pelaksana'] as $tingkat => $judul)
        @php($anggota = collect($strukturOrganisasi)->where('tingkat', $tingkat))
        @if ($anggota->isNotEmpty())
            <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="mb-4 font-bold text-gray-800">{{ $judul }}</h3>
                <div class="grid gap-3 md:grid-cols-2">
                    @foreach ($anggota as $item)
                        <article class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                            <h4 class="font-bold text-blue-950">{{ $item['jabatan'] }}</h4>
                            @if ($item['nama'])<p class="mt-1 text-sm text-gray-700">{{ $item['nama'] }}</p>@endif
                            @if ($item['keterangan'])<p class="mt-1 text-xs text-gray-500">{{ $item['keterangan'] }}</p>@endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    @endforeach
</section>
