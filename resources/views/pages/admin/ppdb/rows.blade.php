@forelse ($siswa as $index => $item)
    <tr class="hover:bg-gray-50">
        <td class="px-5 py-4 text-gray-500">{{ $siswa->firstItem() + $index }}</td>
        <td class="px-5 py-4 font-semibold text-gray-800">{{ $item->nama_siswa }}</td>
        <td class="px-5 py-4 text-sm text-gray-600">{{ $item->jenis_kelamin ?: '—' }}</td>
        <td class="px-5 py-4 font-mono text-gray-600">{{ $item->nik ?: '—' }}</td>
        <td class="px-5 py-4 font-mono text-gray-600">{{ $item->nisn ?: '—' }}</td>
        <td class="px-5 py-4 font-semibold text-gray-700">{{ $item->tahun_ajaran ?: 'Belum diatur' }}</td>
        <td class="px-5 py-4">
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.ppdb.edit', $item) }}" class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100" aria-label="Ubah data {{ $item->nama_siswa }}"><i class="fas fa-pen"></i></a>
                <form action="{{ route('admin.ppdb.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data siswa {{ addslashes($item->nama_siswa) }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus data {{ $item->nama_siswa }}"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-5 py-12 text-center text-gray-500">
            {{ $search ? 'Siswa tidak ditemukan. Coba kata pencarian lain.' : 'Belum ada data siswa PPDB.' }}
        </td>
    </tr>
@endforelse
