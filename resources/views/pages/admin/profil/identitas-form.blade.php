<div x-show="activeTab === 'identitas'" style="display: none;" x-data="{ identitas: @js(old('identitas_sekolah', $identitasSekolah)), tambah() { this.identitas.push({ label: '', nilai: '' }) }, hapus(index) { if (this.identitas.length > 1) this.identitas.splice(index, 1) } }" class="space-y-4">
    <p class="text-[11px] text-gray-500">Nama sekolah, alamat, dan email resmi dapat diubah pada tab Profil Sekolah.</p>
    <div class="flex justify-end">
        <button type="button" @click="tambah" :disabled="activeTab !== 'identitas'" class="inline-flex items-center gap-2 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-100 disabled:opacity-40"><i class="fas fa-plus"></i>Tambah Data</button>
    </div>
    <div class="max-h-[50vh] space-y-3 overflow-y-auto pr-1">
        <template x-for="(item, index) in identitas" :key="index">
            <div class="grid gap-3 rounded-xl border border-gray-200 bg-gray-50/60 p-4 md:grid-cols-[1fr_1.5fr_auto]">
                <div>
                    <label :for="`identitas-label-${index}`" class="mb-1 block font-semibold text-gray-600">Nama Data</label>
                    <input :id="`identitas-label-${index}`" :name="`identitas_sekolah[${index}][label]`" x-model="item.label" x-bind:disabled="activeTab !== 'identitas'" required maxlength="100" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none" placeholder="Contoh: NPSN">
                </div>
                <div>
                    <label :for="`identitas-nilai-${index}`" class="mb-1 block font-semibold text-gray-600">Nilai</label>
                    <input :id="`identitas-nilai-${index}`" :name="`identitas_sekolah[${index}][nilai]`" x-model="item.nilai" x-bind:disabled="activeTab !== 'identitas'" required maxlength="500" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 focus:border-blue-500 focus:outline-none" placeholder="Isi data sekolah">
                </div>
                <div class="flex items-end">
                    <button type="button" @click="hapus(index)" :disabled="activeTab !== 'identitas' || identitas.length === 1" class="flex h-10 w-10 items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 disabled:cursor-not-allowed disabled:opacity-40" aria-label="Hapus data identitas"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        </template>
    </div>
</div>
