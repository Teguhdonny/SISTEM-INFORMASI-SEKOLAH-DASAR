<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold text-blue-700">Konten Website</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Daftar Guru & Staff</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola profil tenaga pendidik yang tampil di website sekolah.</p>
            </div>
            <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700">
                <i class="fas fa-plus mr-2"></i>Tambah Guru
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-4">
            <div class="flex items-center gap-4 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-700"><i class="fas fa-users"></i></div>
                <div><p class="text-sm text-gray-500">Total Data Guru</p><p class="text-2xl font-bold text-gray-800">{{ $totalGuru }}</p></div>
            </div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-bold text-gray-800">Semua Guru</h2>
                    <p class="mt-1 text-xs text-gray-500">{{ $guru->total() }} data tenaga pendidik</p>
                </div>
                <form method="GET" action="{{ route('admin.guru.index') }}" class="flex gap-2">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, NIP, bagian, email" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none sm:w-72">
                    <button class="rounded-xl bg-blue-700 px-4 text-white transition hover:bg-blue-800" aria-label="Cari"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr><th class="px-5 py-4 font-semibold">Nama</th><th class="px-5 py-4 font-semibold">NIP</th><th class="px-5 py-4 font-semibold">Lulusan</th><th class="px-5 py-4 font-semibold">Bagian</th><th class="px-5 py-4 font-semibold">Email</th><th class="px-5 py-4 text-right font-semibold">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($guru as $item)
                            <tr class="transition hover:bg-gray-50/70">
                                <td class="px-5 py-4"><div class="flex items-center gap-3">
                                    @if ($item->foto)
                                        <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->nama }}" class="h-11 w-11 rounded-xl object-cover">
                                    @else
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-user-tie"></i></div>
                                    @endif
                                    <span class="font-semibold text-gray-800">{{ $item->nama }}</span>
                                </div></td>
                                <td class="px-5 py-4 text-gray-600">{{ $item->nip ?: '—' }}</td>
                                <td class="whitespace-pre-line px-5 py-4 text-gray-600">{{ $item->lulusan ?: '—' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $item->bagian }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $item->email ?: '—' }}</td>
                                <td class="px-5 py-4"><div class="flex justify-end gap-2">
                                    <a href="{{ route('admin.guru.edit', $item) }}" class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-700 transition hover:bg-blue-100" aria-label="Ubah {{ $item->nama }}"><i class="fas fa-pen"></i></a>
                                    <form method="POST" action="{{ route('admin.guru.destroy', $item) }}" onsubmit="return confirm('Hapus data {{ $item->nama }}?')">
                                        @csrf @method('DELETE')
                                        <button class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus {{ $item->nama }}"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-14 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600"><i class="fas fa-user-friends"></i></div>
                                <p class="mt-4 font-semibold text-gray-800">{{ $search ? 'Guru tidak ditemukan' : 'Belum ada data guru' }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ $search ? 'Coba gunakan kata pencarian lain.' : 'Tambahkan data guru untuk mulai mengisi daftar.' }}</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($guru->hasPages())
                <div class="border-t border-gray-100 px-5 py-4">{{ $guru->links() }}</div>
            @endif
        </section>
    </div>
</x-layouts.admin>
