<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    protected $fillable = [
        'nama_sekolah',
        'tentang_sekolah',
        'sejarah',
        'alamat',
        'kontak',
        'email_sekolah',
        'tahun_berdiri',
        'status_sekolah',
        'tujuan',
        'riwayat_sekolah',
        'identitas_sekolah',
        'struktur_organisasi',
        'fasilitas_sekolah',
    ];

    protected $casts = [
        'tahun_berdiri' => 'integer',
        'tujuan' => 'array',
        'riwayat_sekolah' => 'array',
        'identitas_sekolah' => 'array',
        'struktur_organisasi' => 'array',
        'fasilitas_sekolah' => 'array',
    ];
}
