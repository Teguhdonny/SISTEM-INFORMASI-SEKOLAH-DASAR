@foreach ($pendaftars as $item)
    @php
        $statusVerifikasi = $item->daftarUlang?->status ?? 'Menunggu Verifikasi';
        $aktaUrl = $item->daftarUlang?->file_akta ? asset('storage/'.$item->daftarUlang->file_akta) : null;
        $kkUrl = $item->daftarUlang?->file_kk ? asset('storage/'.$item->daftarUlang->file_kk) : null;
        $aktaType = $item->daftarUlang?->file_akta && str_ends_with(strtolower($item->daftarUlang->file_akta), '.pdf') ? 'pdf' : 'image';
        $kkType = $item->daftarUlang?->file_kk && str_ends_with(strtolower($item->daftarUlang->file_kk), '.pdf') ? 'pdf' : 'image';
    @endphp

    <div x-show="activeModal === {{ $item->id }}" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm" style="display: none;">
        <div x-data="{ previewUrl: null, previewTitle: '', previewType: 'pdf' }" class="relative max-h-[calc(100vh-2rem)] w-full max-w-6xl overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
            <button @click="activeModal = null" class="absolute right-6 top-6 z-10 flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 font-bold text-gray-500 transition hover:bg-gray-200" aria-label="Tutup detail">
                <i class="fas fa-times"></i>
            </button>

            <div class="mb-6 pr-10">
                <h3 class="text-xl font-bold text-gray-900">Detail Pendaftar PPDB</h3>
                <p class="mt-1 text-xs text-gray-500">Periksa data diri dan dokumen pendukung siswa sebelum melakukan verifikasi.</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(22rem,0.95fr)]">
                <div class="min-w-0">
                    <div class="mb-6 space-y-3 rounded-2xl border border-gray-200 bg-gray-50 p-5 text-xs">
                        <div class="grid grid-cols-2 gap-3">
                            <div><span class="mb-0.5 block text-gray-400">No. Pendaftaran:</span> <strong class="text-sm font-mono text-gray-800">{{ $item->no_pendaftaran }}</strong></div>
                            <div><span class="mb-0.5 block text-gray-400">NISN:</span> <strong class="text-sm font-mono text-gray-800">{{ $item->nisn }}</strong></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><span class="mb-0.5 block text-gray-400">Nama Lengkap:</span> <strong class="text-sm text-gray-800">{{ $item->nama_siswa }}</strong></div>
                            <div><span class="mb-0.5 block text-gray-400">Jenis Kelamin:</span> <strong class="text-sm text-gray-800">{{ $item->jenis_kelamin ?: '-' }}</strong></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div><span class="mb-0.5 block text-gray-400">Asal Sekolah:</span> <strong class="text-sm text-gray-800">{{ $item->asal_sekolah }}</strong></div>
                            <div><span class="mb-0.5 block text-gray-400">Nama Wali:</span> <strong class="text-sm text-gray-800">{{ $item->daftarUlang->nama_wali ?? '-' }}</strong></div>
                        </div>
                        <div><span class="mb-0.5 block text-gray-400">No. WhatsApp Wali:</span> <strong class="text-sm font-mono text-gray-800">{{ $item->daftarUlang->no_whatsapp ?? '-' }}</strong></div>
                    </div>

                    <h4 class="mb-3 text-xs font-bold uppercase tracking-wider text-gray-800">Dokumen Lampiran</h4>
                    <div class="mb-6 grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <p class="mb-2 text-xs font-semibold text-gray-700">Akta Kelahiran</p>
                            @if ($aktaUrl)
                                <button type="button" @click="previewUrl = @js($aktaUrl); previewTitle = 'Akta Kelahiran'; previewType = @js($aktaType)" class="inline-block rounded-xl border border-blue-200 bg-blue-50 px-3.5 py-2 text-xs font-bold text-blue-600 shadow-sm transition hover:bg-blue-100">
                                    <i class="fas fa-eye mr-1 text-blue-500"></i> Lihat Akta
                                </button>
                            @else
                                <span class="text-xs italic text-rose-500">Tidak ada file</span>
                            @endif
                        </div>
                        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 text-center">
                            <p class="mb-2 text-xs font-semibold text-gray-700">Kartu Keluarga (KK)</p>
                            @if ($kkUrl)
                                <button type="button" @click="previewUrl = @js($kkUrl); previewTitle = 'Kartu Keluarga (KK)'; previewType = @js($kkType)" class="inline-block rounded-xl border border-blue-200 bg-blue-50 px-3.5 py-2 text-xs font-bold text-blue-600 shadow-sm transition hover:bg-blue-100">
                                    <i class="fas fa-eye mr-1 text-blue-500"></i> Lihat KK
                                </button>
                            @else
                                <span class="text-xs italic text-rose-500">Tidak ada file</span>
                            @endif
                        </div>
                    </div>

                    @if ($statusVerifikasi === 'Menunggu Verifikasi')
                        <form action="{{ route('admin.pendaftaran.status', $item->id) }}" method="POST" class="space-y-4 border-t border-gray-200 pt-4">
                            @csrf
                            <div>
                                <label class="mb-1.5 block text-xs font-bold text-gray-700">Tentukan Status Verifikasi Admin</label>
                                <select name="status" required class="w-full rounded-xl border border-gray-300 bg-white p-3 text-xs font-semibold text-gray-800 outline-none transition focus:ring-2 focus:ring-blue-500">
                                    <option value="">Pilih status akhir</option>
                                    @foreach (['Diterima', 'Ditolak'] as $statusOption)
                                        <option value="{{ $statusOption }}">{{ $statusOption }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-3 pt-2">
                                <button type="button" @click="activeModal = null" class="w-1/2 rounded-xl bg-gray-100 py-3 text-xs font-bold text-gray-700 transition hover:bg-gray-200">Batal</button>
                                <button type="submit" class="w-1/2 rounded-xl bg-blue-600 py-3 text-xs font-bold text-white shadow-md transition hover:bg-blue-700">Simpan Status</button>
                            </div>
                            <p class="text-[11px] leading-5 text-amber-700">Status yang sudah disimpan tidak dapat diubah lagi.</p>
                        </form>
                    @else
                        <div class="space-y-2 border-t border-gray-200 pt-4">
                            <p class="text-xs font-bold text-gray-700">Status Verifikasi</p>
                            <p class="inline-flex rounded-full border border-blue-200 bg-blue-50 px-3.5 py-1.5 text-xs font-semibold text-blue-800">{{ $statusVerifikasi }}</p>
                            <p class="text-[11px] leading-5 text-gray-500">Status sudah ditentukan dan dikunci sehingga tidak dapat diubah lagi.</p>
                        </div>
                    @endif
                </div>

                <div class="min-w-0 rounded-2xl border border-gray-200 bg-gray-50 p-4">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-800">Preview Dokumen</h4>
                        <span x-show="previewUrl" x-text="previewTitle" class="truncate text-[11px] font-semibold text-blue-600"></span>
                    </div>
                    <div class="flex min-h-[28rem] items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-white">
                        <div x-show="! previewUrl" class="px-6 text-center text-gray-400">
                            <i class="fas fa-file-image mb-3 text-3xl"></i>
                            <p class="text-xs">Pilih tombol Lihat Akta atau Lihat KK untuk menampilkan dokumen di sini.</p>
                        </div>
                        <iframe x-show="previewUrl && previewType === 'pdf'" x-bind:src="previewUrl" title="Preview dokumen" class="h-[28rem] w-full" loading="lazy"></iframe>
                        <img x-show="previewUrl && previewType === 'image'" x-bind:src="previewUrl" x-bind:alt="previewTitle" class="max-h-[28rem] max-w-full object-contain" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
