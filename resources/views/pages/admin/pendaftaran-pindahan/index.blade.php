<x-layouts.admin>
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold text-blue-700">Pengelolaan Siswa</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-800">Pendaftaran Murid Pindahan</h1>
            <p class="mt-1 text-sm text-gray-500">Periksa data dan dokumen, lalu perbarui status pengajuan.</p>
        </div>

        @if (session('success'))<div role="status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>@endif
        @if (session('warning'))<div role="alert" class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">{{ session('warning') }}</div>@endif
        @if ($errors->any())<div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ $errors->first() }}</div>@endif

        <section class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.pendaftaran-pindahan.index') }}" class="grid gap-4 md:grid-cols-[1fr_240px_auto] md:items-end">
                <div><label for="search" class="mb-2 block text-sm font-semibold text-gray-700">Cari pengajuan</label><input id="search" name="search" value="{{ $search }}" placeholder="Nama, NISN, nomor pendaftaran, sekolah asal" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"></div>
                <div><label for="status" class="mb-2 block text-sm font-semibold text-gray-700">Status</label><select id="status" name="status" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm"><option value="">Semua status</option>@foreach ($filterStatuses as $option)<option value="{{ $option }}" @selected($status === $option)>{{ $option }}</option>@endforeach</select></div>
                <button type="submit" class="rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white hover:bg-blue-800">Terapkan Filter</button>
            </form>
        </section>

        <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
            <form x-data="{ fileName: '' }" method="POST" action="{{ route('admin.pendaftaran-pindahan.import.xlsx') }}" enctype="multipart/form-data" class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-end xl:flex-1">
                @csrf
                <div class="min-w-0 flex-1">
                    <input id="pindahan-import-file" x-ref="importFile" @change="fileName = $event.target.files[0]?.name ?? ''" name="file" type="file" accept=".xlsx" required class="sr-only">
                    <p x-show="fileName" x-text="fileName" class="truncate text-xs text-gray-600" aria-live="polite"></p>
                </div>
                <div class="flex shrink-0 gap-2">
                    <a href="{{ route('admin.pendaftaran-pindahan.template.xlsx') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-xl border border-gray-200 px-4 py-3 text-xs font-bold text-gray-700 hover:bg-gray-50">Template</a>
                    <button x-show="! fileName" type="button" @click="$refs.importFile.click()" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-blue-700 px-4 py-3 text-xs font-bold text-white hover:bg-blue-800"><i class="fas fa-upload"></i>Impor</button>
                    <button x-show="fileName" type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-blue-700 px-4 py-3 text-xs font-bold text-white hover:bg-blue-800"><i class="fas fa-upload"></i>Impor</button>
                </div>
            </form>
            <div class="flex shrink-0 gap-2 border-t border-gray-100 pt-4 xl:border-l xl:border-t-0 xl:pl-4 xl:pt-0">
                <a href="{{ route('admin.pendaftaran-pindahan.export.pdf', request()->only(['search', 'status'])) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 px-4 py-3 text-sm font-bold text-gray-700 hover:bg-gray-50"><i class="fas fa-file-pdf text-red-600"></i>PDF</a>
                <a href="{{ route('admin.pendaftaran-pindahan.export.xlsx', request()->only(['search', 'status'])) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800 hover:bg-green-100"><i class="fas fa-file-excel"></i>Excel</a>
            </div>
        </div>

        <section class="space-y-4">
            @forelse ($pendaftaran as $item)
                <article x-data="{ previewUrl: null, previewTitle: '', previewType: 'pdf' }" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex flex-col justify-between gap-3 border-b border-gray-100 bg-gray-50 px-5 py-4 sm:flex-row sm:items-center">
                        <div><p class="font-bold text-gray-800">{{ $item->nama_siswa }} <span class="ml-2 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800">{{ $item->status }}</span></p><p class="mt-1 text-xs text-gray-500">{{ $item->no_pendaftaran }} · Dikirim {{ $item->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p></div>
                        <div class="flex items-center gap-4"><p class="text-sm font-semibold text-gray-700">{{ $item->kelas_asal }} → {{ $item->kelas_tujuan }} · {{ $item->tahun_ajaran }}</p><form action="{{ route('admin.pendaftaran-pindahan.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus data murid pindahan ini? Dokumen yang diunggah juga akan dihapus.');">@csrf @method('DELETE')<button type="submit" class="rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-700 hover:bg-red-100">Hapus</button></form></div>
                    </div>
                    <div class="grid gap-5 p-5 lg:grid-cols-[1fr_1fr_320px]">
                        <div class="space-y-2 text-sm text-gray-600"><p><span class="font-semibold text-gray-800">NISN:</span> {{ $item->nisn }}</p><p><span class="font-semibold text-gray-800">NIK:</span> {{ $item->nik }}</p><p><span class="font-semibold text-gray-800">Jenis kelamin:</span> {{ $item->jenis_kelamin }}</p><p><span class="font-semibold text-gray-800">Sekolah asal:</span> {{ $item->asal_sekolah }}</p><p><span class="font-semibold text-gray-800">Alasan pindah:</span> {{ $item->alasan_pindah }}</p></div>
                        <div class="space-y-2 text-sm text-gray-600"><p><span class="font-semibold text-gray-800">Orang tua/wali:</span> {{ $item->nama_wali }}</p><p><span class="font-semibold text-gray-800">WhatsApp:</span> {{ $item->no_whatsapp }}</p><p class="pt-1 font-semibold text-gray-800">Dokumen</p><div class="flex flex-wrap gap-2">@foreach ([['file_kk', 'kk', 'Kartu Keluarga'], ['file_akta', 'akta', 'Akta'], ['file_rapor', 'rapor', 'Rapor'], ['file_surat_pindah', 'surat-pindah', 'Surat pindah'], ['file_rekomendasi_diterima', 'rekomendasi-diterima', 'Rekomendasi diterima'], ['file_surat_permohonan', 'surat-permohonan', 'Surat permohonan'], ['file_rekomendasi_dinas', 'rekomendasi-dinas', 'Rekomendasi Dinas']] as [$field, $key, $label])@if ($item->{$field})<div class="flex items-center gap-1 rounded-lg bg-blue-50 p-1"><button type="button" @click="previewUrl = @js(route('admin.pendaftaran-pindahan.document.preview', [$item, $key])); previewTitle = @js($label); previewType = @js(str_ends_with(strtolower($item->{$field}), '.pdf') ? 'pdf' : 'image')" class="rounded-md px-2 py-1 text-xs font-semibold text-blue-700 hover:bg-blue-100" aria-label="Lihat {{ $label }} untuk {{ $item->nama_siswa }}">Lihat</button><a href="{{ route('admin.pendaftaran-pindahan.document', [$item, $key]) }}" class="rounded-md px-2 py-1 text-xs font-semibold text-gray-600 hover:bg-white" aria-label="Unduh {{ $label }} untuk {{ $item->nama_siswa }}">Unduh</a></div>@endif @endforeach</div><p class="text-xs">NISN aktif di Dapodik: {{ $item->nisn_aktif_dapodik ? 'Ya' : 'Belum dikonfirmasi' }}</p></div>
                        @if (in_array($item->status, ['Menunggu Verifikasi', 'Perlu Perbaikan'], true))
                            <form x-data="{ status: '' }" action="{{ route('admin.pendaftaran-pindahan.status', $item) }}" method="POST" class="space-y-3 rounded-xl border border-gray-100 p-4">
                                @csrf
                                <label for="status-{{ $item->id }}" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Tindak lanjut admin</label>
                                <select id="status-{{ $item->id }}" name="status" x-model="status" required class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-sm"><option value="">Pilih status akhir</option>@foreach ($statuses as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach</select>
                                <div x-cloak x-show="status === 'Ditolak'">
                                    <label for="catatan-admin-{{ $item->id }}" class="mb-2 block text-xs font-semibold text-gray-600">Catatan penolakan untuk wali *</label>
                                    <textarea id="catatan-admin-{{ $item->id }}" name="catatan_admin" x-bind:disabled="status !== 'Ditolak'" x-bind:required="status === 'Ditolak'" rows="3" maxlength="2000" placeholder="Jelaskan alasan pengajuan ditolak" class="w-full rounded-lg border border-gray-200 px-3 py-2.5 text-sm"></textarea>
                                </div>
                                <button type="submit" class="w-full rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-bold text-white hover:bg-blue-800">Kirim Status via WhatsApp</button>
                                <p class="text-xs leading-5 text-gray-500">Status tersimpan setelah WhatsApp berhasil dikirim dan tidak dapat diubah.</p>
                            </form>
                        @else
                            <div class="space-y-2 text-sm font-semibold text-gray-700"><p>Status: <span class="font-bold text-blue-800">{{ $item->status }}</span></p>@if ($item->status === 'Ditolak' && $item->catatan_admin)<p class="font-normal text-gray-600">Catatan yang dikirim ke wali: {{ $item->catatan_admin }}</p>@endif</div>
                        @endif
                    </div>
                    <div x-cloak x-show="previewUrl" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm" @keydown.escape.window="previewUrl = null" @click.self="previewUrl = null" style="display: none;">
                        <div class="relative flex max-h-[calc(100vh-2rem)] w-full max-w-5xl flex-col overflow-hidden rounded-2xl bg-white p-5 shadow-2xl sm:p-7">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <h3 class="truncate text-lg font-bold text-gray-900" x-text="previewTitle"></h3>
                                <button type="button" @click="previewUrl = null" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-gray-600 hover:bg-gray-200" aria-label="Tutup preview"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="flex min-h-[60vh] items-center justify-center overflow-auto rounded-xl border border-gray-200 bg-gray-50">
                                <iframe x-show="previewType === 'pdf'" :src="previewUrl" :title="previewTitle" class="h-[75vh] w-full"></iframe>
                                <img x-show="previewType === 'image'" :src="previewUrl" :alt="previewTitle" class="max-h-[75vh] max-w-full object-contain">
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-12 text-center text-sm text-gray-500">Belum ada pengajuan murid pindahan.</div>
            @endforelse
        </section>

        <div>{{ $pendaftaran->links() }}</div>
    </div>
</x-layouts.admin>
