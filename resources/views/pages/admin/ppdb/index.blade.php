<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Manajemen PPDB</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Siswa Diterima PPDB</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola data siswa dari hasil PPDB pemerintah. NIK dan NISN hanya dapat dilihat admin.</p>
            </div>
            <a href="{{ route('admin.ppdb.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"><i class="fas fa-plus"></i>Tambah Siswa</a>
        </div>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500">{{ $siswa->total() }} siswa tercatat</p>
                <form method="GET" action="{{ route('admin.ppdb.index') }}" class="flex gap-2">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari nama, NIK, NISN, tahun ajaran" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm sm:w-72">
                    <button class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white">Cari</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-5 py-3">No.</th><th class="px-5 py-3">Nama Siswa</th><th class="px-5 py-3">NIK</th><th class="px-5 py-3">NISN</th><th class="px-5 py-3">Tahun Ajaran</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($siswa as $index => $item)
                            <tr class="hover:bg-gray-50"><td class="px-5 py-4 text-gray-500">{{ $siswa->firstItem() + $index }}</td><td class="px-5 py-4 font-semibold text-gray-800">{{ $item->nama_siswa }}</td><td class="px-5 py-4 font-mono text-gray-600">{{ $item->nik ?: '—' }}</td><td class="px-5 py-4 font-mono text-gray-600">{{ $item->nisn ?: '—' }}</td><td class="px-5 py-4 font-semibold text-gray-700">{{ $item->tahun_ajaran ?: 'Belum diatur' }}</td><td class="px-5 py-4"><div class="flex justify-end gap-2"><a href="{{ route('admin.ppdb.edit', $item) }}" class="rounded-lg border border-blue-200 px-3 py-2 text-xs font-semibold text-blue-700 hover:bg-blue-50">Ubah</a><form action="{{ route('admin.ppdb.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data siswa {{ addslashes($item->nama_siswa) }}?')">@csrf @method('DELETE')<button class="rounded-lg border border-red-200 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-50">Hapus</button></form></div></td></tr>
                        @empty
                            <tr><td colspan="6" class="px-5 py-12 text-center text-gray-500">Belum ada data siswa PPDB.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($siswa->hasPages()) <div class="border-t border-gray-100 p-4">{{ $siswa->links() }}</div> @endif
        </div>
    </div>
</x-layouts.admin>
