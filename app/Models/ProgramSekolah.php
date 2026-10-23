<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSekolah extends Model
{
    protected $table = 'program_sekolah';

    protected $fillable = ['nama_program', 'kategori', 'jadwal', 'deskripsi', 'peserta', 'ikon', 'warna', 'status'];
}
