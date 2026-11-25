<div x-show="activeTab === 'fasilitas'" style="display: none;" x-data="{ fasilitas: @js(old('fasilitas', $fasilitasSekolah)), tambah() { this.fasilitas.push({ nama: '', ket: '', ikon: 'kelas' }) }, hapus(index) { if (this.fasilitas.length > 1) this.fasilitas.splice(index, 1) } }" class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-[11px] text-gray-500">Pilih ikon yang paling sesuai dengan fasilitas.</p>
        <button type="button" @click="tambah" :disabled="activeTab !== 'fasilitas'" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 disabled:opacity-40"><i class="fas fa-plus"></i>Tambah Fasilitas</button>
    </div>
    <div class="max-h-[50vh] space-y-3 overflow-y-auto pr-1">
        <template x-for="(item, index) in fasilitas" :key="index">
            <article class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                <div class="mb-3 flex items-center justify-between">
                    <span class="font-bold text-blue-800" x-text="`Fasilitas ${index + 1}`"></span>
                    <button type="button" @click="hapus(index)" :disabled="activeTab !== 'fasilitas' || fasilitas.length === 1" class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Hapus fasilitas"><i class="fas fa-trash"></i></button>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label :for="`fasilitas-nama-${index}`" class="mb-1 block font-semibold text-gray-600">Nama Fasilitas</label>
                        <input :id="`fasilitas-nama-${index}`" :name="`fasilitas[${index}][nama]`" x-model="item.nama" x-bind:disabled="activeTab !== 'fasilitas'" required maxlength="100" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label :for="`fasilitas-ikon-${index}`" class="mb-1 block font-semibold text-gray-600">Ikon</label>
                        <select :id="`fasilitas-ikon-${index}`" :name="`fasilitas[${index}][ikon]`" x-model="item.ikon" x-bind:disabled="activeTab !== 'fasilitas'" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                            <option value="kelas">Ruang Kelas</option>
                            <option value="buku">Perpustakaan</option>
                            <option value="laboratorium">Laboratorium</option>
                            <option value="uks">UKS</option>
                            <option value="lapangan">Lapangan</option>
                            <option value="toilet">Toilet</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label :for="`fasilitas-ket-${index}`" class="mb-1 block font-semibold text-gray-600">Deskripsi</label>
                        <textarea :id="`fasilitas-ket-${index}`" :name="`fasilitas[${index}][ket]`" x-model="item.ket" x-bind:disabled="activeTab !== 'fasilitas'" required maxlength="500" rows="2" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none"></textarea>
                    </div>
                </div>
            </article>
        </template>
    </div>
</div>
