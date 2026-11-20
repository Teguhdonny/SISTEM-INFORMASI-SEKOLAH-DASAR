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
            $table->text('tentang_sekolah')->nullable();
            $table->unsignedSmallInteger('tahun_berdiri')->nullable();
            $table->string('status_sekolah', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table): void {
            $table->dropColumn(['tentang_sekolah', 'tahun_berdiri', 'status_sekolah']);
        });
    }
};
