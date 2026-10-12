<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Daftar Ulang - SDN Pojok 2 Kota Kediri</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex flex-col min-h-screen font-sans">

    <!-- Memanggil Navbar Publik -->
    @include('components.public.navbar')

    <!-- Hero Banner Dinamis & Modern -->
    <section class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-16 px-6 overflow-hidden shadow-md">
        <!-- Efek Cahaya Latar Belakang (Glow) -->
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
                Lengkapi data dan unggah dokumen yang diperlukan untuk proses daftar ulang peserta didik baru. Pastikan semua berkas sesuai dengan ketentuan yang berlaku.
            </p>
        </div>
    </section>

    <!-- Konten Utama -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        
        <!-- Notifikasi Sukses (Tampil di halaman utama jika berhasil dan modal tertutup) -->
        @if(session('success'))
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-xl shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-3">
                <i class="fas fa-check-circle text-green-600 text-xl"></i>
                <div>
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            </div>
            <a href="/admin/pendaftaran" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-2 rounded-lg shadow transition">
                Cek Data di Admin &rarr;
            </a>
        </div>
        @endif

        <!-- Notifikasi Error Validasi -->
        @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl shadow-sm relative flex items-center gap-3" role="alert">
            <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
            <div>
                <strong class="font-bold">Perhatian!</strong> Terdapat kesalahan pengisian data. Silakan periksa kembali pada form pop-up.
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
                            <h2 class="text-lg font-bold text-gray-900">Alur Daftar Ulang</h2>
                            <p class="text-xs text-gray-500">Ikuti langkah-langkah berikut untuk menyelesaikan proses daftar ulang.</p>
                        </div>
                    </div>
                    
                    <!-- Stepper Visual -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-center mt-6">
                        <div class="p-4 bg-blue-50/50 rounded-xl border border-blue-100">
                            <div class="w-8 h-8 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-sm">1</div>
                            <h4 class="font-semibold text-xs text-gray-800">Lengkapi Data Pribadi</h4>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-8 h-8 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-sm">2</div>
                            <h4 class="font-semibold text-xs text-gray-800">Unggah Dokumen</h4>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="w-8 h-8 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm mb-2 shadow-sm">3</div>
                            <h4 class="font-semibold text-xs text-gray-800">Verifikasi Admin</h4>
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
                            <p class="text-xs text-gray-500">Siapkan dokumen dalam format JPG, PNG, atau PDF (Maksimal 2MB per file).</p>
                        </div>
                    </div>

                    <div class="space-y-4 mt-6">
                        <!-- Item Dokumen 1 -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Kartu Keluarga (KK)</h4>
                                    <p class="text-xs text-gray-500">Scan/Foto Kartu Keluarga terbaru.</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-full">Wajib</span>
                        </div>

                        <!-- Item Dokumen 2 -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-lg">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Akta Kelahiran</h4>
                                    <p class="text-xs text-gray-500">Scan/Foto Akta Kelahiran peserta didik.</p>
                                </div>
                            </div>
                            <span class="text-xs font-semibold text-red-600 bg-red-50 border border-red-100 px-3 py-1 rounded-full">Wajib</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan (Sidebar: Info Penting, Kontak, & Tombol Mulai) -->
            <div class="space-y-6">
                
                <!-- Informasi Penting -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i> Informasi Penting
                    </h3>
                    <ul class="text-xs text-gray-600 space-y-2.5 list-disc list-inside leading-relaxed">
                        <li>Daftar ulang wajib dilakukan sesuai jadwal yang telah ditentukan.</li>
                        <li>Pastikan semua dokumen yang diunggah sudah lengkap dan jelas.</li>
                        <li>Jika terdapat kendala, silakan hubungi admin sekolah melalui kontak yang tersedia.</li>
                    </ul>
                </div>

                <!-- Kontak Admin -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-headset text-blue-600"></i> Kontak Admin
                    </h3>
                    <div class="text-xs text-gray-600 space-y-3">
                        <p class="flex items-start gap-2"><i class="fas fa-map-marker-alt text-red-500 mt-0.5"></i> <span>Jl. Mastrip 59, Pojok, Kec. Mojoroto, Kota Kediri</span></p>
                        <p class="flex items-center gap-2"><i class="fas fa-phone text-green-500"></i> <span>(0354) 123456</span></p>
                        <p class="flex items-center gap-2"><i class="fas fa-envelope text-blue-500"></i> <span>sdnpojok2@gmail.com</span></p>
                    </div>
                </div>

                <!-- Box Call to Action (Mulai Daftar Ulang) -->
                <div class="bg-gradient-to-br from-blue-900 to-blue-800 rounded-2xl shadow-md p-6 text-white text-center">
                    <div class="w-12 h-12 mx-auto bg-blue-700 rounded-full flex items-center justify-center text-xl mb-3 shadow">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h3 class="font-bold text-base mb-1">Mulai Daftar Ulang Sekarang</h3>
                    <p class="text-xs text-blue-200 mb-4">Pastikan semua data dan dokumen sudah siap sebelum melanjutkan.</p>
                    <button id="openModalBtn" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow-md transition duration-200 text-sm flex items-center justify-center gap-2">
                        <span>Lanjutkan</span> <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>

        </div>
    </main>

    <!-- POPUP MODAL BORANG PENDAFTARAN -->
    <div id="pendaftaranModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4 hidden overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 relative">
            
            <!-- Tombol Tutup (X) -->
            <button id="closeModalBtn" class="absolute top-5 right-5 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center font-bold transition">
                <i class="fas fa-times"></i>
            </button>

            <div class="mb-6 pr-8">
                <h2 class="text-2xl font-bold text-gray-900">Formulir Pendaftaran</h2>
                <p class="text-xs text-gray-500 mt-1">Silakan lengkapi data diri dan unggah berkas dengan benar.</p>
            </div>

            <!-- Form Pendaftaran -->
            <form action="{{ url('/registrasi-daftar-ulang') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor Pendaftaran</label>
                        <input type="text" name="no_pendaftaran" required placeholder="Contoh: PPDB-2026-001" value="{{ old('no_pendaftaran') }}"
                            class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">NISN</label>
                        <input type="text" name="nisn" required placeholder="Nomor Induk Siswa Nasional" value="{{ old('nisn') }}"
                            class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap Siswa</label>
                        <input type="text" name="nama_siswa" required placeholder="Sesuai Akta Kelahiran" value="{{ old('nama_siswa') }}"
                            class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jenjang / Kelas</label>
                        <select name="jenjang" class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                            <option value="Kelas 1">Kelas 1</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Asal Sekolah (TK/RA)</label>
                        <input type="text" name="asal_sekolah" placeholder="Nama TK asal" value="{{ old('asal_sekolah') }}"
                            class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Orang Tua / Wali</label>
                        <input type="text" name="nama_wali" required placeholder="Nama Ayah/Ibu/Wali" value="{{ old('nama_wali') }}"
                            class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nomor WhatsApp Aktif</label>
                    <input type="text" name="no_whatsapp" required placeholder="08xxxxxxxxxx" value="{{ old('no_whatsapp') }}"
                        class="w-full rounded-xl border border-gray-300 p-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <hr class="my-4 border-gray-200">

                <h3 class="text-sm font-bold text-gray-800">Unggah Berkas / Dokumen</h3>
                <p class="text-xs text-gray-500">Format file: JPG, PNG, atau PDF (Maksimal 2MB per file).</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="border border-gray-200 p-3.5 rounded-xl bg-gray-50">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Akta Kelahiran</label>
                        <input type="file" name="file_akta" accept=".jpg,.jpeg,.png,.pdf" 
                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>

                    <div class="border border-gray-200 p-3.5 rounded-xl bg-gray-50">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Kartu Keluarga (KK)</label>
                        <input type="file" name="file_kk" accept=".jpg,.jpeg,.png,.pdf" 
                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-md transition duration-200 text-sm">
                        Kirim Pendaftaran Daftar Ulang
                    </button>
                </div>

            </form>
        </div>
    </div>

    <!-- Memanggil Komponen Footer Publik yang Sudah Ada -->
    @include('components.public.footer')

    <!-- Skrip JavaScript untuk Modal & Auto Show saat Error Validasi -->
    <script>
        const modal = document.getElementById('pendaftaranModal');
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Buka modal otomatis jika terdapat ralat validasi dari form
        @if($errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                modal.classList.remove('hidden');
            });
        @endif
    </script>

</body>
</html>