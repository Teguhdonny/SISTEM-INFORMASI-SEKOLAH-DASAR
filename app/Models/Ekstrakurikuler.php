<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    protected $table = 'ekstrakurikuler';

    protected $fillable = ['nama_ekskul', 'kategori', 'jadwal', 'deskripsi', 'pembina', 'peserta', 'ikon', 'warna', 'status'];
}
