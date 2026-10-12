<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiswaPpdb extends Model
{
    use HasFactory;

    protected $table = 'siswa_ppdb';

    protected $fillable = [
        'no_pendaftaran',
        'nisn',
        'nama_siswa',
        'jenjang',
        'asal_sekolah',
        'status_diterima',
    ];

    // Relasi One-to-One ke tabel daftar_ulang
    public function daftarUlang()
    {
        return $this->hasOne(DaftarUlang::class, 'id_siswa_ppdb');
    }
}