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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            
            // Toggle On/Off Manual
            $table->boolean('is_registration_open')->default(false); 
            
            // Berdasarkan Waktu Otomatis (opsional jika dibutuhkan jadwal spesifik)
            $table->dateTime('registration_start')->nullable();
            $table->dateTime('registration_end')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};