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
            $table->string('no_whatsapp_admin', 20)->nullable()->after('kontak');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_sekolah', function (Blueprint $table): void {
            $table->dropColumn('no_whatsapp_admin');
        });
    }
};
