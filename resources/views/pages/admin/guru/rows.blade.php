@forelse ($guru as $item)
    <tr class="transition hover:bg-gray-50/70">
        <td class="px-5 py-4">
            <div class="flex items-center gap-3">
                @if ($item->foto)
                    <img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->nama }}" class="h-11 w-11 rounded-xl object-cover">
                @else
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600"><i class="fas fa-user-tie"></i></div>
                @endif
                <span class="font-semibold text-gray-800">{{ $item->nama }}</span>
            </div>
        </td>
        <td class="px-5 py-4 text-gray-600">{{ $item->nip ?: '—' }}</td>
        <td class="px-5 py-4 text-gray-600">{{ $item->bagian }}</td>
        @php($pendidikan = collect(preg_split('/\R/', (string) $item->lulusan, -1, PREG_SPLIT_NO_EMPTY))->map(fn (string $jenjang): string => trim($jenjang))->filter())
        <td class="px-5 py-4 text-gray-600">
            @if ($pendidikan->isNotEmpty())
                <ul class="list-disc space-y-0.5 pl-4">
                    @foreach ($pendidikan as $jenjang)
                        <li>{{ $jenjang }}</li>
                    @endforeach
                </ul>
            @else
                —
            @endif
        </td>
        <td class="px-5 py-4">
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.guru.edit', $item) }}" class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-600 transition hover:bg-blue-100" aria-label="Ubah {{ $item->nama }}"><i class="fas fa-pen"></i></a>
                <form method="POST" action="{{ route('admin.guru.destroy', $item) }}" onsubmit="return confirm('Hapus data {{ $item->nama }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus {{ $item->nama }}"><i class="fas fa-trash"></i></button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="px-5 py-14 text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50 text-xl text-blue-600"><i class="fas fa-user-friends"></i></div>
            <p class="mt-4 font-semibold text-gray-800">{{ $search ? 'Guru tidak ditemukan' : 'Belum ada data guru' }}</p>
            <p class="mt-1 text-sm text-gray-500">{{ $search ? 'Coba gunakan kata pencarian lain.' : 'Tambahkan data guru untuk mulai mengisi daftar.' }}</p>
        </td>
    </tr>
@endforelse
