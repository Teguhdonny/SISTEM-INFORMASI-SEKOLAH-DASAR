<div x-show="activeTab === 'sejarah'" style="display: none;" x-data="{ riwayat: @js(old('riwayat', $riwayat)), tambah() { this.riwayat.push({ tahun: '', judul: '', isi: '' }) }, hapus(index) { if (this.riwayat.length > 1) this.riwayat.splice(index, 1) } }" class="space-y-4">
    <div>
        <label for="sejarah-edit" class="mb-1 block font-bold text-gray-700">Ringkasan Sejarah</label>
        <textarea id="sejarah-edit" name="sejarah" rows="3" x-bind:disabled="activeTab !== 'sejarah'" required maxlength="10000" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('sejarah', $profilSekolah->sejarah) }}</textarea>
    </div>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h4 class="font-bold text-gray-800">Linimasa Sekolah</h4>
            <p class="mt-1 text-[11px] text-gray-500">Tambah, ubah, atau hapus peristiwa berdasarkan tahun.</p>
        </div>
        <button type="button" @click="tambah" :disabled="activeTab !== 'sejarah'" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 disabled:opacity-40"><i class="fas fa-plus"></i>Tambah Peristiwa</button>
    </div>
    <div class="max-h-[50vh] space-y-3 overflow-y-auto pr-1">
        <template x-for="(item, index) in riwayat" :key="index">
            <article class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                <div class="mb-3 flex items-center justify-between">
                    <span class="font-bold text-blue-800" x-text="`Peristiwa ${index + 1}`"></span>
                    <button type="button" @click="hapus(index)" :disabled="activeTab !== 'sejarah' || riwayat.length === 1" class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Hapus peristiwa"><i class="fas fa-trash"></i></button>
                </div>
                <div class="grid gap-3 md:grid-cols-4">
                    <div>
                        <label :for="`riwayat-tahun-${index}`" class="mb-1 block font-semibold text-gray-600">Tahun</label>
                        <input :id="`riwayat-tahun-${index}`" :name="`riwayat[${index}][tahun]`" x-model="item.tahun" x-bind:disabled="activeTab !== 'sejarah'" required maxlength="40" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div class="md:col-span-3">
                        <label :for="`riwayat-judul-${index}`" class="mb-1 block font-semibold text-gray-600">Judul Peristiwa</label>
                        <input :id="`riwayat-judul-${index}`" :name="`riwayat[${index}][judul]`" x-model="item.judul" x-bind:disabled="activeTab !== 'sejarah'" required maxlength="150" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div class="md:col-span-4">
                        <label :for="`riwayat-isi-${index}`" class="mb-1 block font-semibold text-gray-600">Keterangan</label>
                        <textarea :id="`riwayat-isi-${index}`" :name="`riwayat[${index}][isi]`" x-model="item.isi" x-bind:disabled="activeTab !== 'sejarah'" required maxlength="2000" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                </div>
            </article>
        </template>
    </div>
</div>
