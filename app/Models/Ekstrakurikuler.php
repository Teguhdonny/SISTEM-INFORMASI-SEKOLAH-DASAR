<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    public const ICON_OPTIONS = [
        'fa-person-running' => 'Olahraga',
        'fa-compass' => 'Pramuka',
        'fa-futbol' => 'Sepak bola',
        'fa-music' => 'Musik dan seni',
        'fa-palette' => 'Karya seni',
        'fa-flask' => 'Sains',
    ];

    public const COLOR_OPTIONS = [
        'blue' => 'Biru',
        'green' => 'Hijau',
        'red' => 'Merah',
        'purple' => 'Ungu',
        'amber' => 'Kuning',
        'rose' => 'Merah muda',
    ];

    protected $table = 'ekstrakurikuler';

    protected $fillable = ['nama_ekskul', 'kategori', 'jadwal', 'deskripsi', 'pembina', 'peserta', 'ikon', 'warna', 'status'];
}
