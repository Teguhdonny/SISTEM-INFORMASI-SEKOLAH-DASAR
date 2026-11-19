<div x-show="activeTab === 'struktur'" style="display: none;" x-data="{ struktur: @js(old('struktur', $strukturOrganisasi)), tambah() { this.struktur.push({ tingkat: '3', jabatan: '', nama: '', keterangan: '' }) }, hapus(index) { if (this.struktur.length > 1) this.struktur.splice(index, 1) } }" class="space-y-4">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-[11px] text-gray-500">Tingkat 1 untuk pimpinan, tingkat 2 untuk koordinator, dan tingkat 3 untuk pelaksana.</p>
        <button type="button" @click="tambah" :disabled="activeTab !== 'struktur'" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 disabled:opacity-40"><i class="fas fa-plus"></i>Tambah Jabatan</button>
    </div>
    <div class="max-h-[50vh] space-y-3 overflow-y-auto pr-1">
        <template x-for="(item, index) in struktur" :key="index">
            <article class="rounded-xl border border-gray-200 bg-gray-50/60 p-4">
                <div class="mb-3 flex items-center justify-between">
                    <span class="font-bold text-blue-800" x-text="`Jabatan ${index + 1}`"></span>
                    <button type="button" @click="hapus(index)" :disabled="activeTab !== 'struktur' || struktur.length === 1" class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Hapus jabatan"><i class="fas fa-trash"></i></button>
                </div>
                <div class="grid gap-3 md:grid-cols-2">
                    <div>
                        <label :for="`struktur-tingkat-${index}`" class="mb-1 block font-semibold text-gray-600">Tingkat</label>
                        <select :id="`struktur-tingkat-${index}`" :name="`struktur[${index}][tingkat]`" x-model="item.tingkat" x-bind:disabled="activeTab !== 'struktur'" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 focus:border-blue-500 focus:outline-none">
                            <option value="1">Pimpinan</option>
                            <option value="2">Koordinator</option>
                            <option value="3">Pelaksana</option>
                        </select>
                    </div>
                    <div>
                        <label :for="`struktur-jabatan-${index}`" class="mb-1 block font-semibold text-gray-600">Jabatan / Bidang</label>
                        <input :id="`struktur-jabatan-${index}`" :name="`struktur[${index}][jabatan]`" x-model="item.jabatan" x-bind:disabled="activeTab !== 'struktur'" required maxlength="150" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none" placeholder="Contoh: Kepala Sekolah">
                    </div>
                    <div>
                        <label :for="`struktur-nama-${index}`" class="mb-1 block font-semibold text-gray-600">Nama (opsional)</label>
                        <input :id="`struktur-nama-${index}`" :name="`struktur[${index}][nama]`" x-model="item.nama" x-bind:disabled="activeTab !== 'struktur'" maxlength="150" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none" placeholder="Nama penanggung jawab">
                    </div>
                    <div>
                        <label :for="`struktur-keterangan-${index}`" class="mb-1 block font-semibold text-gray-600">Keterangan</label>
                        <input :id="`struktur-keterangan-${index}`" :name="`struktur[${index}][keterangan]`" x-model="item.keterangan" x-bind:disabled="activeTab !== 'struktur'" maxlength="500" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none" placeholder="Contoh: Guru Kelas & Mapel">
                    </div>
                </div>
            </article>
        </template>
    </div>
</div>
