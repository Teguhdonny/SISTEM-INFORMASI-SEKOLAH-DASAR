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
        Schema::table('profil_sekolah', function (Blueprint $table) {
            $table->json('riwayat_sekolah')->nullable();
            $table->json('identitas_sekolah')->nullable();
            $table->json('struktur_organisasi')->nullable();
            $table->json('fasilitas_sekolah')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'riwayat_sekolah',
                'identitas_sekolah',
                'struktur_organisasi',
                'fasilitas_sekolah',
            ]);
        });
    }
};
