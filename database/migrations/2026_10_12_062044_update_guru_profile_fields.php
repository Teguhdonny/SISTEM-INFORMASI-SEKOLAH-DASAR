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
        Schema::table('guru', function (Blueprint $table) {
            $table->renameColumn('jabatan', 'bagian');
            $table->string('lulusan', 200)->nullable();
            $table->string('email', 150)->nullable();
            $table->dropColumn('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->renameColumn('bagian', 'jabatan');
            $table->dropColumn(['lulusan', 'email']);
            $table->boolean('is_published')->default(true);
        });
    }
};
