<x-layouts.admin>
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <p class="text-sm font-semibold text-blue-700">Pengelolaan PPDB</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-800">Jadwal Registrasi Daftar Ulang</h1>
            <p class="mt-1 text-sm text-gray-500">Pilih tanggal pembukaan dan penutupan formulir registrasi.</p>
        </div>

        @if (session('success'))
            <div role="status" class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800"><i class="fas fa-check-circle"></i>{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div role="alert" class="flex items-center gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-900"><i class="fas fa-circle-exclamation"></i>{{ session('error') }}</div>
        @endif

        <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6 flex items-start gap-4 rounded-xl {{ $registrationPeriod->acceptsRegistrationsAt(now('Asia/Jakarta')) ? 'bg-green-50 text-green-800' : 'bg-gray-50 text-gray-700' }} p-4">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white"><i class="fas {{ $registrationPeriod->acceptsRegistrationsAt(now('Asia/Jakarta')) ? 'fa-lock-open text-green-600' : 'fa-lock text-gray-500' }}"></i></span>
                <div>
                    <p class="font-bold">{{ $registrationPeriod->acceptsRegistrationsAt(now('Asia/Jakarta')) ? 'Formulir sedang dibuka' : 'Formulir sedang ditutup' }}</p>
                    <p class="mt-1 text-xs leading-relaxed">{{ $registrationPeriod->is_open ? 'Periode dijadwalkan aktif. Formulir dibuka pada tanggal mulai dan ditutup setelah tanggal tutup berakhir.' : 'Formulir publik tidak dapat dikirim sampai periode diaktifkan.' }}</p>
                </div>
            </div>

            <form action="{{ route('admin.registration-period.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="tahun_ajaran" class="mb-2 block text-sm font-semibold text-gray-700">Tahun ajaran PPDB <span class="text-red-600">*</span></label>
                    <input id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $registrationPeriod->tahun_ajaran) }}" required maxlength="9" pattern="[0-9]{4}/[0-9]{4}" placeholder="Contoh: 2026/2027" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 sm:max-w-sm">
                    <p class="mt-1 text-xs text-gray-500">Periode ini digunakan untuk mencocokkan pendaftar dengan data siswa PPDB.</p>
                    @error('tahun_ajaran') <p class="mt-1 text-xs text-red-600">Masukkan tahun ajaran seperti 2026/2027.</p> @enderror
                </div>

                <div class="grid gap-5 lg:grid-cols-2">
                    <fieldset class="space-y-3 rounded-xl border border-gray-200 p-4 sm:p-5">
                    <legend class="px-2 text-sm font-bold text-gray-800">Tanggal mulai</legend>
                        <div>
                            <label for="opens_on" class="mb-2 block text-xs font-semibold text-gray-600">Formulir mulai dibuka</label>
                            <input id="opens_on" name="opens_on" type="date" required value="{{ old('opens_on', $registrationPeriod->opens_at?->copy()->timezone('Asia/Jakarta')->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            @error('opens_on') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </fieldset>
                    <fieldset class="space-y-3 rounded-xl border border-gray-200 p-4 sm:p-5">
                        <legend class="px-2 text-sm font-bold text-gray-800">Tanggal tutup</legend>
                        <div>
                            <label for="closes_on" class="mb-2 block text-xs font-semibold text-gray-600">Formulir terakhir dibuka</label>
                            <input id="closes_on" name="closes_on" type="date" required value="{{ old('closes_on', $registrationPeriod->closes_at?->copy()->timezone('Asia/Jakarta')->format('Y-m-d')) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            @error('closes_on') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </fieldset>
                </div>

                <label for="is_open" class="flex cursor-pointer items-start gap-3 rounded-xl border border-gray-200 p-4 transition hover:bg-gray-50">
                    <input id="is_open" name="is_open" type="checkbox" value="1" @checked(old('is_open', $registrationPeriod->is_open)) class="mt-1 h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-blue-600">
                    <span><span class="block text-sm font-semibold text-gray-800">Aktifkan periode registrasi</span><span class="mt-1 block text-xs leading-relaxed text-gray-500">Jika aktif, formulir akan terbuka otomatis pada tanggal mulai dan tertutup setelah tanggal tutup berakhir. Anda dapat menonaktifkannya kapan saja.</span></span>
                </label>

                <div class="flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"><i class="fas fa-save mr-2"></i>Simpan Jadwal</button>
                </div>
            </form>
        </section>

        <p class="text-xs leading-relaxed text-gray-500"><i class="fas fa-calendar-day mr-1"></i>Tanggal dihitung dalam zona waktu WIB. Formulir mulai tersedia pada awal tanggal mulai dan ditutup setelah tanggal tutup berakhir.</p>
    </div>
</x-layouts.admin>
