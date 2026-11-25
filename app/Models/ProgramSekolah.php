<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSekolah extends Model
{
    public const ICON_OPTIONS = [
        'fa-book-open' => 'Buku dan literasi',
        'fa-universal-access' => 'Layanan inklusif',
        'fa-heart' => 'Kesehatan dan kebiasaan',
        'fa-users' => 'Kebersamaan',
        'fa-leaf' => 'Lingkungan',
        'fa-star' => 'Program unggulan',
    ];

    public const COLOR_OPTIONS = [
        'blue' => 'Biru',
        'green' => 'Hijau',
        'red' => 'Merah',
        'purple' => 'Ungu',
        'amber' => 'Kuning',
        'rose' => 'Merah muda',
    ];

    protected $table = 'program_sekolah';

    protected $fillable = ['nama_program', 'kategori', 'jadwal', 'deskripsi', 'peserta', 'ikon', 'warna', 'status'];
}
