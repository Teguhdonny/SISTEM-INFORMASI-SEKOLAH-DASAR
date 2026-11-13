<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategori_konten', function (Blueprint $table): void {
            $table->id();
            $table->string('tipe', 30);
            $table->string('nama', 50);
            $table->unique(['tipe', 'nama']);
            $table->timestamps();
        });

        foreach ([
            'program_sekolah' => 'program_sekolah',
            'ekstrakurikuler' => 'ekstrakurikuler',
        ] as $type => $tableName) {
            $categories = DB::table($tableName)
                ->whereNotNull('kategori')
                ->distinct()
                ->pluck('kategori');

            $seenNames = [];
            foreach ($categories as $categoryName) {
                $name = trim((string) $categoryName);
                $normalizedName = mb_strtolower($name);

                if ($name === '' || isset($seenNames[$normalizedName])) {
                    continue;
                }

                $seenNames[$normalizedName] = true;
                DB::table('kategori_konten')->insert([
                    'tipe' => $type,
                    'nama' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_konten');
    }
};
