<?php

use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PendaftaranWebController;
use App\Models\Guru;
use Illuminate\Support\Facades\Route;

// ==============================
// RUTE PUBLIK (Sesuai Navbar Website)
// ==============================

Route::get('/', function () {
    return view('pages.public.home');
})->name('home');

Route::get('/profil', function () {
    return view('pages.public.profil');
})->name('profil.public.index');

Route::get('/visi-misi', function () {
    return view('pages.public.visi-misi');
})->name('visi-misi.public.index');

Route::get('/daftar-guru', function () {
    $guru = Guru::query()->orderBy('nama')->paginate(12);

    return view('pages.public.guru', compact('guru'));
})->name('guru.public.index');

Route::get('/ekstrakurikuler', function () {
    $ekstrakurikuler = [
        ['nama' => 'Pramuka', 'kategori' => 'Kepemimpinan', 'deskripsi' => 'Melatih kemandirian, kedisiplinan, kerja sama, dan kepedulian melalui kegiatan kepramukaan yang menyenangkan.', 'jadwal' => 'Jumat, 13.00–15.00', 'pembina' => 'Ibu Siti Rahmawati', 'peserta' => 48, 'ikon' => 'fa-compass', 'warna' => 'blue'],
        ['nama' => 'Futsal', 'kategori' => 'Olahraga', 'deskripsi' => 'Mengembangkan kebugaran, keterampilan bermain bola, sportivitas, dan kekompakan tim.', 'jadwal' => 'Selasa, 14.00–15.30', 'pembina' => 'Bapak Agus Setiawan', 'peserta' => 32, 'ikon' => 'fa-futbol', 'warna' => 'green'],
        ['nama' => 'Seni Tari', 'kategori' => 'Seni & Budaya', 'deskripsi' => 'Mengenal ragam tari daerah, melatih percaya diri, dan mengekspresikan kreativitas melalui gerak.', 'jadwal' => 'Rabu, 13.30–15.00', 'pembina' => 'Ibu Rina Puspitasari', 'peserta' => 24, 'ikon' => 'fa-masks-theater', 'warna' => 'purple'],
        ['nama' => 'Karawitan', 'kategori' => 'Seni & Budaya', 'deskripsi' => 'Belajar memainkan gamelan dan mengenal musik tradisional Jawa dalam suasana kolaboratif.', 'jadwal' => 'Kamis, 13.30–15.00', 'pembina' => 'Bapak Bambang Suyono', 'peserta' => 18, 'ikon' => 'fa-music', 'warna' => 'red'],
        ['nama' => 'Bola Voli', 'kategori' => 'Olahraga', 'deskripsi' => 'Membangun koordinasi, kebugaran, disiplin latihan, dan semangat saling mendukung.', 'jadwal' => 'Rabu, 14.00–15.30', 'pembina' => 'Bapak Agus Setiawan', 'peserta' => 26, 'ikon' => 'fa-volleyball', 'warna' => 'blue'],
        ['nama' => 'Dokter Kecil', 'kategori' => 'Kesehatan', 'deskripsi' => 'Mengenalkan perilaku hidup bersih dan sehat serta kepedulian terhadap kesehatan diri dan teman.', 'jadwal' => 'Sabtu, 09.00–10.30', 'pembina' => 'Ibu Dewi Lestari', 'peserta' => 20, 'ikon' => 'fa-kit-medical', 'warna' => 'green'],
    ];

    return view('pages.public.ekstrakurikuler', compact('ekstrakurikuler'));
})->name('ekstrakurikuler.public.index');

Route::get('/program-sekolah', function () {
    $programs = [
        ['kategori' => 'Akademik', 'judul' => 'Literasi Pagi', 'deskripsi' => 'Membiasakan siswa membaca selama 15 menit sebelum pembelajaran dimulai untuk menumbuhkan minat baca dan rasa ingin tahu.', 'jadwal' => 'Setiap hari sekolah', 'ikon' => 'fa-book-open', 'warna' => 'blue'],
        ['kategori' => 'Karakter', 'judul' => 'Jumat Berkarakter', 'deskripsi' => 'Kegiatan bersama untuk belajar disiplin, peduli lingkungan, beribadah, dan menghargai sesama melalui aktivitas yang menyenangkan.', 'jadwal' => 'Setiap hari Jumat', 'ikon' => 'fa-heart', 'warna' => 'red'],
        ['kategori' => 'Lingkungan', 'judul' => 'Sekolah Hijau', 'deskripsi' => 'Mengajak warga sekolah merawat tanaman, memilah sampah, dan menjaga kebersihan ruang kelas serta lingkungan sekolah.', 'jadwal' => 'Sepanjang tahun ajaran', 'ikon' => 'fa-leaf', 'warna' => 'green'],
        ['kategori' => 'Akademik', 'judul' => 'Kelas Numerasi', 'deskripsi' => 'Melatih kemampuan berhitung dan pemecahan masalah dengan permainan, proyek sederhana, serta contoh dari kehidupan sehari-hari.', 'jadwal' => 'Selasa & Kamis', 'ikon' => 'fa-calculator', 'warna' => 'amber'],
        ['kategori' => 'Kesehatan', 'judul' => 'Jumat Sehat', 'deskripsi' => 'Menjaga kebugaran dan membangun kebiasaan hidup sehat melalui senam, permainan gerak, dan edukasi kesehatan.', 'jadwal' => 'Setiap hari Jumat', 'ikon' => 'fa-person-running', 'warna' => 'purple'],
        ['kategori' => 'Karakter', 'judul' => 'Pojok Berbagi', 'deskripsi' => 'Wadah bagi siswa untuk belajar berbagi, bekerja sama, dan menunjukkan kepedulian melalui aksi sosial di sekolah.', 'jadwal' => 'Satu kali setiap bulan', 'ikon' => 'fa-hands-holding-child', 'warna' => 'rose'],
    ];

    return view('pages.public.program', compact('programs'));
})->name('program.public.index');

