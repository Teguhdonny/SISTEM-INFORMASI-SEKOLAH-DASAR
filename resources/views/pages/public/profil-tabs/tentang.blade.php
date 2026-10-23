<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
        <div class="flex flex-col md:flex-row gap-8 items-center">
            <div class="w-full md:w-2/5 shrink-0">
                <div class="rounded-xl overflow-hidden bg-gray-200 aspect-[4/3] flex items-center justify-center border border-gray-100 shadow-inner">
                    <img src="{{ asset('images/sekolah.jpg') }}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" alt="Gedung SDN Pojok 2" class="w-full h-full object-cover">
                    <div style="display: none;" class="w-full h-full flex-col items-center justify-center text-gray-400 p-6 text-center">
                        <i class="fas fa-school text-4xl mb-2"></i>
                        <span class="text-xs">[Foto Gedung Sekolah]</span>
                    </div>
                </div>
            </div>
            <div class="flex-1 space-y-5">
                <div>
                    <h2 class="text-xl font-extrabold text-blue-950 mb-2">Tentang Sekolah</h2>
                    <p class="text-gray-600 text-sm leading-relaxed border-b border-gray-100 pb-4">
                        SDN Pojok 2 Kota Kediri adalah sekolah dasar negeri yang berkomitmen untuk memberikan pendidikan berkualitas dengan lingkungan belajar yang aman, nyaman, dan berprestasi.
                    </p>
                </div>
                <div class="bg-blue-50/50 rounded-xl p-4 border border-blue-100/80 grid grid-cols-2 sm:grid-cols-4 gap-4 divide-x divide-blue-100 text-center">
                    <div class="px-2">
                        <i class="fas fa-users text-blue-700 text-lg mb-1"></i>
                        <p class="text-[11px] text-gray-500">Jumlah Siswa</p>
                        <p class="text-base font-extrabold text-blue-950 mt-0.5">&plusmn; 320</p>
                    </div>
                    <div class="px-2">
                        <i class="fas fa-chalkboard-teacher text-blue-700 text-lg mb-1"></i>
                        <p class="text-[11px] text-gray-500">Jumlah Guru</p>
                        <p class="text-base font-extrabold text-blue-950 mt-0.5">24</p>
                    </div>
                    <div class="px-2">
                        <i class="fas fa-building text-blue-700 text-lg mb-1"></i>
                        <p class="text-[11px] text-gray-500">Tahun Berdiri</p>
                        <p class="text-base font-extrabold text-blue-950 mt-0.5">1982</p>
                    </div>
                    <div class="px-2">
                        <i class="fas fa-map-marker-alt text-blue-700 text-lg mb-1"></i>
                        <p class="text-[11px] text-gray-500">Status</p>
                        <p class="text-base font-extrabold text-blue-950 mt-0.5">Negeri</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="w-9 h-9 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0"><i class="fas fa-bullseye"></i></div>
                <h3 class="font-extrabold text-blue-950 text-sm">Visi Sekolah</h3>
            </div>
            <p class="text-gray-600 text-xs italic leading-relaxed">
                “{{ $visi }}”
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="w-9 h-9 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><i class="fas fa-list-ul"></i></div>
                <h3 class="font-extrabold text-blue-950 text-sm">Misi Sekolah</h3>
            </div>
            <ul class="space-y-2.5 text-xs text-gray-600">@foreach ($misi as $deskripsiMisi)<li class="flex items-start gap-2.5"><span class="w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-bold flex items-center justify-center shrink-0 mt-0.5">{{ $loop->iteration }}</span><span>{{ $deskripsiMisi }}</span></li>@endforeach</ul>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4 pb-3 border-b border-gray-100">
                <div class="w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><i class="fas fa-leaf"></i></div>
                <h3 class="font-extrabold text-blue-950 text-sm">Nilai-Nilai Sekolah</h3>
            </div>
            <ul class="space-y-2 text-xs text-gray-600">
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> Religius & Berakhlak Mulia</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> Mandiri & Disiplin</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> Kreatif & Tangguh</li>
                <li class="flex items-center gap-2"><i class="fas fa-check-circle text-emerald-500"></i> Peduli Lingkungan</li>
            </ul>
        </div>
    </div>
</div>
