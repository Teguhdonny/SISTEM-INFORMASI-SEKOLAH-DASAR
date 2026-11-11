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
        Schema::table('pendaftaran_pindahans', function (Blueprint $table): void {
            $table->string('file_rekomendasi_diterima')->nullable()->after('file_rapor');
            $table->string('file_surat_permohonan')->nullable()->after('file_rekomendasi_diterima');
            $table->string('file_rekomendasi_dinas')->nullable()->after('file_surat_pindah');
            $table->boolean('nisn_aktif_dapodik')->default(false)->after('nisn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftaran_pindahans', function (Blueprint $table): void {
            $table->dropColumn([
                'file_rekomendasi_diterima',
                'file_surat_permohonan',
                'file_rekomendasi_dinas',
                'nisn_aktif_dapodik',
            ]);
        });
    }
};
