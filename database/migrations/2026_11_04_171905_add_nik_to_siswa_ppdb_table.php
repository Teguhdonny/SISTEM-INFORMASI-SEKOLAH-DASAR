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
            $table->string('nik', 16)->nullable()->unique()->after('no_pendaftaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa_ppdb', function (Blueprint $table): void {
            $table->dropUnique(['nik']);
            $table->dropColumn('nik');
        });
    }
};
