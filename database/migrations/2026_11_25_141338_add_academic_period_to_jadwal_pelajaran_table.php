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
        Schema::table('jadwal_pelajaran', function (Blueprint $table): void {
            $table->string('semester', 10)->nullable()->after('kelas');
            $table->string('tahun_ajaran', 9)->nullable()->after('semester');
            $table->index(['tahun_ajaran', 'semester', 'kelas']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table): void {
            $table->dropIndex(['tahun_ajaran', 'semester', 'kelas']);
            $table->dropColumn(['semester', 'tahun_ajaran']);
        });
    }
};
