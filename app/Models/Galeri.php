<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    protected $table = 'galeri';

    protected $fillable = [
        'judul',
        'kategori',
        'tanggal',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];
}