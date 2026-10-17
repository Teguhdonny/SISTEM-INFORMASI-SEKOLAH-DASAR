<x-layouts.admin>
    <div class="space-y-6" x-data="{ query: '', category: 'Semua' }">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div><p class="text-sm font-semibold text-blue-700">Konten Website</p><h1 class="mt-1 text-2xl font-bold text-gray-800">Program Sekolah</h1><p class="mt-1 text-sm text-gray-500">Kelola program pembelajaran dan kegiatan unggulan sekolah.</p></div>
            <button type="button" onclick="window.alert('Form tambah program akan tersedia saat data mulai disimpan ke database.')" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700"><i class="fas fa-plus mr-2"></i>Tambah Program</button>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm text-gray-500">Total Program</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i class="fas fa-clipboard-list"></i></span></div><p class="mt-3 text-3xl font-black text-gray-800">{{ count($programs) }}</p><p class="mt-1 text-xs text-gray-500">Data contoh saat ini</p></div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm text-gray-500">Program Aktif</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-50 text-green-700"><i class="fas fa-circle-check"></i></span></div><p class="mt-3 text-3xl font-black text-gray-800">{{ collect($programs)->where('status', 'Aktif')->count() }}</p><p class="mt-1 text-xs text-gray-500">Tampil untuk publik</p></div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm text-gray-500">Kategori</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i class="fas fa-layer-group"></i></span></div><p class="mt-3 text-3xl font-black text-gray-800">{{ collect($programs)->pluck('kategori')->unique()->count() }}</p><p class="mt-1 text-xs text-gray-500">Bidang kegiatan</p></div>
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><p class="text-sm text-gray-500">Perkiraan Peserta</p><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600"><i class="fas fa-people-group"></i></span></div><p class="mt-3 text-3xl font-black text-gray-800">186</p><p class="mt-1 text-xs text-gray-500">Siswa SDN Pojok 2</p></div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 p-5 lg:flex-row lg:items-center lg:justify-between">
                <div><h2 class="font-bold text-gray-800">Daftar Program</h2><p class="mt-1 text-xs text-gray-500">Atur konten dan status publikasi program sekolah.</p></div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <label class="relative"><span class="sr-only">Cari program</span><i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i><input x-model="query" type="search" placeholder="Cari nama program" class="w-full rounded-xl border border-gray-200 py-2.5 pl-9 pr-4 text-sm focus:border-blue-500 focus:outline-none sm:w-56"></label>
                    <label><span class="sr-only">Filter kategori</span><select x-model="category" class="w-full rounded-xl border border-gray-200 px-3 py-2.5 text-sm text-gray-600 focus:border-blue-500 focus:outline-none sm:w-44"><option>Semua</option>@foreach (collect($programs)->pluck('kategori')->unique() as $category)<option>{{ $category }}</option>@endforeach</select></label>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-5 py-4 font-semibold">Nama Program</th><th class="px-5 py-4 font-semibold">Kategori</th><th class="px-5 py-4 font-semibold">Jadwal</th><th class="px-5 py-4 font-semibold">Peserta</th><th class="px-5 py-4 font-semibold">Status</th><th class="px-5 py-4 text-right font-semibold">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($programs as $program)
                            <tr x-show="('{{ strtolower($program['judul']) }}'.includes(query.toLowerCase())) && (category === 'Semua' || category === '{{ $program['kategori'] }}')" class="transition hover:bg-gray-50/70">
                                <td class="px-5 py-4"><div class="flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i class="fas fa-book-open"></i></div><span class="font-semibold text-gray-800">{{ $program['judul'] }}</span></div></td>
                                <td class="px-5 py-4"><span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">{{ $program['kategori'] }}</span></td>
                                <td class="px-5 py-4 text-gray-600">{{ $program['jadwal'] }}</td><td class="px-5 py-4 text-gray-600">{{ $program['peserta'] }} siswa</td>
                                <td class="px-5 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $program['status'] === 'Aktif' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}"><i class="fas {{ $program['status'] === 'Aktif' ? 'fa-circle-check' : 'fa-clock' }} mr-1"></i>{{ $program['status'] }}</span></td>
                                <td class="px-5 py-4"><div class="flex justify-end gap-2"><button type="button" onclick="window.alert('Aksi ubah akan tersedia saat program tersimpan ke database.')" aria-label="Ubah {{ $program['judul'] }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition hover:bg-blue-100"><i class="fas fa-pen"></i></button><button type="button" onclick="window.alert('Aksi hapus akan tersedia saat program tersimpan ke database.')" aria-label="Hapus {{ $program['judul'] }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100"><i class="fas fa-trash"></i></button></div></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col justify-between gap-2 border-t border-gray-100 px-5 py-4 text-xs text-gray-500 sm:flex-row"><span>Menampilkan {{ count($programs) }} data contoh</span><span>Perubahan belum disimpan ke database</span></div>
        </section>
    </div>
</x-layouts.admin>
