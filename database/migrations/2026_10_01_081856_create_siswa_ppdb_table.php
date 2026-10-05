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
    Schema::create('siswa_ppdb', function (Blueprint $table) {
        $table->id();
        $table->string('no_pendaftaran', 50)->unique();
        $table->string('nama_siswa', 150);
        $table->string('asal_sekolah', 100);
        $table->boolean('status_diterima')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_ppdb');
    }
};
