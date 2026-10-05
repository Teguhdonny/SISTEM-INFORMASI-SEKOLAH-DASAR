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
        Schema::create('daftar_ulang', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel siswa_ppdb
            $table->foreignId('id_siswa_ppdb')->constrained('siswa_ppdb')->onDelete('cascade');
            $table->string('nama_wali', 100);
            $table->string('no_whatsapp', 20);
            $table->string('berkas_dokumen', 255);
            $table->enum('status', ['Diproses', 'Disetujui', 'Ditolak'])->default('Diproses');
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