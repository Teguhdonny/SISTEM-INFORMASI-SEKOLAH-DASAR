<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('profil_sekolah', function (Blueprint $table) {
        $table->id();
        $table->text('sejarah');
        $table->text('alamat');
        $table->string('kontak', 50);
        $table->string('email_sekolah', 100);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_sekolah');
    }
};