// Tambahkan 2 rute ini di bagian RUTE PUBLIK pada routes/web.php:

Route::get('/jadwal-pelajaran', function () {
    return view('pages.public.jadwal');
})->name('jadwal.public.index');

Route::get('/informasi-ppdb', function () {
    return view('pages.public.registrasi.ppdb');
})->name('ppdb.public.index');

// Halaman Registrasi Daftar Ulang (PPDB Publik - Tanpa Login)
Route::get('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'create'])->name('registrasi.public.create');
Route::post('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'store'])->name('registrasi.public.store');

// ==============================
// RUTE AUTENTIKASI & ADMIN
// ==============================

Route::get('/login', [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware(['guest', 'throttle:5,1'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/admin/dashboard', function () {
    return view('pages.admin.dashboard.index');
})->middleware(['auth', 'admin'])->name('admin.dashboard');

// Rute Admin Profil Sekolah & Visi Misi (BARU DITAMBAHKAN)
Route::get('/admin/profil', function () {
    return view('pages.admin.profil.index');
})->middleware(['auth', 'admin'])->name('admin.profil.index');

Route::resource('/admin/guru', GuruController::class)->names('admin.guru')->middleware(['auth', 'admin']);

Route::get('/admin/program-sekolah', function () {
    $programs = [
        ['judul' => 'Literasi Pagi', 'kategori' => 'Akademik', 'jadwal' => 'Setiap hari sekolah', 'status' => 'Aktif', 'peserta' => 186],
        ['judul' => 'Jumat Berkarakter', 'kategori' => 'Karakter', 'jadwal' => 'Setiap hari Jumat', 'status' => 'Aktif', 'peserta' => 186],
        ['judul' => 'Sekolah Hijau', 'kategori' => 'Lingkungan', 'jadwal' => 'Sepanjang tahun ajaran', 'status' => 'Aktif', 'peserta' => 42],
        ['judul' => 'Kelas Numerasi', 'kategori' => 'Akademik', 'jadwal' => 'Selasa & Kamis', 'status' => 'Aktif', 'peserta' => 64],
        ['judul' => 'Jumat Sehat', 'kategori' => 'Kesehatan', 'jadwal' => 'Setiap hari Jumat', 'status' => 'Draft', 'peserta' => 186],
        ['judul' => 'Pojok Berbagi', 'kategori' => 'Karakter', 'jadwal' => 'Satu kali setiap bulan', 'status' => 'Aktif', 'peserta' => 28],
    ];

    return view('pages.admin.program.index', compact('programs'));
})->middleware(['auth', 'admin'])->name('admin.program.index');

// Panel Admin PPDB & Menu Lainnya
Route::get('/admin/pendaftaran', [PendaftaranWebController::class, 'adminIndex'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.index');
Route::post('/admin/pendaftaran/{id}/status', [PendaftaranWebController::class, 'updateStatus'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.status');

Route::get('/admin/galeri', function () {
    return view('pages.admin.galeri.index');
})->middleware(['auth', 'admin'])->name('admin.galeri.index');

Route::get('/admin/ekstrakurikuler', function () {
    $ekstrakurikuler = [
        ['nama' => 'Pramuka', 'kategori' => 'Kepemimpinan', 'jadwal' => 'Jumat, 13.00–15.00', 'pembina' => 'Ibu Siti Rahmawati', 'peserta' => 48, 'status' => 'Aktif'],
        ['nama' => 'Futsal', 'kategori' => 'Olahraga', 'jadwal' => 'Selasa, 14.00–15.30', 'pembina' => 'Bapak Agus Setiawan', 'peserta' => 32, 'status' => 'Aktif'],
        ['nama' => 'Seni Tari', 'kategori' => 'Seni & Budaya', 'jadwal' => 'Rabu, 13.30–15.00', 'pembina' => 'Ibu Rina Puspitasari', 'peserta' => 24, 'status' => 'Aktif'],
        ['nama' => 'Karawitan', 'kategori' => 'Seni & Budaya', 'jadwal' => 'Kamis, 13.30–15.00', 'pembina' => 'Bapak Bambang Suyono', 'peserta' => 18, 'status' => 'Aktif'],
        ['nama' => 'Bola Voli', 'kategori' => 'Olahraga', 'jadwal' => 'Rabu, 14.00–15.30', 'pembina' => 'Bapak Agus Setiawan', 'peserta' => 26, 'status' => 'Aktif'],
        ['nama' => 'Dokter Kecil', 'kategori' => 'Kesehatan', 'jadwal' => 'Sabtu, 09.00–10.30', 'pembina' => 'Ibu Dewi Lestari', 'peserta' => 20, 'status' => 'Draft'],
    ];

    return view('pages.admin.ekstrakurikuler.index', compact('ekstrakurikuler'));
})->middleware(['auth', 'admin'])->name('admin.ekstrakurikuler.index');

Route::get('/pendaftaran', [PendaftaranController::class, 'index']);
Route::post('/pendaftaran', [PendaftaranController::class, 'store']);
Route::post('/pendaftaran/{id}/validasi', [PendaftaranController::class, 'validasiStatus']);