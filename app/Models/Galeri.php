<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    // Menentukan nama jadual jika berbeza dengan nama model (plural)
    protected $table = 'galeri';

    // Lajur yang dibenarkan untuk diisi
    protected $fillable = [
        'judul',
        'kategori',
        'foto',
        'deskripsi'
    ];
}