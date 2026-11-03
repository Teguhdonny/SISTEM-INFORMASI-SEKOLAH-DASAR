<x-layouts.app>
    <!-- Wrapper Alpine.js untuk mengontrol Modal dan Validasi Cek Data PPDB -->
    <div x-data="{ 
        bukaModalForm: {{ ($errors->any() && $isOpen) ? 'true' : 'false' }},
        bukaModalTutup: false,
        noPendaftaran: @js(old('no_pendaftaran', '')),
        nisn: @js(old('nisn', '')),
        namaSiswa: @js(old('nama_siswa', '')),
        asalSekolah: @js(old('asal_sekolah', '')),
        cekLoading: false,
        cekStatus: null,
        cekPesan: '',
        async cekDataPpdb() {
            if (!this.noPendaftaran && !this.nisn) {
                this.cekStatus = 'error';
                this.cekPesan = 'Silakan isi Nomor Pendaftaran dan NISN terlebih dahulu.';
                return;
            }
            this.cekLoading = true;
            this.cekStatus = null;
            try {
                const params = new URLSearchParams({
                    no_pendaftaran: this.noPendaftaran,
                    nisn: this.nisn
                });
                const res = await fetch(`{{ route('registrasi.public.cek') }}?${params.toString()}`);
                const result = await res.json();
                if (result.found) {
                    this.cekStatus = 'success';
                    this.cekPesan = result.message;
                    this.noPendaftaran = result.data.no_pendaftaran;
                    this.nisn = result.data.nisn;
                    if (result.data.asal_sekolah && !this.asalSekolah) {
                        this.asalSekolah = result.data.asal_sekolah;
                    }
                } else {
                    this.cekStatus = 'error';
                    this.cekPesan = result.message;
                }
            } catch (e) {
                this.cekStatus = 'error';
                this.cekPesan = 'Gagal memeriksa data ke server. Silakan lanjutkan pengisian manual.';
            } finally {
                this.cekLoading = false;
            }
        }
    }" class="flex flex-col min-h-screen">

        <!-- Hero Banner Dinamis & Modern -->
        <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-16 px-6 overflow-hidden shadow-md">
            <div class="absolute -right-10 -top-10 w-72 h-72 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
            <div class="absolute -left-10 -bottom-10 w-72 h-72 bg-indigo-600 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
            
            <div class="max-w-7xl mx-auto relative z-10">
                <nav class="text-xs text-blue-200 mb-3 flex items-center gap-2">
                    <a href="/" class="hover:text-white transition">Beranda</a> 
                    <i class="fas fa-chevron-right text-[10px] text-blue-400"></i> 
                    <span class="text-white font-semibold">Registrasi Daftar Ulang</span>
                </nav>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                    Registrasi <span class="text-blue-300">Daftar Ulang</span>
                </h1>
                <p class="mt-3 text-sm sm:text-base text-blue-100 max-w-2xl leading-relaxed">
                    Khusus bagi calon peserta didik baru yang telah dinyatakan <strong>Diterima / Lulus PPDB</strong>. Pastikan Nomor Pendaftaran, NISN, dan Nama Lengkap sesuai dengan pengumuman kelulusan PPDB.
                </p>
            </div>
        </section>

        <!-- Konten Utama -->
        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 rounded-xl shadow-sm flex items-center justify-between" role="alert">
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                    <div>
                        <strong class="font-bold block text-sm">Registrasi Daftar Ulang Berhasil!</strong>
                        <span class="text-xs">{{ session('success') }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Notifikasi Error Validasi / Proteksi -->
            @if($errors->any() || session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3.5 rounded-xl shadow-sm relative flex items-start gap-3" role="alert">
                <i class="fas fa-exclamation-triangle text-red-600 text-lg mt-0.5"></i>
                <div class="text-xs space-y-1">
                    <strong class="font-bold text-sm block">Validasi Pendaftaran Gagal!</strong> 
                    <p>{{ session('error') ?? 'Terdapat ketidaksesuaian data. Silakan periksa kembali pesan kesalahan di dalam formulir.' }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Kolom Kiri & Tengah (Alur & Dokumen) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card Alur Daftar Ulang -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                                <i class="fas fa-list-check text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Alur Daftar Ulang Online</h2>
                                <p class="text-xs text-gray-500">Ikuti 3 langkah berikut untuk menyelesaikan proses daftar ulang siswa PPDB.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center mt-6">
                            <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                                <div class="w-8 h-8 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-sm">1</div>
                                <h4 class="font-semibold text-xs text-gray-800">Validasi Data PPDB</h4>
                                <p class="text-[11px] text-gray-500 mt-1">Cocokkan No. Pendaftaran, NISN & Nama Siswa.</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-8 h-8 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-sm">2</div>
                                <h4 class="font-semibold text-xs text-gray-800">Unggah Berkas Wajib</h4>
                                <p class="text-[11px] text-gray-500 mt-1">Unggah scan/foto Akta Kelahiran & KK.</p>
                            </div>
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="w-8 h-8 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-sm">3</div>
                                <h4 class="font-semibold text-xs text-gray-800">Verifikasi Admin</h4>
                                <p class="text-[11px] text-gray-500 mt-1">Admin memeriksa berkas & mengonfirmasi status.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Dokumen yang Diperlukan -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 bg-blue-50 text-blue-600 rounded-xl">
                                <i class="fas fa-folder-open text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Dokumen yang Diperlukan</h2>
                                <p class="text-xs text-gray-500">Siapkan dokumen asli dalam format JPG, PNG, atau PDF (Maksimal 2MB per file).</p>
                            </div>
                        </div>

                        <div class="space-y-4 mt-6">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">
                                        <i class="fas fa-id-card"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">Kartu Keluarga (KK)</h4>
                                        <p class="text-xs text-gray-500">Scan/Foto Kartu Keluarga terbaru yang jelas dan terbaca.</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-full">Wajib</span>
                            </div>

                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-gray-800">Akta Kelahiran</h4>
                                        <p class="text-xs text-gray-500">Scan/Foto Akta Kelahiran asli peserta didik.</p>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-full">Wajib</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Kolom Kanan (Sidebar) -->
                <div class="space-y-6">
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-info-circle text-blue-600"></i> Ketentuan Validasi PPDB
                        </h3>
                        <ul class="text-xs text-gray-600 space-y-2.5 list-disc list-inside leading-relaxed">
                            <li>Hanya siswa yang terdaftar pada <strong>Data Kelulusan PPDB</strong> yang dapat melakukan daftar ulang.</li>
                            <li>Penulisan <strong>Nama Lengkap Siswa</strong> harus sesuai dengan yang tertera pada data pengumuman PPDB.</li>
                            <li>Setiap siswa hanya dapat mengirim formulir daftar ulang sebanyak <strong>1 kali</strong>.</li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas fa-headset text-blue-600"></i> Kontak Bantuan Admin
                        </h3>
                        <div class="text-xs text-gray-600 space-y-3">
                            <p class="flex items-start gap-2"><i class="fas fa-map-marker-alt text-red-500 mt-0.5"></i> <span>Jl. Mastrip Nomor 59, Pojok, Kec. Mojoroto, Kota Kediri</span></p>
                            <p class="flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i> <span>pojok2mojoroto@gmail.com</span></p>
                        </div>
                    </div>

                    <!-- Box Call to Action (Mulai Daftar Ulang) -->
                    <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-2xl shadow-md p-6 text-white text-center">
                        <div class="w-12 h-12 mx-auto bg-blue-700 rounded-full flex items-center justify-center text-xl mb-3 shadow">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3 class="font-bold text-base mb-1">Mulai Daftar Ulang Sekarang</h3>
                        <p class="text-xs text-blue-200 mb-4">Siapkan Nomor Pendaftaran PPDB, NISN, serta berkas KK dan Akta Kelahiran.</p>
                        
                        @if($isOpen)
                            <button @click="bukaModalForm = true" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-2">
                                <span>Isi Formulir Daftar Ulang</span> <i class="fas fa-arrow-right"></i>
                            </button>
                        @else
                            <button @click="bukaModalTutup = true" class="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-2">
                                <span>Lanjutkan</span> <i class="fas fa-lock"></i>
                            </button>
                        @endif
                    </div>

                </div>
            </div>
        </main>

        @if($isOpen)
        <!-- POPUP MODAL FORMULIR PENDAFTARAN DAFTAR ULANG -->
        <div x-show="bukaModalForm" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4 overflow-y-auto">
            <div @click.away="bukaModalForm = false" class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[92vh] overflow-y-auto p-6 sm:p-8 relative my-auto">
                <button type="button" @click="bukaModalForm = false" class="absolute top-5 right-5 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center font-bold transition">
                    <i class="fas fa-times"></i>
                </button>

                <div class="mb-5 pr-8">
                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-bold text-[11px] rounded-lg">Validasi Terintegrasi PPDB</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-1.5">Formulir Registrasi Daftar Ulang</h2>
                    <p class="text-xs text-gray-500 mt-1">Masukkan Nomor Pendaftaran, NISN, dan Nama Lengkap sesuai pengumuman kelulusan PPDB.</p>
                </div>

                <!-- Form Pendaftaran -->
                <form action="{{ route('registrasi.public.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                    @csrf
                    
                    <!-- Bagian 1: Identitas PPDB + Tombol Cek -->
                    <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                                <i class="fas fa-id-badge text-blue-600"></i> Pencocokan Data Kelulusan PPDB
                            </span>
                            <button type="button" @click="cekDataPpdb()" :disabled="cekLoading" class="bg-blue-600 hover:bg-blue-700 disabled:opacity-50 text-white text-[11px] font-bold px-3 py-1.5 rounded-lg shadow-sm transition flex items-center gap-1.5">
                                <i class="fas" :class="cekLoading ? 'fa-spinner fa-spin' : 'fa-search'"></i>
                                <span x-text="cekLoading ? 'Memeriksa...' : 'Cek Data PPDB'"></span>
                            </button>
                        </div>

                        <!-- Pesan Hasil Cek Real-time -->
                        <div x-show="cekStatus" style="display: none;" 
                             :class="cekStatus === 'success' ? 'bg-emerald-100 border-emerald-300 text-emerald-800' : 'bg-red-100 border-red-300 text-red-700'"
                             class="text-xs px-3 py-2 rounded-xl border flex items-center gap-2">
                            <i class="fas" :class="cekStatus === 'success' ? 'fa-check-circle text-emerald-600' : 'fa-times-circle text-red-600'"></i>
                            <span x-text="cekPesan"></span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Pendaftaran PPDB <span class="text-red-500">*</span></label>
                                <input type="text" name="no_pendaftaran" x-model="noPendaftaran" required placeholder="Contoh: PPDB-2026-001"
                                    class="w-full rounded-xl border @error('no_pendaftaran') border-red-500 bg-red-50 @else border-gray-300 bg-white @enderror p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @error('no_pendaftaran')
                                    <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">NISN (10 Digit) <span class="text-red-500">*</span></label>
                                <input type="text" name="nisn" x-model="nisn" inputmode="numeric" pattern="[0-9]*" data-digits-only maxlength="10" required placeholder="Contoh: 0123456789"
                                    class="w-full rounded-xl border @error('nisn') border-red-500 bg-red-50 @else border-gray-300 bg-white @enderror p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                                @error('nisn')
                                    <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Bagian 2: Validasi Nama Siswa & Jenjang -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Siswa (Sesuai PPDB) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_siswa" x-model="namaSiswa" required placeholder="Ketik nama lengkap sesuai data PPDB"
                                class="w-full rounded-xl border @error('nama_siswa') border-red-500 bg-red-50 @else border-gray-300 @enderror p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('nama_siswa')
                                <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Jenjang / Kelas Tujuan</label>
                            <select name="jenjang" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                                <option value="Kelas 1">Kelas 1</option>
                            </select>
                        </div>
                    </div>

                    <!-- Bagian 3: Data Wali & Kontak -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Asal Sekolah (TK/RA)</label>
                            <input type="text" name="asal_sekolah" x-model="asalSekolah" placeholder="Nama TK/RA asal"
                                class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Orang Tua / Wali <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_wali" required placeholder="Nama Ayah / Ibu / Wali" value="{{ old('nama_wali') }}"
                                class="w-full rounded-xl border @error('nama_wali') border-red-500 bg-red-50 @else border-gray-300 @enderror p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            @error('nama_wali')
                                <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor WhatsApp Aktif (Untuk Notifikasi Status) <span class="text-red-500">*</span></label>
                        <input type="text" name="no_whatsapp" inputmode="numeric" pattern="[0-9]*" data-digits-only maxlength="20" required placeholder="08xxxxxxxxxx" value="{{ old('no_whatsapp') }}"
                            class="w-full rounded-xl border @error('no_whatsapp') border-red-500 bg-red-50 @else border-gray-300 @enderror p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        @error('no_whatsapp')
                            <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <hr class="my-4 border-gray-200">

                    <h3 class="text-sm font-bold text-gray-800">Unggah Berkas / Dokumen Wajib</h3>
                    <p class="text-xs text-gray-500">Format file: JPG, PNG, atau PDF (Maksimal 2MB per file).</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="border @error('file_akta') border-red-400 bg-red-50/40 @else border-gray-200 bg-gray-50 @enderror p-3.5 rounded-xl">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Akta Kelahiran <span class="text-red-500">*</span></label>
                            <input type="file" name="file_akta" required accept=".jpg,.jpeg,.png,.pdf" 
                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            @error('file_akta')
                                <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="border @error('file_kk') border-red-400 bg-red-50/40 @else border-gray-200 bg-gray-50 @enderror p-3.5 rounded-xl">
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kartu Keluarga (KK) <span class="text-red-500">*</span></label>
                            <input type="file" name="file_kk" required accept=".jpg,.jpeg,.png,.pdf" 
                                class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            @error('file_kk')
                                <p class="mt-1 text-[11px] font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Kirim Pendaftaran Daftar Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @else
        <!-- POPUP MODAL PENDAFTARAN DITUTUP -->
        <div x-show="bukaModalTutup" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4">
            <div @click.away="bukaModalTutup = false" class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden relative text-center">
                
                <div class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-6 px-8 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-500 rounded-full mix-blend-overlay filter blur-xl opacity-50"></div>
                    
                    <button type="button" @click="bukaModalTutup = false" class="absolute top-4 right-4 w-8 h-8 bg-white/20 text-white hover:bg-white/40 rounded-full flex items-center justify-center font-bold transition">
                        <i class="fas fa-times"></i>
                    </button>
                    
                    <div class="w-16 h-16 mx-auto bg-white/20 backdrop-blur-md text-white rounded-full flex items-center justify-center text-3xl mb-2 shadow-inner border border-white/30">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-white tracking-wide">PENDAFTARAN DITUTUP</h2>
                </div>

                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Mohon Maaf!</h3>
                    <p class="text-gray-600 text-sm mb-6 leading-relaxed">
                        Registrasi daftar ulang saat ini <strong>belum dibuka</strong> atau <strong>sudah ditutup</strong>.<br><br>
                        Silakan pantau terus website ini atau hubungi admin sekolah SDN Pojok 2 untuk informasi lebih lanjut.
                    </p>
                    
                    <button type="button" @click="bukaModalTutup = false" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition duration-200 text-sm">
                        Tutup Pesan Ini
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Script Validasi Input Khusus Angka -->
    <script>
        const isDigitsOnlyInput = (input) => input instanceof HTMLInputElement && input.matches('[data-digits-only]');
        const showDigitsOnlyWarning = (input, visible) => {
            let warning = input.nextElementSibling;
            if (!warning?.matches('[data-numeric-warning]')) {
                warning = document.createElement('p');
                warning.dataset.numericWarning = '';
                warning.className = 'mt-1 text-xs text-red-600';
                warning.setAttribute('role', 'status');
                warning.textContent = 'Kolom ini hanya boleh diisi angka.';
                warning.hidden = true;
                input.insertAdjacentElement('afterend', warning);
            }
            warning.hidden = !visible;
        };

        document.addEventListener('beforeinput', (event) => {
            const input = event.target;
            const insertedText = event.data ?? event.dataTransfer?.getData('text') ?? '';
            if (isDigitsOnlyInput(input) && insertedText !== '' && /[^0-9]/.test(insertedText)) {
                event.preventDefault();
                showDigitsOnlyWarning(input, true);
            }
        });

        document.addEventListener('paste', (event) => {
            const input = event.target;
            const pastedText = event.clipboardData?.getData('text') ?? '';
            if (isDigitsOnlyInput(input) && /[^0-9]/.test(pastedText)) {
                event.preventDefault();
                showDigitsOnlyWarning(input, true);
            }
        });

        document.addEventListener('input', (event) => {
            const input = event.target;
            if (!isDigitsOnlyInput(input)) return;

            const digitsOnlyValue = input.value.replace(/[^0-9]/g, '');
            const containedNonDigits = digitsOnlyValue !== input.value;
            if (containedNonDigits) input.value = digitsOnlyValue;
            showDigitsOnlyWarning(input, containedNonDigits);
        });
    </script>
</x-layouts.app>