<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('profil_sekolah', 'nama_kepsek')) {
                $table->string('nama_kepsek', 150)->nullable()->default('H. Ahmad Fauzi, S.Pd');
            }
            if (!Schema::hasColumn('profil_sekolah', 'sambutan_kepsek')) {
                $table->text('sambutan_kepsek')->nullable();
            }
            if (!Schema::hasColumn('profil_sekolah', 'foto_kepsek')) {
                $table->string('foto_kepsek')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table) {
            $table->dropColumn(['nama_kepsek', 'sambutan_kepsek', 'foto_kepsek']);
        });
    }
};