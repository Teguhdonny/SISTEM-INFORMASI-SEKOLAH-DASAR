@forelse ($pendaftars as $index => $item)
    @php
        $statusVerifikasi = $item->daftarUlang->status ?? 'Menunggu Verifikasi';
        $badgeColor = 'bg-amber-100 text-amber-800 border border-amber-200';
        if ($statusVerifikasi === 'Diterima') {
            $badgeColor = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
        }
        if ($statusVerifikasi === 'Ditolak') {
            $badgeColor = 'bg-rose-100 text-rose-800 border border-rose-200';
        }
    @endphp
    <tr class="hover:bg-blue-50/40 transition">
        <td class="px-6 py-4 font-medium text-gray-500">{{ $index + 1 }}</td>
        <td class="px-6 py-4 font-bold text-gray-900">{{ $item->nama_siswa }}</td>
        <td class="px-6 py-4 text-gray-600 font-mono">{{ $item->nisn }}</td>
        <td class="px-6 py-4 text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</td>
        <td class="px-6 py-4">
            <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-[11px] font-semibold border border-blue-100">{{ $item->jenis_kelamin ?: '-' }}</span>
        </td>
        <td class="px-6 py-4">
            <span class="px-3.5 py-1 inline-flex text-[11px] font-semibold rounded-full {{ $badgeColor }}">{{ $statusVerifikasi }}</span>
        </td>
        <td class="px-6 py-4 text-center">
            <button @click="activeModal = {{ $item->id }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition inline-flex items-center gap-1.5">
                <i class="fas fa-eye text-[10px]"></i> Detail & Verifikasi
            </button>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
            <i class="fas fa-folder-open text-3xl mb-2"></i>
            <p class="text-sm">{{ $search || $status ? 'Tidak ada pendaftar yang sesuai filter.' : 'Belum ada data pendaftar masuk ke sistem.' }}</p>
        </td>
    </tr>
@endforelse
