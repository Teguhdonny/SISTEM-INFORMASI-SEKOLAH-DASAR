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
    Schema::create('jadwal_pelajaran', function (Blueprint $table) {
        $table->id();
        $table->string('kelas', 20);
        $table->string('hari', 20);
        $table->string('mata_pelajaran', 100);
        // Ini adalah Foreign Key yang berelasi ke tabel guru
        $table->foreignId('id_guru')->constrained('guru')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
};
