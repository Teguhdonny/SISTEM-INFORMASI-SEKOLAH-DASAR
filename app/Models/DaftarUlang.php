<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaftarUlang extends Model
{
    use HasFactory;

    protected $table = 'daftar_ulang';

    protected $fillable = [
        'id_siswa_ppdb',
        'nama_wali',
        'no_whatsapp',
        'file_kk',
        'file_akta',
        'file_foto_rumah',
        'file_ktp_ortu',
        'file_surat_pernyataan',
        'file_pas_foto',
        'status',
    ];

    // Relasi balik ke siswa_ppdb
    public function siswaPpdb()
    {
        return $this->belongsTo(SiswaPpdb::class, 'id_siswa_ppdb');
    }
}