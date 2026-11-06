@csrf
@if ($guru?->exists)
    @method('PUT')
@endif

<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div>
        <label for="nama" class="mb-2 block text-sm font-semibold text-gray-700">Nama lengkap <span class="text-red-600">*</span></label>
        <input id="nama" name="nama" value="{{ old('nama', $guru?->nama) }}" required maxlength="100" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nama dan gelar">
        @error('nama') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="nip" class="mb-2 block text-sm font-semibold text-gray-700">NIP</label>
        <input id="nip" name="nip" type="text" inputmode="numeric" pattern="[0-9]*" data-digits-only maxlength="50" value="{{ old('nip', $guru?->nip) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nomor Induk Pegawai">
        @error('nip') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    @php
        $pendidikanAwal = old('lulusan', $guru?->lulusan ? preg_split('/\R/', $guru->lulusan, -1, PREG_SPLIT_NO_EMPTY) : []);
        $pendidikanAwal = is_array($pendidikanAwal) ? array_values($pendidikanAwal) : [];
        if ($pendidikanAwal === []) {
            $pendidikanAwal = [''];
        }
    @endphp
    <div x-data="{ pendidikan: @js($pendidikanAwal), tambah() { this.pendidikan.push('') }, hapus(index) { if (this.pendidikan.length > 1) this.pendidikan.splice(index, 1) } }">
        <div class="mb-2 flex items-center justify-between gap-3">
            <label class="block text-sm font-semibold text-gray-700">Jenjang Pendidikan</label>
            <button type="button" @click="tambah" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-100"><i class="fas fa-plus"></i>Tambah Jenjang</button>
        </div>
        <div class="space-y-3">
            <template x-for="(item, index) in pendidikan" :key="index">
                <div class="flex items-start gap-2">
                    <div class="flex-1">
                        <label :for="`lulusan-${index}`" class="sr-only">Jenjang pendidikan</label>
                        <input :id="`lulusan-${index}`" name="lulusan[]" x-model="pendidikan[index]" maxlength="200" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: S1 PGSD — Universitas ...">
                    </div>
                    <button type="button" @click="hapus(index)" class="mt-1 flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus jenjang pendidikan"><i class="fas fa-trash"></i></button>
                </div>
            </template>
        </div>
        @error('lulusan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        @error('lulusan.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="bagian" class="mb-2 block text-sm font-semibold text-gray-700">Bagian / Tugas <span class="text-red-600">*</span></label>
        <input id="bagian" name="bagian" value="{{ old('bagian', $guru?->bagian) }}" required maxlength="100" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: Guru Kelas VI">
        @error('bagian') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    @php
        $kompetensiAwal = old('kompetensi', $guru?->kompetensi ? preg_split('/\R/', $guru->kompetensi, -1, PREG_SPLIT_NO_EMPTY) : []);
        $kompetensiAwal = is_array($kompetensiAwal) ? array_values($kompetensiAwal) : [];
        if ($kompetensiAwal === []) {
            $kompetensiAwal = [''];
        }
    @endphp
    <div x-data="{ kompetensi: @js($kompetensiAwal), tambah() { this.kompetensi.push('') }, hapus(index) { if (this.kompetensi.length > 1) this.kompetensi.splice(index, 1) } }" class="md:col-span-2">
        <div class="mb-2 flex flex-wrap items-center justify-between gap-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700">Kompetensi Mata Pelajaran</label>
                <p class="mt-1 text-xs text-gray-500">Isi satu mata pelajaran per baris. Jadwal hanya menampilkan guru yang sesuai.</p>
            </div>
            <button type="button" @click="tambah" class="inline-flex items-center gap-1.5 rounded-lg bg-blue-50 px-3 py-2 text-xs font-bold text-blue-700 transition hover:bg-blue-100"><i class="fas fa-plus"></i>Tambah Kompetensi</button>
        </div>
        <div class="space-y-3">
            <template x-for="(item, index) in kompetensi" :key="index">
                <div class="flex items-start gap-2">
                    <div class="flex-1">
                        <label :for="`kompetensi-${index}`" class="sr-only">Kompetensi mata pelajaran</label>
                        <input :id="`kompetensi-${index}`" name="kompetensi[]" x-model="kompetensi[index]" list="daftar-kompetensi" maxlength="100" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: Matematika">
                    </div>
                    <button type="button" @click="hapus(index)" class="mt-1 flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600 transition hover:bg-red-100" aria-label="Hapus kompetensi"><i class="fas fa-trash"></i></button>
                </div>
            </template>
        </div>
        <datalist id="daftar-kompetensi">
            <option value="Pendidikan Agama Islam"></option>
            <option value="Pendidikan Pancasila"></option>
            <option value="Bahasa Indonesia"></option>
            <option value="Matematika"></option>
            <option value="IPAS"></option>
            <option value="PJOK"></option>
            <option value="Seni Budaya"></option>
            <option value="Bahasa Inggris"></option>
            <option value="Bahasa Jawa"></option>
        </datalist>
        @error('kompetensi') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        @error('kompetensi.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="foto" class="mb-2 block text-sm font-semibold text-gray-700">Foto profil <span class="font-normal text-gray-400">(JPG, PNG, WEBP maks. 2 MB)</span></label>
        <input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp" class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:font-semibold file:text-blue-700">
        @error('foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        @if ($guru?->foto)
            <img src="{{ asset('storage/'.$guru->foto) }}" alt="Foto {{ $guru->nama }}" class="mt-3 h-20 w-20 rounded-xl object-cover">
        @endif
    </div>
</div>

<div class="mt-6 flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
    <a href="{{ route('admin.guru.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Batal</a>
    <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800">
        <i class="fas fa-save mr-2"></i>{{ $guru?->exists ? 'Simpan Perubahan' : 'Simpan Data Guru' }}
    </button>
</div>
