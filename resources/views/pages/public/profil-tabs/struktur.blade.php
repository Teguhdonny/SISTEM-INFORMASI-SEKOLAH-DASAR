<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-8 pb-3 border-b border-gray-100">Struktur Organisasi Sekolah</h2>
    <div class="max-w-3xl mx-auto flex flex-col items-center">
        <!-- Kepala Sekolah -->
        <div class="bg-blue-700 text-white rounded-2xl px-8 py-4 shadow-md flex items-center gap-4 border-2 border-blue-800">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-lg"><i class="fas fa-user-tie"></i></div>
            <div>
                <p class="text-sm font-extrabold">Kepala Sekolah</p>
                <p class="text-[11px] text-blue-100">(Kepala SDN Pojok 2)</p>
            </div>
        </div>
        <div class="w-0.5 h-8 bg-blue-200"></div>
        <div class="w-full max-w-xl border-t-2 border-blue-200 hidden sm:block"></div>

        <!-- Level 2 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full mt-2 sm:mt-0">
            @foreach ([['ikon' => 'fa-users', 'bidang' => 'Bidang Kurikulum'], ['ikon' => 'fa-user-graduate', 'bidang' => 'Bidang Kesiswaan'], ['ikon' => 'fa-tools', 'bidang' => 'Bidang Sarana Prasarana']] as $koord)
                <div class="flex flex-col items-center">
                    <div class="w-0.5 h-4 bg-blue-200 hidden sm:block"></div>
                    <div class="w-full bg-blue-50/70 border border-blue-200 rounded-xl p-4 text-center shadow-sm">
                        <i class="fas {{ $koord['ikon'] }} text-blue-600 text-lg mb-1"></i>
                        <h4 class="text-xs font-extrabold text-blue-950">Koordinator</h4>
                        <p class="text-[11px] text-gray-600">{{ $koord['bidang'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="w-0.5 h-8 bg-blue-200"></div>
        <div class="w-full max-w-xl border-t-2 border-blue-200 hidden sm:block"></div>

        <!-- Level 3 -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full mt-2 sm:mt-0">
            @foreach ([['ikon' => 'fa-id-badge', 'jabatan' => 'Bendahara & TU', 'sub' => '(Tata Usaha & BOS)'], ['ikon' => 'fa-chalkboard-teacher', 'jabatan' => 'Dewan Guru', 'sub' => '(Guru Kelas & Mapel)'], ['ikon' => 'fa-user-cog', 'jabatan' => 'Tenaga Kependidikan', 'sub' => '(Layanan Khusus / Tendik)']] as $staf)
                <div class="flex flex-col items-center">
                    <div class="w-0.5 h-4 bg-blue-200 hidden sm:block"></div>
                    <div class="w-full bg-blue-50/40 border border-blue-100 rounded-xl p-4 text-center">
                        <i class="fas {{ $staf['ikon'] }} text-blue-600 text-lg mb-1"></i>
                        <h4 class="text-xs font-extrabold text-blue-950">{{ $staf['jabatan'] }}</h4>
                        <p class="text-[11px] text-gray-500">{{ $staf['sub'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>