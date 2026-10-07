<?php

use Illuminate\Support\Facades\Route;

// ==============================
// RUTE PUBLIK
// ==============================

// Rute untuk Halaman Beranda (Laman Utama)
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


// ==============================
// RUTE AUTENTIKASI & ADMIN
// ==============================

// Route untuk Halaman Login Admin
Route::get('/login', function () {
    return view('auth.login');
});

// Route untuk Dashboard Admin
Route::get('/admin/dashboard', function () {
    // Memanggil view yang sesuai dengan struktur folder: pages > admin > dashboard > index.blade.php
    return view('pages.admin.dashboard.index');
});