<?php

use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EkstrakurikulerController;
use App\Http\Controllers\Admin\GaleriController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JadwalPelajaranController as AdminJadwalPelajaranController;
use App\Http\Controllers\Admin\KategoriKontenController;
use App\Http\Controllers\Admin\PendaftaranPindahanController;
use App\Http\Controllers\Admin\ProfilSekolahController;
use App\Http\Controllers\Admin\ProgramSekolahController;
use App\Http\Controllers\Admin\RegistrationPeriodController;
use App\Http\Controllers\Admin\SiswaPpdbController;
use App\Http\Controllers\Admin\StudentDataTransferController;
use App\Http\Controllers\Api\PendaftaranController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\JadwalPelajaranController;
use App\Http\Controllers\PendaftaranPindahanWebController;
use App\Http\Controllers\PendaftaranWebController;
use App\Models\Berita;
use App\Models\Ekstrakurikuler;
use App\Models\Galeri;
use App\Models\Guru;
use App\Models\ProfilSekolah;
use App\Models\ProgramSekolah;
use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use App\Models\VisiMisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==============================
// RUTE PUBLIK (Sesuai Navbar Website)
// ==============================

Route::get('/', function () {
    $beritaAktif = Berita::query()
        ->where('status', 'Aktif')
        ->latest('tanggal_kegiatan')
        ->latest('id')
        ->limit(4)
        ->get();

    return view('pages.public.home', compact('beritaAktif'));
})->name('home');

