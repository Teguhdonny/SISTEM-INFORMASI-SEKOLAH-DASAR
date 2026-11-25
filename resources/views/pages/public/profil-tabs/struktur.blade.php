<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-8 pb-3 border-b border-gray-100">Struktur Organisasi Sekolah</h2>
    @php
        $strukturOrganisasi = $profilSekolah?->struktur_organisasi ?: [
            ['tingkat' => 1, 'jabatan' => 'Kepala Sekolah', 'nama' => '', 'keterangan' => 'Kepala SDN Pojok 2'],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => '', 'keterangan' => 'Bidang Kurikulum'],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => '', 'keterangan' => 'Bidang Kesiswaan'],
            ['tingkat' => 2, 'jabatan' => 'Koordinator', 'nama' => '', 'keterangan' => 'Bidang Sarana Prasarana'],
            ['tingkat' => 3, 'jabatan' => 'Bendahara & TU', 'nama' => '', 'keterangan' => 'Tata Usaha & BOS'],
            ['tingkat' => 3, 'jabatan' => 'Dewan Guru', 'nama' => '', 'keterangan' => 'Guru Kelas & Mapel'],
            ['tingkat' => 3, 'jabatan' => 'Tenaga Kependidikan', 'nama' => '', 'keterangan' => 'Layanan Khusus / Tendik'],
        ];
        $strukturPerTingkat = collect($strukturOrganisasi)->groupBy('tingkat');
    @endphp
    <div class="max-w-3xl mx-auto flex flex-col items-center">
        @foreach ($strukturPerTingkat->get(1, collect()) as $pemimpin)
            <div class="bg-blue-700 text-white rounded-2xl px-8 py-4 shadow-md flex items-center gap-4 border-2 border-blue-800">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-lg"><i class="fas fa-user-tie"></i></div>
                <div>
                    <p class="text-sm font-extrabold">{{ $pemimpin['jabatan'] }}</p>
                    @if ($pemimpin['nama'])
                        <p class="text-[11px] text-blue-100">{{ $pemimpin['nama'] }}</p>
                    @endif
                    @if ($pemimpin['keterangan'])
                        <p class="text-[11px] text-blue-100">{{ $pemimpin['keterangan'] }}</p>
                    @endif
                </div>
            </div>
            @if (! $loop->last)<div class="w-0.5 h-4 bg-blue-200"></div>@endif
        @endforeach
        @if ($strukturPerTingkat->has(1))
            <div class="w-0.5 h-8 bg-blue-200"></div>
        @endif
        <div class="w-full max-w-xl border-t-2 border-blue-200 hidden sm:block"></div>

        <!-- Level 2 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full mt-2 sm:mt-0">
            @foreach ($strukturPerTingkat->get(2, collect()) as $koord)
                <div class="flex flex-col items-center">
                    <div class="w-0.5 h-4 bg-blue-200 hidden sm:block"></div>
                    <div class="w-full bg-blue-50/70 border border-blue-200 rounded-xl p-4 text-center shadow-sm">
                        <i class="fas fa-users text-blue-600 text-lg mb-1"></i>
                        <h4 class="text-xs font-extrabold text-blue-950">{{ $koord['jabatan'] }}</h4>
                        @if ($koord['nama'])<p class="text-[11px] text-gray-700">{{ $koord['nama'] }}</p>@endif
                        @if ($koord['keterangan'])<p class="text-[11px] text-gray-600">{{ $koord['keterangan'] }}</p>@endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($strukturPerTingkat->has(2))
            <div class="w-0.5 h-8 bg-blue-200"></div>
        @endif
        <div class="w-full max-w-xl border-t-2 border-blue-200 hidden sm:block"></div>

        <!-- Level 3 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full mt-2 sm:mt-0">
            @foreach ($strukturPerTingkat->get(3, collect()) as $staf)
                <div class="flex flex-col items-center">
                    <div class="w-0.5 h-4 bg-blue-200 hidden sm:block"></div>
                    <div class="w-full bg-blue-50/40 border border-blue-100 rounded-xl p-4 text-center">
                        <i class="fas fa-user-tie text-blue-600 text-lg mb-1"></i>
                        <h4 class="text-xs font-extrabold text-blue-950">{{ $staf['jabatan'] }}</h4>
                        @if ($staf['nama'])<p class="text-[11px] text-gray-700">{{ $staf['nama'] }}</p>@endif
                        @if ($staf['keterangan'])<p class="text-[11px] text-gray-500">{{ $staf['keterangan'] }}</p>@endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>