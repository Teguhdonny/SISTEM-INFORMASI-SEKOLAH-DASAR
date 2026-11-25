<x-layouts.admin>
    <div class="mx-auto max-w-4xl space-y-6">
        <div><a href="{{ route('admin.program.index') }}" class="text-sm font-semibold text-blue-700 hover:text-red-600"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Program</a><h1 class="mt-3 text-2xl font-bold text-gray-800">{{ $program->exists ? 'Ubah Program' : 'Tambah Program' }}</h1><p class="mt-1 text-sm text-gray-500">Informasi yang berstatus Aktif akan ditampilkan di halaman publik.</p></div>
        <form action="{{ $program->exists ? route('admin.program.update', $program) : route('admin.program.store') }}" method="POST" class="space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
            @csrf @if ($program->exists) @method('PUT') @endif
            <div class="grid gap-5 md:grid-cols-2">
                @foreach (['nama_program' => 'Nama program'] as $field => $label)
                    <div><label for="{{ $field }}" class="mb-2 block text-sm font-semibold text-gray-700">{{ $label }} *</label><input id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $program->$field) }}" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">@error($field)<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                @endforeach
                <div>
                    <label for="kategori" class="mb-2 block text-sm font-semibold text-gray-700">Kategori *</label>
                    <select id="kategori" name="kategori" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected(old('kategori', $program->kategori) === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    @if ($categories->isEmpty())
                        <p class="mt-1 text-xs text-amber-700">Belum ada kategori. <a href="{{ route('admin.kategori-konten.index') }}" class="font-semibold underline">Kelola kategori</a> terlebih dahulu.</p>
                    @endif
                    @error('kategori')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="ikon" class="mb-2 block text-sm font-semibold text-gray-700">Ikon *</label>
                    <select id="ikon" name="ikon" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm">
                        @foreach ($icons as $icon => $label)
                            <option value="{{ $icon }}" @selected(old('ikon', $program->ikon ?? 'fa-book-open') === $icon)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('ikon')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="warna" class="mb-2 block text-sm font-semibold text-gray-700">Warna kartu *</label>
                    <select id="warna" name="warna" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm">
                        @foreach ($colors as $color => $label)
                            <option value="{{ $color }}" @selected(old('warna', $program->warna ?? 'blue') === $color)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('warna')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div><label for="status" class="mb-2 block text-sm font-semibold text-gray-700">Status *</label><select id="status" name="status" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm"><option value="Aktif" @selected(old('status', $program->status ?? 'Aktif') === 'Aktif')>Aktif</option><option value="Draft" @selected(old('status', $program->status ?? '') === 'Draft')>Draft</option></select>@error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                <div class="md:col-span-2"><label for="deskripsi" class="mb-2 block text-sm font-semibold text-gray-700">Deskripsi *</label><textarea id="deskripsi" name="deskripsi" rows="4" required class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('deskripsi', $program->deskripsi) }}</textarea>@error('deskripsi')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
            </div>
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('admin.program.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600">Batal</a><button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
</x-layouts.admin>
