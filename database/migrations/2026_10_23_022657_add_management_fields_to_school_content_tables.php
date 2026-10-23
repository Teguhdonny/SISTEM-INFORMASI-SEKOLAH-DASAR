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
        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->string('pembina', 150)->nullable();
            $table->unsignedInteger('peserta')->default(0);
            $table->string('ikon', 60)->default('fa-person-running');
            $table->string('warna', 30)->default('blue');
            $table->string('status', 20)->default('Aktif');
        });

        Schema::table('program_sekolah', function (Blueprint $table) {
            $table->string('kategori', 50)->default('Umum');
            $table->string('jadwal', 100)->default('');
            $table->unsignedInteger('peserta')->default(0);
            $table->string('ikon', 60)->default('fa-book-open');
            $table->string('warna', 30)->default('blue');
            $table->string('status', 20)->default('Aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_sekolah', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'jadwal', 'peserta', 'ikon', 'warna', 'status']);
        });

        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->dropColumn(['pembina', 'peserta', 'ikon', 'warna', 'status']);
        });
    }
};
