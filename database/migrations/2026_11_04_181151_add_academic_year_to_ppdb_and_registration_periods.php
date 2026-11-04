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
        Schema::table('siswa_ppdb', function (Blueprint $table): void {
            $table->string('tahun_ajaran', 9)->nullable()->after('jenjang');
        });

        Schema::table('registration_periods', function (Blueprint $table): void {
            $table->string('tahun_ajaran', 9)->nullable()->after('is_open');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_periods', function (Blueprint $table): void {
            $table->dropColumn('tahun_ajaran');
        });

        Schema::table('siswa_ppdb', function (Blueprint $table): void {
            $table->dropColumn('tahun_ajaran');
        });
    }
};
