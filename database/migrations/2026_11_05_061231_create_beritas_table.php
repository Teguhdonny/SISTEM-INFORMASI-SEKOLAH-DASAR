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
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('kategori', 50)->default('Pengumuman');
            $table->date('tanggal_kegiatan');
            $table->string('foto')->nullable();
            $table->string('status', 20)->default('Aktif');
            $table->text('isi');
            $table->index(['status', 'tanggal_kegiatan']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};
