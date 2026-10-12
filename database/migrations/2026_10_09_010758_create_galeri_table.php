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
        Schema::create('galeri', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori', 100); // Contoh: 'Fasilitas', 'Kegiatan Siswa'
            $table->string('foto'); // Untuk menyimpan nama fail/path gambar
            $table->text('deskripsi')->nullable(); // Boleh dibiarkan kosong
            $table->timestamps(); // Menghasilkan created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galeri');
    }
};
