<x-layouts.admin>
    <div class="mx-auto max-w-4xl space-y-6">
        <div><a href="{{ route('admin.program.index') }}" class="text-sm font-semibold text-blue-700 hover:text-red-600"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Program</a><h1 class="mt-3 text-2xl font-bold text-gray-800">{{ $program->exists ? 'Ubah Program' : 'Tambah Program' }}</h1><p class="mt-1 text-sm text-gray-500">Informasi yang berstatus Aktif akan ditampilkan di halaman publik.</p></div>
        <form action="{{ $program->exists ? route('admin.program.update', $program) : route('admin.program.store') }}" method="POST" class="space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
            @csrf @if ($program->exists) @method('PUT') @endif
            <div class="grid gap-5 md:grid-cols-2">
                @foreach (['nama_program' => 'Nama program', 'kategori' => 'Kategori', 'jadwal' => 'Jadwal'] as $field => $label)
                    <div><label for="{{ $field }}" class="mb-2 block text-sm font-semibold text-gray-700">{{ $label }} *</label><input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $program->$field) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">@error($field)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endforeach
                <div><label for="peserta" class="mb-2 block text-sm font-semibold text-gray-700">Jumlah peserta *</label><input id="peserta" name="peserta" type="number" inputmode="numeric" step="1" min="0" data-digits-only value="{{ old('peserta', $program->peserta ?? 0) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm">@error('peserta')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div><label for="status" class="mb-2 block text-sm font-semibold text-gray-700">Status *</label><select id="status" name="status" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"><option value="Aktif" @selected(old('status', $program->status ?? 'Aktif') === 'Aktif')>Aktif</option><option value="Draft" @selected(old('status', $program->status ?? '') === 'Draft')>Draft</option></select>@error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div class="md:col-span-2"><label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi *</label><textarea id="deskripsi" name="deskripsi" rows="4" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('deskripsi', $program->deskripsi) }}</textarea>@error('deskripsi')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            </div>
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('admin.program.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600">Batal</a><button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</x-layouts.admin>
