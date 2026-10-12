<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('daftar_ulang', function (Blueprint $table) {
        $table->id();
        // Hubungkan ke tabel siswa_ppdb
        $table->foreignId('id_siswa_ppdb')->constrained('siswa_ppdb')->onDelete('cascade');
        
        $table->string('nama_wali', 100);
        $table->string('no_whatsapp', 20);
        
        // Lajur Dokumen Utama & Pendukung (Dibuat nullable agar fleksibel)
        $table->string('file_kk')->nullable();
        $table->string('file_akta')->nullable();
        $table->string('file_foto_rumah')->nullable();
        $table->string('file_ktp_ortu')->nullable();
        $table->string('file_surat_pernyataan')->nullable();
        $table->string('file_pas_foto')->nullable();
        
        $table->string('status', 50)->default('Menunggu Verifikasi');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_ulang');
    }
};