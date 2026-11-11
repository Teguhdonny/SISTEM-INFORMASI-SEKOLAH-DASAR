<x-layouts.app>
    <section class="relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 px-6 py-14 text-white shadow-md sm:py-16">
        <div class="absolute -right-10 -top-10 h-72 w-72 rounded-full bg-blue-600/30 blur-3xl"></div>
        <div class="absolute -bottom-10 -left-10 h-72 w-72 rounded-full bg-indigo-600/30 blur-3xl"></div>
        <div class="relative z-10 mx-auto max-w-7xl">
            <nav class="mb-3 flex items-center gap-2 text-xs text-blue-200">
                <a href="{{ route('home') }}" class="transition hover:text-white">Beranda</a>
                <i class="fas fa-chevron-right text-[10px] text-blue-400"></i>
                <span class="font-semibold text-white">Pendaftaran Murid Pindahan</span>
            </nav>
            <h1 class="text-3xl font-extrabold leading-tight tracking-tight sm:text-5xl">Pendaftaran <span class="text-blue-300">Murid Pindahan</span></h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-blue-100 sm:text-base">Ajukan perpindahan siswa dengan melengkapi data dan mengunggah dokumen persyaratan. Pengajuan akan diperiksa oleh pihak sekolah dan belum berarti siswa otomatis diterima.</p>
        </div>
    </section>

    <main class="mx-auto w-full max-w-7xl flex-grow px-4 py-10 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 flex items-center gap-3 rounded-xl border-l-4 border-green-500 bg-green-100 p-4 text-green-800 shadow-sm" role="status">
                <i class="fas fa-check-circle text-xl text-green-600"></i>
                <div><strong class="font-bold">Berhasil!</strong> {{ session('success') }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-xl border border-red-300 bg-red-100 px-4 py-3 text-red-700 shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle mt-0.5 text-lg text-red-600"></i>
                <div><strong class="font-bold">Periksa kembali data yang diisi.</strong><ul class="mt-2 list-inside list-disc space-y-1 text-sm">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            </div>
        @endif

        <div x-data="{ open: @js($errors->any()), kelasAsal: @js(old('kelas_asal', '')), kelasTujuan: @js(old('kelas_tujuan', '')), pesanJenjang: '', syncKelasTujuan() { if (this.kelasTujuan && Number(this.kelasTujuan.replace('Kelas ', '')) !== Number(this.kelasAsal)) { this.kelasTujuan = ''; this.pesanJenjang = 'Kelas tujuan harus sama dengan kelas di sekolah asal.'; } else { this.pesanJenjang = ''; } } }" @keydown.escape.window="open = false">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-6 lg:col-span-2">
                    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="rounded-xl bg-blue-50 p-3 text-blue-600"><i class="fas fa-list-check text-xl"></i></div>
                            <div><h2 class="text-lg font-bold text-gray-900">Alur Pendaftaran</h2><p class="text-xs text-gray-500">Ikuti langkah untuk mengajukan murid pindahan.</p></div>
                        </div>
                        <div class="mt-6 grid grid-cols-1 gap-4 text-center sm:grid-cols-3">
                            @foreach ([['1', 'Lengkapi Data Siswa'], ['2', 'Unggah Dokumen'], ['3', 'Verifikasi Admin']] as [$number, $label])
                                <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4"><div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white shadow-sm">{{ $number }}</div><h3 class="text-xs font-semibold text-gray-800">{{ $label }}</h3></div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm sm:p-8">
                        <div class="mb-4 flex items-center gap-3">
                            <div class="rounded-xl bg-blue-50 p-3 text-blue-600"><i class="fas fa-folder-open text-xl"></i></div>
                            <div><h2 class="text-lg font-bold text-gray-900">Dokumen yang Diperlukan</h2><p class="text-xs text-gray-500">Siapkan berkas digital sebelum mengisi formulir.</p></div>
                        </div>
                        <div class="mt-6 space-y-3">
                            @foreach ([['fa-file-signature', 'Surat Keterangan Pindah', 'Surat resmi dari sekolah asal.'], ['fa-envelope-open-text', 'Rekomendasi Diterima', 'Surat dari sekolah tujuan yang menyatakan tersedia daya tampung.'], ['fa-book-open', 'Buku Rapor', 'Halaman identitas dan nilai semester terakhir.'], ['fa-file-lines', 'Surat Permohonan Pindah', 'Dari orang tua atau wali murid.'], ['fa-id-card', 'Kartu Keluarga dan Akta Kelahiran', 'Dokumen identitas siswa dan keluarga.'], ['fa-building-columns', 'Rekomendasi Dinas Pendidikan', 'Diperlukan untuk perpindahan lintas wilayah; unggah jika diminta.']] as [$icon, $title, $description])
                                <div class="flex items-center justify-between gap-3 rounded-xl border border-gray-100 bg-gray-50 p-4">
                                    <div class="flex items-center gap-3"><div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600"><i class="fas {{ $icon }}"></i></div><div><h3 class="text-sm font-bold text-gray-800">{{ $title }}</h3><p class="text-xs text-gray-500">{{ $description }}</p></div></div>
                                    <span class="shrink-0 rounded-full border px-3 py-1 text-xs font-semibold {{ $title === 'Rekomendasi Dinas Pendidikan' ? 'border-amber-100 bg-amber-50 text-amber-700' : 'border-red-100 bg-red-50 text-red-600' }}">{{ $title === 'Rekomendasi Dinas Pendidikan' ? 'Jika perlu' : 'Wajib' }}</span>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-4 text-xs text-gray-500">Unggah gambar JPG, PNG, WebP, atau PDF; maksimal 5 MB untuk setiap dokumen. NISN harus terdaftar aktif di Dapodik.</p>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="mb-3 flex items-center gap-2 text-sm font-bold text-gray-900"><i class="fas fa-circle-info text-blue-600"></i> Informasi Penting</h2>
                        <ul class="list-inside list-disc space-y-2.5 text-xs leading-relaxed text-gray-600">
                            <li>Kelas yang dituju harus sama dengan kelas di sekolah asal.</li>
                            <li>Pastikan data siswa dan wali diisi dengan benar.</li>
                            <li>Pastikan dokumen terlihat jelas dan dapat dibaca.</li>
                            <li>Pengajuan akan diverifikasi oleh admin sekolah.</li>
                        </ul>
                    </section>

                    <section class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                        <h2 class="mb-3 flex items-center gap-2 text-sm font-bold text-gray-900"><i class="fas fa-headset text-blue-600"></i> Kontak Admin</h2>
                        <div class="space-y-3 text-xs text-gray-600">
                            <p class="flex items-start gap-2"><i class="fas fa-map-marker-alt mt-0.5 text-red-500"></i><span>Jl. Mastrip Nomor 59, Pojok, Kec. Mojoroto, Kota Kediri</span></p>
                            <p class="flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i><a href="mailto:pojok2mojoroto@gmail.com" class="hover:text-blue-700">pojok2mojoroto@gmail.com</a></p>
                        </div>
                    </section>

                    <section class="rounded-2xl bg-gradient-to-br from-blue-900 to-blue-800 p-6 text-center text-white shadow-md">
                        <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-700 text-xl shadow"><i class="fas fa-user-plus"></i></div>
                        <h2 class="mb-1 text-base font-bold">Mulai Pendaftaran</h2>
                        <p class="mb-4 text-xs text-blue-200">Siapkan data dan dokumen sebelum melanjutkan.</p>
                        <button type="button" @click="open = true" class="flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-3 text-sm font-bold text-white shadow-md transition hover:bg-red-700"><span>Isi Formulir</span><i class="fas fa-arrow-right"></i></button>
                    </section>
                </aside>
            </div>

            <div x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/60 p-4 backdrop-blur-sm" @click.self="open = false">
                <section x-transition class="relative my-auto max-h-[92vh] w-full max-w-4xl overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
                    <button type="button" @click="open = false" aria-label="Tutup formulir" class="absolute right-5 top-5 flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 font-bold text-gray-500 transition hover:bg-gray-200"><i class="fas fa-times"></i></button>
                    <div class="mb-6 pr-10"><h2 class="text-2xl font-bold text-gray-900">Formulir Pendaftaran Murid Pindahan</h2><p class="mt-1 text-xs text-gray-500">Lengkapi seluruh data bertanda wajib dan unggah dokumen yang diminta.</p></div>

                    <form action="{{ route('pendaftaran-pindahan.public.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 text-left">
                        @csrf
                        <section class="rounded-2xl border border-gray-100 p-5 sm:p-6">
                            <h3 class="mb-4 text-sm font-bold text-gray-800">Data Siswa</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div><label for="nama_siswa" class="mb-1 block text-xs font-semibold text-gray-700">Nama lengkap siswa *</label><input id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa') }}" required maxlength="150" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('nama_siswa')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="nisn" class="mb-1 block text-xs font-semibold text-gray-700">NISN *</label><input id="nisn" name="nisn" value="{{ old('nisn') }}" required inputmode="numeric" data-digits-only maxlength="10" placeholder="10 digit NISN" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('nisn')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror<label class="mt-2 flex items-start gap-2 text-xs leading-5 text-gray-600"><input type="checkbox" name="nisn_aktif_dapodik" value="1" @checked(old('nisn_aktif_dapodik')) required class="mt-1 rounded border-gray-300 text-blue-700"><span>Saya memastikan NISN siswa terdaftar aktif di Dapodik.</span></label>@error('nisn_aktif_dapodik')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="nik" class="mb-1 block text-xs font-semibold text-gray-700">NIK siswa *</label><input id="nik" name="nik" value="{{ old('nik') }}" required inputmode="numeric" data-digits-only maxlength="16" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('nik')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="jenis_kelamin" class="mb-1 block text-xs font-semibold text-gray-700">Jenis kelamin *</label><select id="jenis_kelamin" name="jenis_kelamin" required class="w-full rounded-xl border border-gray-300 bg-white p-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"><option value="">Pilih jenis kelamin</option><option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')>Laki-laki</option><option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')>Perempuan</option></select>@error('jenis_kelamin')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="asal_sekolah" class="mb-1 block text-xs font-semibold text-gray-700">Sekolah asal *</label><input id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required maxlength="150" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('asal_sekolah')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="kelas_asal" class="mb-1 block text-xs font-semibold text-gray-700">Kelas di sekolah asal *</label><select id="kelas_asal" name="kelas_asal" x-model="kelasAsal" @change="syncKelasTujuan" required class="w-full rounded-xl border border-gray-300 bg-white p-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"><option value="">Pilih kelas asal</option>@foreach (range(1, 6) as $nomorKelas)<option value="{{ $nomorKelas }}" @selected((string) old('kelas_asal') === (string) $nomorKelas)>Kelas {{ $nomorKelas }}</option>@endforeach</select>@error('kelas_asal')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="kelas_tujuan" class="mb-1 block text-xs font-semibold text-gray-700">Kelas yang dituju *</label><select id="kelas_tujuan" name="kelas_tujuan" x-model="kelasTujuan" required class="w-full rounded-xl border border-gray-300 bg-white p-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200"><option value="">Pilih kelas tujuan</option>@foreach (range(1, 6) as $nomorKelas)<option value="Kelas {{ $nomorKelas }}" :disabled="!kelasAsal || {{ $nomorKelas }} !== Number(kelasAsal)" @selected(old('kelas_tujuan') === 'Kelas '.$nomorKelas)>Kelas {{ $nomorKelas }}</option>@endforeach</select><p class="mt-1 text-xs text-gray-500">Kelas tujuan harus sama dengan kelas di sekolah asal.</p><p x-cloak x-show="pesanJenjang" x-text="pesanJenjang" role="alert" class="mt-1 text-xs text-red-600"></p>@error('kelas_tujuan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="tahun_ajaran" class="mb-1 block text-xs font-semibold text-gray-700">Tahun ajaran yang dituju *</label><input id="tahun_ajaran" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tahunAjaranAktif) }}" required maxlength="9" pattern="[0-9]{4}/[0-9]{4}" placeholder="2026/2027" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('tahun_ajaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div class="sm:col-span-2"><label for="alasan_pindah" class="mb-1 block text-xs font-semibold text-gray-700">Alasan pindah *</label><textarea id="alasan_pindah" name="alasan_pindah" required maxlength="2000" rows="3" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">{{ old('alasan_pindah') }}</textarea>@error('alasan_pindah')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-gray-100 p-5 sm:p-6">
                            <h3 class="mb-4 text-sm font-bold text-gray-800">Data Orang Tua atau Wali</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div><label for="nama_wali" class="mb-1 block text-xs font-semibold text-gray-700">Nama orang tua/wali *</label><input id="nama_wali" name="nama_wali" value="{{ old('nama_wali') }}" required maxlength="100" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('nama_wali')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                <div><label for="no_whatsapp" class="mb-1 block text-xs font-semibold text-gray-700">Nomor WhatsApp *</label><input id="no_whatsapp" name="no_whatsapp" value="{{ old('no_whatsapp') }}" required inputmode="numeric" data-digits-only maxlength="20" placeholder="08xxxxxxxxxx" class="w-full rounded-xl border border-gray-300 p-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200">@error('no_whatsapp')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-gray-100 p-5 sm:p-6">
                            <h3 class="text-sm font-bold text-gray-800">Unggah Berkas / Dokumen</h3><p class="mt-1 text-xs text-gray-500">Format JPG, PNG, WebP, atau PDF (maksimal 5 MB per file).</p>
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach ([['file_surat_pindah', 'Surat keterangan pindah sekolah *', true], ['file_rekomendasi_diterima', 'Rekomendasi diterima dari sekolah tujuan *', true], ['file_rapor', 'Buku rapor: identitas dan nilai semester terakhir *', true], ['file_surat_permohonan', 'Surat permohonan pindah dari orang tua/wali *', true], ['file_kk', 'Kartu Keluarga *', true], ['file_akta', 'Akta kelahiran *', true], ['file_rekomendasi_dinas', 'Rekomendasi Dinas Pendidikan (opsional)', false]] as [$field, $label, $required])
                                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-3.5"><label for="{{ $field }}" class="mb-1.5 block text-xs font-semibold text-gray-700">{{ $label }}</label><input id="{{ $field }}" name="{{ $field }}" type="file" accept=".jpg,.jpeg,.png,.webp,.pdf" @required($required) class="block w-full cursor-pointer text-xs text-gray-500 file:mr-2 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-blue-700 hover:file:bg-blue-100">@error($field)<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror</div>
                                @endforeach
                            </div>
                        </section>

                        <div class="flex flex-col-reverse justify-end gap-3 border-t border-gray-200 pt-4 sm:flex-row"><button type="button" @click="open = false" class="rounded-xl border border-gray-300 px-5 py-3 text-sm font-semibold text-gray-600 transition hover:bg-gray-50">Batal</button><button type="submit" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-md transition hover:bg-blue-700">Kirim Pengajuan</button></div>
                    </form>
                </section>
            </div>
        </div>
    </main>
</x-layouts.app>
