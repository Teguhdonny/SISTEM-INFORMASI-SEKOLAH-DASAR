<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EkstrakurikulerController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\ProfilSekolahController;
use App\Http\Controllers\Admin\ProgramSekolahController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PendaftaranWebController;
use App\Models\Ekstrakurikuler;
use App\Models\Guru;
use App\Models\ProfilSekolah;
use App\Models\ProgramSekolah;
use App\Models\VisiMisi;
use Illuminate\Support\Facades\Route;

// ==============================
// RUTE PUBLIK (Sesuai Navbar Website)
// ==============================

Route::get('/', function () {
    return view('pages.public.home');
})->name('home');

Route::get('/profil', function () {
    $profilSekolah = ProfilSekolah::query()->first();
    $visi = VisiMisi::query()->where('kategori', 'Visi')->value('deskripsi')
        ?? 'Mewujudkan Generasi Muda yang Berakhlak Mulia, Mandiri, Kreatif Berwawasan Global dan Peduli Lingkungan.';
    $misi = VisiMisi::query()->where('kategori', 'Misi')->orderBy('id')->pluck('deskripsi');
    if ($misi->isEmpty()) {
        $misi = collect([
            'Melaksanakan pembelajaran dan bimbingan secara efektif dan efisien, sehingga setiap siswa dapat berkembang secara optimal.',
            'Menumbuhkan semangat keunggulan secara kompetitif dan interaktif kepada seluruh warga sekolah.',
            'Mengembangkan potensi berdasarkan minat dan bakat anak.',
            'Menanamkan perilaku hidup sesuai norma-norma dan ajaran agama.',
            'Menumbuhkan rasa cinta lingkungan sebagai sumber belajar.',
        ]);
    }

    return view('pages.public.profil', compact('profilSekolah', 'visi', 'misi'));
})->name('profil.public.index');

Route::get('/visi-misi', function () {
    $profilSekolah = ProfilSekolah::query()->first();
    $visi = VisiMisi::query()->where('kategori', 'Visi')->value('deskripsi')
        ?? 'Mewujudkan Generasi Muda yang Berakhlak Mulia, Mandiri, Kreatif Berwawasan Global dan Peduli Lingkungan.';
    $misi = VisiMisi::query()->where('kategori', 'Misi')->orderBy('id')->pluck('deskripsi');
    if ($misi->isEmpty()) {
        $misi = collect([
            'Melaksanakan pembelajaran dan bimbingan secara efektif dan efisien, sehingga setiap siswa dapat berkembang secara optimal.',
            'Menumbuhkan semangat keunggulan secara kompetitif dan interaktif kepada seluruh warga sekolah.',
            'Mengembangkan potensi berdasarkan minat dan bakat anak.',
            'Menanamkan perilaku hidup sesuai norma-norma dan ajaran agama.',
            'Menumbuhkan rasa cinta lingkungan sebagai sumber belajar.',
        ]);
    }

    return view('pages.public.visi-misi', compact('profilSekolah', 'visi', 'misi'));
})->name('visi-misi.public.index');

Route::get('/daftar-guru', function () {
    $guru = Guru::query()->orderBy('nama')->paginate(12);

    return view('pages.public.guru', compact('guru'));
})->name('guru.public.index');

Route::get('/ekstrakurikuler', function () {
    $ekstrakurikuler = Ekstrakurikuler::query()->where('status', 'Aktif')->orderBy('nama_ekskul')->get();

    return view('pages.public.ekstrakurikuler', compact('ekstrakurikuler'));
})->name('ekstrakurikuler.public.index');

Route::get('/program-sekolah', function () {
    $programs = ProgramSekolah::query()->where('status', 'Aktif')->orderBy('nama_program')->get();

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

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');
Route::get('/admin/pencarian', [SearchController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.search');

Route::post('/admin/pendaftaran/toggle-status', [\App\Http\Controllers\PendaftaranWebController::class, 'toggleStatus'])->name('admin.ppdb.toggle');

// Rute Admin Profil Sekolah & Visi Misi (BARU DITAMBAHKAN)
Route::get('/admin/profil', [ProfilSekolahController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.profil.index');
Route::put('/admin/profil', [ProfilSekolahController::class, 'update'])->middleware(['auth', 'admin'])->name('admin.profil.update');

Route::resource('/admin/guru', GuruController::class)->names('admin.guru')->middleware(['auth', 'admin']);

Route::resource('/admin/program-sekolah', ProgramSekolahController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->parameters(['program-sekolah' => 'programSekolah'])->names('admin.program')->middleware(['auth', 'admin']);

// Panel Admin PPDB & Menu Lainnya
Route::get('/admin/pendaftaran', [PendaftaranWebController::class, 'adminIndex'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.index');
Route::post('/admin/pendaftaran/{id}/status', [PendaftaranWebController::class, 'updateStatus'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.status');

Route::get('/admin/galeri', function () {
    return view('pages.admin.galeri.index');
})->middleware(['auth', 'admin'])->name('admin.galeri.index');

Route::resource('/admin/ekstrakurikuler', EkstrakurikulerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('admin.ekstrakurikuler')->middleware(['auth', 'admin']);

Route::get('/pendaftaran', [PendaftaranController::class, 'index']);
Route::post('/pendaftaran', [PendaftaranController::class, 'store']);
Route::post('/pendaftaran/{id}/validasi', [PendaftaranController::class, 'validasiStatus']);
