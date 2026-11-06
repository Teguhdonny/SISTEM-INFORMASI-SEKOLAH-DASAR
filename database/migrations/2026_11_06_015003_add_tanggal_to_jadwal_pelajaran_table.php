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
        if (! Schema::hasColumn('jadwal_pelajaran', 'tanggal')) {
            Schema::table('jadwal_pelajaran', function (Blueprint $table): void {
                $table->date('tanggal')->nullable()->after('hari');
            });
        }

        if (! Schema::hasIndex('jadwal_pelajaran', 'jadwal_schedule_overlap_index')) {
            Schema::table('jadwal_pelajaran', function (Blueprint $table): void {
                $table->index(['hari', 'tanggal', 'id_guru', 'jam_mulai', 'jam_selesai'], 'jadwal_schedule_overlap_index');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_pelajaran', function (Blueprint $table): void {
            $table->dropIndex('jadwal_schedule_overlap_index');
            $table->dropColumn('tanggal');
        });
    }
};
