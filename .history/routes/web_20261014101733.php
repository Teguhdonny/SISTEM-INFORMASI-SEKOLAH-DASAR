<?php

use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\PendaftaranWebController;
use App\Models\Guru;
use Illuminate\Support\Facades\Route;

// ==============================
// RUTE PUBLIK (Sesuai Navbar Website)
// ==============================

Route::get('/', function () {
    return view('pages.public.home');
});

Route::get('/profil', function () {
    return view('pages.public.profil');
});

Route::get('/visi-misi', function () {
    return view('pages.public.visi-misi');
});

Route::get('/daftar-guru', function () {
    $guru = Guru::query()->orderBy('nama')->paginate(12);
    return view('pages.public.guru', compact('guru'));
})->name('guru.public.index');

Route::get('/ekstrakurikuler', function () {
    return view('pages.public.ekstrakurikuler');
});

Route::get('/program-sekolah', function () {
    return view('pages.public.program');
});

// Halaman Registrasi Daftar Ulang (PPDB Publik - Tanpa Login)
Route::get('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'create']);
Route::post('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'store']);


// ==============================
// RUTE AUTENTIKASI & ADMIN
// ==============================

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/admin/dashboard', function () {
    return view('pages.admin.dashboard.index');
});

Route::resource('/admin/guru', GuruController::class)->names('admin.guru');

// Panel Admin PPDB & Menu Lainnya
Route::get('/admin/pendaftaran', [PendaftaranWebController::class, 'adminIndex']);
Route::post('/admin/pendaftaran/{id}/status', [PendaftaranWebController::class, 'updateStatus']);

Route::get('/admin/galeri', function () {
    return view('pages.admin.galeri.index');
});

Route::get('/admin/ekstrakurikuler', function () {
    return view('pages.admin.ekstrakurikuler.index');
});

Route::get('/pendaftaran', [PendaftaranController::class, 'index']);
Route::post('/pendaftaran', [PendaftaranController::class, 'store']);
Route::post('/pendaftaran/{id}/validasi', [PendaftaranController::class, 'validasiStatus']);