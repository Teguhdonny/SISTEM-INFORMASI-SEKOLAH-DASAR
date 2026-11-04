@csrf
<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <label for="nama_siswa" class="mb-2 block text-sm font-semibold text-gray-700">Nama lengkap siswa <span class="text-red-600">*</span></label>
        <input id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $siswaPpdb?->nama_siswa) }}" required maxlength="150" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="Sesuai data PPDB pemerintah">
        @error('nama_siswa') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="nik" class="mb-2 block text-sm font-semibold text-gray-700">NIK <span class="text-red-600">*</span></label>
        <input id="nik" name="nik" inputmode="numeric" data-digits-only maxlength="16" value="{{ old('nik', $siswaPpdb?->nik) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="16 digit NIK">
        @error('nik') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="nisn" class="mb-2 block text-sm font-semibold text-gray-700">NISN <span class="text-red-600">*</span></label>
        <input id="nisn" name="nisn" inputmode="numeric" data-digits-only maxlength="10" value="{{ old('nisn', $siswaPpdb?->nisn) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100" placeholder="10 digit NISN">
        @error('nisn') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
<p class="mt-4 rounded-xl bg-blue-50 px-4 py-3 text-xs text-blue-800"><i class="fas fa-calendar-check mr-1"></i>Tahun ajaran otomatis mengikuti periode aktif: <strong>{{ $tahunAjaranAktif ?: 'belum diatur' }}</strong>.</p>
<div class="mt-6 flex flex-wrap justify-end gap-3 border-t border-gray-100 pt-5">
    <a href="{{ route('admin.ppdb.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Batal</a>
    <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"><i class="fas fa-save mr-2"></i>{{ $siswaPpdb?->exists ? 'Simpan Perubahan' : 'Simpan Data Siswa' }}</button>
</div>
