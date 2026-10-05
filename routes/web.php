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


// ==============================
// RUTE AUTENTIKASI & ADMIN
// ==============================

// Route untuk Halaman Login Admin
Route::get('/login', function () {
    return view('auth.login');
});

// Route sementara untuk melihat kerangka Dashboard Admin
Route::get('/admin/dashboard', function () {
    // Memanggil layout admin dengan konten placeholder sementara
    return view('components.layouts.admin')->with('slot', '
        <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang di Dashboard</h2>
            <p class="text-gray-500">Konten widget dan statistik akan dirakit di halaman ini pada langkah selanjutnya.</p>
        </div>
    ');
});