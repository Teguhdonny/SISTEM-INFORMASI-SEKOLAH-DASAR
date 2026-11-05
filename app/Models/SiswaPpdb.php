<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SiswaPpdb extends Model
{
    use HasFactory;

    protected $table = 'siswa_ppdb';

    protected $fillable = [
        'no_pendaftaran',
        'nik',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'jenjang',
        'tahun_ajaran',
        'asal_sekolah',
        'status_diterima',
        'dicatat_admin',
    ];

    // Relasi One-to-One ke tabel daftar_ulang
    public function daftarUlang(): HasOne
    {
        return $this->hasOne(DaftarUlang::class, 'id_siswa_ppdb');
    }
}
