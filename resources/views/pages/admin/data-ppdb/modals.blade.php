@foreach ($pendaftars as $item)
    <div x-show="activeModal === {{ $item->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4 overflow-y-auto" style="display: none;">
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-8 relative">
            <button @click="activeModal = null" class="absolute top-6 right-6 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center font-bold transition" aria-label="Tutup detail">
                <i class="fas fa-times"></i>
            </button>

            <h3 class="text-xl font-bold text-gray-900 mb-1">Detail Pendaftar PPDB</h3>
            <p class="text-xs text-gray-500 mb-6">Periksa data diri dan dokumen pendukung siswa sebelum melakukan verifikasi.</p>

            <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 space-y-3 text-xs mb-6">
                <div class="grid grid-cols-2 gap-3">
                    <div><span class="text-gray-400 block mb-0.5">No. Pendaftaran:</span> <strong class="text-gray-800 text-sm font-mono">{{ $item->no_pendaftaran }}</strong></div>
                    <div><span class="text-gray-400 block mb-0.5">NISN:</span> <strong class="text-gray-800 text-sm font-mono">{{ $item->nisn }}</strong></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><span class="text-gray-400 block mb-0.5">Nama Lengkap:</span> <strong class="text-gray-800 text-sm">{{ $item->nama_siswa }}</strong></div>
                    <div><span class="text-gray-400 block mb-0.5">Jenis Kelamin:</span> <strong class="text-gray-800 text-sm">{{ $item->jenis_kelamin ?: '-' }}</strong></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div><span class="text-gray-400 block mb-0.5">Asal Sekolah:</span> <strong class="text-gray-800 text-sm">{{ $item->asal_sekolah }}</strong></div>
                    <div><span class="text-gray-400 block mb-0.5">Nama Wali:</span> <strong class="text-gray-800 text-sm">{{ $item->daftarUlang->nama_wali ?? '-' }}</strong></div>
                </div>
                <div><span class="text-gray-400 block mb-0.5">No. WhatsApp Wali:</span> <strong class="text-gray-800 text-sm font-mono">{{ $item->daftarUlang->no_whatsapp ?? '-' }}</strong></div>
            </div>

            <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Dokumen Lampiran</h4>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="border border-gray-200 rounded-2xl p-4 text-center bg-gray-50">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Akta Kelahiran</p>
                    @if ($item->daftarUlang->file_akta)
                        <a href="{{ asset('storage/'.$item->daftarUlang->file_akta) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold bg-blue-50 px-3.5 py-2 rounded-xl inline-block border border-blue-200 shadow-sm transition"><i class="fas fa-file-pdf mr-1 text-red-500"></i> Lihat Akta</a>
                    @else
                        <span class="text-xs text-rose-500 italic">Tidak ada file</span>
                    @endif
                </div>
                <div class="border border-gray-200 rounded-2xl p-4 text-center bg-gray-50">
                    <p class="text-xs font-semibold text-gray-700 mb-2">Kartu Keluarga (KK)</p>
                    @if ($item->daftarUlang->file_kk)
                        <a href="{{ asset('storage/'.$item->daftarUlang->file_kk) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold bg-blue-50 px-3.5 py-2 rounded-xl inline-block border border-blue-200 shadow-sm transition"><i class="fas fa-file-pdf mr-1 text-red-500"></i> Lihat KK</a>
                    @else
                        <span class="text-xs text-rose-500 italic">Tidak ada file</span>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.pendaftaran.status', $item->id) }}" method="POST" class="space-y-4 pt-4 border-t border-gray-200">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Ubah Status Verifikasi Admin</label>
                    <select name="status" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none transition">
                        @foreach (['Menunggu Verifikasi', 'Diterima', 'Ditolak'] as $statusOption)
                            <option value="{{ $statusOption }}" @selected(($item->daftarUlang->status ?? 'Menunggu Verifikasi') === $statusOption)>{{ $statusOption }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="activeModal = null" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-xs transition">Batal</button>
                    <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-xs shadow-md transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach
