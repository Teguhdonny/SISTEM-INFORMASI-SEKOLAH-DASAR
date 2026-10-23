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
        Schema::table('profil_sekolah', function (Blueprint $table): void {
            $table->string('nama_sekolah', 150)->default('SD Negeri Pojok 2 Kota Kediri')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table): void {
            $table->dropColumn('nama_sekolah');
        });
    }
};
