<x-layouts.app title="Informasi PPDB - SDN Pojok 2 Kota Kediri">
    <section class="bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-900 px-6 py-14 text-white sm:py-18">
        <div class="mx-auto max-w-6xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-blue-200">Penerimaan Peserta Didik Baru</p>
            <h1 class="mt-3 text-3xl font-extrabold leading-tight sm:text-5xl">Informasi <span class="text-blue-300">PPDB</span></h1>
            <p class="mt-4 max-w-2xl text-sm leading-relaxed text-blue-100 sm:text-base">Cek daftar siswa yang diterima di SDN Pojok 2. Jika nama tercantum, lanjutkan ke registrasi daftar ulang.</p>
        </div>
    </section>

    <main class="mx-auto max-w-6xl space-y-8 px-6 py-10 sm:py-12">
        <section aria-labelledby="accepted-students-heading" class="overflow-hidden rounded-3xl border border-blue-100 bg-white shadow-sm">
            <div class="flex flex-col gap-4 bg-blue-50/70 p-5 sm:flex-row sm:items-center sm:justify-between sm:p-7">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-700 text-xl text-white shadow-sm"><i class="fas fa-user-check"></i></div>
                    <div>
                        <h2 id="accepted-students-heading" class="text-xl font-bold text-gray-900 sm:text-2xl">Daftar Siswa Diterima</h2>
                        <p class="mt-1 text-sm text-gray-600">Daftar ini hanya menampilkan siswa pada periode PPDB yang sedang berjalan.</p>
                    </div>
                </div>
                <div class="inline-flex w-fit items-center gap-2 rounded-full border border-blue-100 bg-white px-4 py-2 text-sm font-semibold text-blue-800">
                    <i class="fas fa-users text-blue-600"></i>{{ number_format($siswaDiterima->total(), 0, ',', '.') }} siswa
                </div>
            </div>

            <div class="p-5 sm:p-7">
                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Periode PPDB</p>
                        <p class="mt-1 text-lg font-bold text-gray-900">{{ $tahunAjaran ?: 'Belum ada periode tersedia' }}</p>
                    </div>
                </div>
                @if ($siswaDiterima->count())
                    <div class="overflow-x-auto rounded-2xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-left">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="w-20 px-4 py-3 text-xs font-bold uppercase tracking-wide text-gray-500 sm:px-5">No</th>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-gray-500 sm:px-5">Nama Siswa</th>
                                    <th scope="col" class="px-4 py-3 text-xs font-bold uppercase tracking-wide text-gray-500 sm:px-5">Jenis Kelamin</th>
                                    <th scope="col" class="w-36 px-4 py-3 text-xs font-bold uppercase tracking-wide text-gray-500 sm:px-5">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($siswaDiterima as $index => $siswa)
                                    <tr class="transition hover:bg-blue-50/50">
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-semibold text-blue-700 sm:px-5">{{ $siswaDiterima->firstItem() + $index }}</td>
                                        <td class="break-words px-4 py-4 text-sm font-semibold text-gray-900 sm:px-5">{{ $siswa->nama_siswa }}</td>
                                        <td class="px-4 py-4 text-sm text-gray-600 sm:px-5">{{ $siswa->jenis_kelamin ?: '-' }}</td>
                                        <td class="px-4 py-4 sm:px-5"><span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">Diterima</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="rounded-2xl border border-dashed border-gray-300 px-5 py-10 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-lg text-gray-400"><i class="fas fa-clipboard-list"></i></div>
                        <h3 class="mt-3 font-semibold text-gray-800">Daftar belum tersedia</h3>
                        <p class="mt-1 text-sm text-gray-500">Informasi siswa diterima akan ditampilkan setelah sekolah memperbarui data PPDB.</p>
                    </div>
                @endif

                @if ($siswaDiterima->hasPages())
                    <nav aria-label="Navigasi halaman daftar siswa diterima" class="mt-6 border-t border-gray-100 pt-5">{{ $siswaDiterima->links() }}</nav>
                @endif
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
            <section aria-labelledby="ppdb-process-heading" class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-lg text-blue-700"><i class="fas fa-circle-info"></i></div>
                    <div>
                        <h2 id="ppdb-process-heading" class="text-xl font-bold text-gray-900">Alur PPDB</h2>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">Pendaftaran dan seleksi dilakukan melalui sistem resmi PPDB pemerintah. Setelah dinyatakan diterima, lakukan daftar ulang melalui situs sekolah.</p>
                    </div>
                </div>
                <ol class="mt-6 grid gap-3 sm:grid-cols-3">
                    <li class="rounded-2xl bg-gray-50 p-4"><span class="text-xs font-bold text-blue-700">LANGKAH 1</span><p class="mt-2 text-sm font-semibold leading-snug text-gray-800">Daftar melalui sistem PPDB pemerintah</p></li>
                    <li class="rounded-2xl bg-gray-50 p-4"><span class="text-xs font-bold text-blue-700">LANGKAH 2</span><p class="mt-2 text-sm font-semibold leading-snug text-gray-800">Cek nama pada daftar siswa diterima di atas</p></li>
                    <li class="rounded-2xl bg-gray-50 p-4"><span class="text-xs font-bold text-blue-700">LANGKAH 3</span><p class="mt-2 text-sm font-semibold leading-snug text-gray-800">Lengkapi registrasi daftar ulang</p></li>
                </ol>
                <p class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs leading-relaxed text-amber-900"><i class="fas fa-lock mr-1.5"></i>Daftar publik hanya memuat nama dan jenis kelamin. NIK dan NISN tidak ditampilkan.</p>
            </section>

            <aside class="flex flex-col justify-between rounded-3xl bg-blue-950 p-6 text-white shadow-sm sm:p-8">
                <div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-800 text-xl"><i class="fas fa-clipboard-check"></i></div>
                    <h2 class="mt-5 text-xl font-bold">Nama sudah tercantum?</h2>
                    <p class="mt-2 text-sm leading-relaxed text-blue-100">Siapkan data wali, nomor WhatsApp, Akta Kelahiran, dan Kartu Keluarga untuk melanjutkan daftar ulang.</p>
                </div>
                <a href="{{ route('registrasi.public.create') }}" class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-red-700">Registrasi Daftar Ulang <i class="fas fa-arrow-right"></i></a>
            </aside>
        </div>
    </main>
</x-layouts.app>
