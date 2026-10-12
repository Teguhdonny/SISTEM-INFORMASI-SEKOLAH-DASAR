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
        <input id="nip" name="nip" value="{{ old('nip', $guru?->nip) }}" maxlength="50" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Nomor Induk Pegawai">
        @error('nip') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="lulusan" class="mb-2 block text-sm font-semibold text-gray-700">Riwayat Pendidikan</label>
        <textarea id="lulusan" name="lulusan" rows="3" maxlength="5000" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Isi satu pendidikan per baris, contoh:&#10;S1: PGSD — Universitas ...&#10;S2: Manajemen Pendidikan — Universitas ...">{{ old('lulusan', $guru?->lulusan) }}</textarea>
        <p class="mt-1 text-xs text-gray-500">Bisa mengisi lebih dari satu jenjang, satu pendidikan per baris.</p>
        @error('lulusan') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="bagian" class="mb-2 block text-sm font-semibold text-gray-700">Bagian / Tugas <span class="text-red-600">*</span></label>
        <input id="bagian" name="bagian" value="{{ old('bagian', $guru?->bagian) }}" required maxlength="100" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Contoh: Guru Kelas VI">
        @error('bagian') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="email" class="mb-2 block text-sm font-semibold text-gray-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $guru?->email) }}" maxlength="150" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="nama@sekolah.sch.id">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
