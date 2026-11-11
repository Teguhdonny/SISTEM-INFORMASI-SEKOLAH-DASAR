<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_pindahans', function (Blueprint $table): void {
            $table->id();
            $table->string('no_pendaftaran', 40)->unique();
            $table->string('nama_siswa', 150);
            $table->string('nisn', 10)->index();
            $table->string('nik', 16);
            $table->string('jenis_kelamin', 20);
            $table->string('asal_sekolah', 150);
            $table->string('kelas_asal', 50);
            $table->string('kelas_tujuan', 50);
            $table->string('tahun_ajaran', 9);
            $table->text('alasan_pindah');
            $table->string('nama_wali', 100);
            $table->string('no_whatsapp', 20);
            $table->string('file_kk');
            $table->string('file_akta');
            $table->string('file_rapor');
            $table->string('file_surat_pindah')->nullable();
            $table->string('status', 40)->default('Menunggu Verifikasi')->index();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pindahans');
    }
};
