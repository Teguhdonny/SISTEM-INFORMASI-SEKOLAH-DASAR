<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolah';

    // Mengizinkan semua kolom diisi (termasuk sejarah, nama_kepsek, foto_kepsek, dll.)
    protected $guarded = ['id'];
}