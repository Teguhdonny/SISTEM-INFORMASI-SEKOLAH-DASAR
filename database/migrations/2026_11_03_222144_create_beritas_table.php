<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->enum('kategori', ['Pengumuman', 'Prestasi', 'Kegiatan', 'Informasi'])->default('Kegiatan');
            $table->date('tanggal');
            $table->text('isi');
            $table->string('gambar')->nullable();
            $table->enum('status', ['Aktif', 'Draft'])->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};