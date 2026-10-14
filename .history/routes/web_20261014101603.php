<?php

use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\PendaftaranWebController;
use App\Models\Guru;
use Illuminate\Support\Facades\Route;

// ==============================
// RUTE PUBLIK (Sesuai Navbar Website)
// ==============================

// Rute untuk Halaman Beranda
Route::get('/', function () {
    return view('pages.public.home');
});

// Rute untuk Halaman Profil Sekolah
Route::get('/profil', function () {
    return view('pages.public.profil');
});

// Rute untuk Halaman Visi & Misi
Route::get('/visi-misi', function () {
    return view('pages.public.visi-misi');
});

// Rute untuk Halaman Daftar Guru
Route::get('/daftar-guru', function () {
    $guru = Guru::query()->orderBy('nama')->paginate(12);

    return view('pages.public.guru', compact('guru'));
})->name('guru.public.index');

// Rute untuk Halaman Ekstrakurikuler
Route::get('/ekstrakurikuler', function () {
    return view('pages.public.ekstrakurikuler');
});

// Rute untuk Halaman Program Sekolah
Route::get('/program-sekolah', function () {
    return view('pages.public.program');
});

// Rute untuk Halaman Registrasi Daftar Ulang (PPDB Publik)
Route::get('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'create']);
Route::post('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'store']);

// ==============================
// RUTE AUTENTIKASI & ADMIN
// ==============================

// Route untuk Halaman Login Admin
Route::get('/login', function () {
    return view('auth.login');
});

// Route untuk Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('pages.admin.dashboard.index');
});

Route::resource('/admin/guru', GuruController::class)->names('admin.guru');

// Route Tambahan Panel Admin (Menu Sidebar Admin PPDB & Galeri)
Route::get('/admin/pendaftaran', [PendaftaranWebController::class, 'adminIndex']);

Route::get('/admin/galeri', function () {
    return view('pages.admin.galeri.index');
});

Route::get('/admin/ekstrakurikuler', function () {
    return view('pages.admin.ekstrakurikuler.index');

});

// Tambahkan di dalam fail routes/web.php

Route::get('/admin/pendaftaran', [PendaftaranWebController::class, 'adminIndex']);
Route::post('/admin/pendaftaran/{id}/status', [PendaftaranWebController::class, 'updateStatus']);

// di routes/api.php
Route::post('/pendaftaran/{id}/validasi', [PendaftaranController::class, 'validasiStatus']);