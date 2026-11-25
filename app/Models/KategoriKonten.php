<?php

namespace App\Models;

use Database\Factories\KategoriKontenFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKonten extends Model
{
    public const TYPE_PROGRAM = 'program_sekolah';

    public const TYPE_EKSTRAKURIKULER = 'ekstrakurikuler';

    /** @use HasFactory<KategoriKontenFactory> */
    use HasFactory;

    protected $table = 'kategori_konten';

    protected $fillable = ['tipe', 'nama'];
}