Route::get('/profil', function () {
    $profilSekolah = ProfilSekolah::query()->first();
    $galeri = Galeri::query()->latest('id')->get();
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

    return view('pages.public.profil', compact('profilSekolah', 'visi', 'misi', 'galeri'));
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

Route::get('/ekstrakurikuler', function (Request $request) {
    $categories = Ekstrakurikuler::query()->where('status', 'Aktif')->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
    $selectedCategory = $request->query('kategori');
    if (! $categories->contains($selectedCategory)) {
        $selectedCategory = null;
    }

    $ekstrakurikuler = Ekstrakurikuler::query()
        ->where('status', 'Aktif')
        ->when($selectedCategory, fn ($query) => $query->where('kategori', $selectedCategory))
        ->orderBy('nama_ekskul')
        ->get();

    return view('pages.public.ekstrakurikuler', compact('ekstrakurikuler', 'categories', 'selectedCategory'));
})->name('ekstrakurikuler.public.index');

Route::get('/program-sekolah', function (Request $request) {
    $categories = ProgramSekolah::query()->where('status', 'Aktif')->whereNotNull('kategori')->distinct()->orderBy('kategori')->pluck('kategori');
    $selectedCategory = $request->query('kategori');
    if (! $categories->contains($selectedCategory)) {
        $selectedCategory = null;
    }

    $programs = ProgramSekolah::query()
        ->where('status', 'Aktif')
        ->when($selectedCategory, fn ($query) => $query->where('kategori', $selectedCategory))
        ->orderBy('nama_program')
        ->get();

    return view('pages.public.program', compact('programs', 'categories', 'selectedCategory'));
})->name('program.public.index');

// Tambahkan 2 rute ini di bagian RUTE PUBLIK pada routes/web.php:

Route::get('/jadwal-pelajaran', [JadwalPelajaranController::class, 'index'])->name('jadwal.public.index');

Route::get('/informasi-ppdb', function () {
    $tahunAjaran = RegistrationPeriod::query()->value('tahun_ajaran');
    $siswaDiterima = SiswaPpdb::query()
        ->where('dicatat_admin', true)
        ->where('status_diterima', true)
        ->whereNotNull('tahun_ajaran')
        ->where('tahun_ajaran', $tahunAjaran)
        ->orderBy('nama_siswa')
        ->paginate(12, ['id', 'nama_siswa', 'jenis_kelamin']);

    return view('pages.public.ppdb', compact('siswaDiterima', 'tahunAjaran'));
})->name('ppdb.public.index');

// Halaman Registrasi Daftar Ulang (PPDB Publik - Tanpa Login)
Route::get('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'create'])->name('registrasi.public.create');
Route::post('/registrasi-daftar-ulang', [PendaftaranWebController::class, 'store'])->name('registrasi.public.store');
Route::get('/pendaftaran-pindahan', [PendaftaranPindahanWebController::class, 'create'])->name('pendaftaran-pindahan.public.create');
Route::post('/pendaftaran-pindahan', [PendaftaranPindahanWebController::class, 'store'])->name('pendaftaran-pindahan.public.store');

// ==============================
// RUTE AUTENTIKASI & ADMIN
// ==============================

Route::get('/login', [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware(['guest', 'throttle:5,1'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.dashboard');
Route::get('/admin/notifikasi', [AdminNotificationController::class, 'feed'])->middleware(['auth', 'admin'])->name('admin.notifications.feed');
Route::post('/admin/notifikasi/{notificationId}/buka', [AdminNotificationController::class, 'open'])->middleware(['auth', 'admin'])->name('admin.notifications.open');

// Rute Admin Profil Sekolah & Visi Misi (BARU DITAMBAHKAN)
Route::get('/admin/profil', [ProfilSekolahController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.profil.index');
Route::put('/admin/profil', [ProfilSekolahController::class, 'update'])->middleware(['auth', 'admin'])->name('admin.profil.update');

Route::resource('/admin/guru', GuruController::class)->names('admin.guru')->middleware(['auth', 'admin']);
Route::resource('/admin/jadwal-pelajaran', AdminJadwalPelajaranController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->parameters(['jadwal-pelajaran' => 'jadwalPelajaran'])->names('admin.jadwal')->middleware(['auth', 'admin']);

Route::resource('/admin/program-sekolah', ProgramSekolahController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->parameters(['program-sekolah' => 'programSekolah'])->names('admin.program')->middleware(['auth', 'admin']);
Route::get('/admin/kategori-konten', [KategoriKontenController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.kategori-konten.index');
Route::post('/admin/kategori-konten', [KategoriKontenController::class, 'store'])->middleware(['auth', 'admin'])->name('admin.kategori-konten.store');
Route::put('/admin/kategori-konten/{kategoriKonten}', [KategoriKontenController::class, 'update'])->middleware(['auth', 'admin'])->name('admin.kategori-konten.update');
Route::delete('/admin/kategori-konten/{kategoriKonten}', [KategoriKontenController::class, 'destroy'])->middleware(['auth', 'admin'])->name('admin.kategori-konten.destroy');

Route::resource('/admin/berita', BeritaController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['berita' => 'berita'])->names('admin.berita')->middleware(['auth', 'admin']);

// Panel Admin PPDB & Menu Lainnya
Route::get('/admin/pendaftaran', [PendaftaranWebController::class, 'adminIndex'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.index');
Route::get('/admin/pendaftaran/export/excel', [StudentDataTransferController::class, 'daftarUlangExcel'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.export.xlsx');
Route::get('/admin/pendaftaran/export/pdf', [StudentDataTransferController::class, 'daftarUlangPdf'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.export.pdf');
Route::get('/admin/pendaftaran/template-excel', [StudentDataTransferController::class, 'daftarUlangTemplate'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.template.xlsx');
Route::post('/admin/pendaftaran/import-excel', [StudentDataTransferController::class, 'importDaftarUlang'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.import.xlsx');
Route::post('/admin/pendaftaran/{id}/status', [PendaftaranWebController::class, 'updateStatus'])->middleware(['auth', 'admin'])->name('admin.pendaftaran.status');
Route::get('/admin/pendaftaran-pindahan', [PendaftaranPindahanController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.index');
Route::get('/admin/pendaftaran-pindahan/export/excel', [StudentDataTransferController::class, 'pindahanExcel'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.export.xlsx');
Route::get('/admin/pendaftaran-pindahan/export/pdf', [StudentDataTransferController::class, 'pindahanPdf'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.export.pdf');
Route::get('/admin/pendaftaran-pindahan/template-excel', [StudentDataTransferController::class, 'pindahanTemplate'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.template.xlsx');
Route::post('/admin/pendaftaran-pindahan/import-excel', [StudentDataTransferController::class, 'importPindahan'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.import.xlsx');
Route::delete('/admin/pendaftaran-pindahan/{pendaftaranPindahan}', [PendaftaranPindahanController::class, 'destroy'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.destroy');
Route::post('/admin/pendaftaran-pindahan/{pendaftaranPindahan}/status', [PendaftaranPindahanController::class, 'updateStatus'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.status');
Route::get('/admin/pendaftaran-pindahan/{pendaftaranPindahan}/dokumen/{document}', [PendaftaranPindahanController::class, 'downloadDocument'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.document');
Route::get('/admin/pendaftaran-pindahan/{pendaftaranPindahan}/dokumen/{document}/preview', [PendaftaranPindahanController::class, 'previewDocument'])->middleware(['auth', 'admin'])->name('admin.pendaftaran-pindahan.document.preview');
Route::get('/admin/jadwal-registrasi', [RegistrationPeriodController::class, 'edit'])->middleware(['auth', 'admin'])->name('admin.registration-period.edit');
Route::put('/admin/jadwal-registrasi', [RegistrationPeriodController::class, 'update'])->middleware(['auth', 'admin'])->name('admin.registration-period.update');
Route::resource('/admin/data-ppdb', SiswaPpdbController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->parameters(['data-ppdb' => 'siswaPpdb'])->names('admin.ppdb')->middleware(['auth', 'admin']);
Route::get('/admin/data-ppdb/export/excel', [StudentDataTransferController::class, 'ppdbExcel'])->middleware(['auth', 'admin'])->name('admin.ppdb.export.xlsx');
Route::get('/admin/data-ppdb/export/pdf', [StudentDataTransferController::class, 'ppdbPdf'])->middleware(['auth', 'admin'])->name('admin.ppdb.export.pdf');
Route::get('/admin/data-ppdb/template-excel', [StudentDataTransferController::class, 'ppdbTemplate'])->middleware(['auth', 'admin'])->name('admin.ppdb.template.xlsx');
Route::post('/admin/data-ppdb/import-excel', [StudentDataTransferController::class, 'importPpdb'])->middleware(['auth', 'admin'])->name('admin.ppdb.import.xlsx');

Route::resource('/admin/galeri', GaleriController::class)
    ->only(['index', 'store', 'update', 'destroy'])
    ->names('admin.galeri')
    ->middleware(['auth', 'admin']);

Route::resource('/admin/ekstrakurikuler', EkstrakurikulerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('admin.ekstrakurikuler')->middleware(['auth', 'admin']);

Route::get('/pendaftaran', [PendaftaranController::class, 'index']);
Route::post('/pendaftaran', [PendaftaranController::class, 'store']);
Route::post('/pendaftaran/{id}/validasi', [PendaftaranController::class, 'validasi'])->middleware(['auth', 'admin']);
