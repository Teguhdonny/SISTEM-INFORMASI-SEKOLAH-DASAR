<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = ['judul', 'kategori', 'tanggal_kegiatan', 'foto', 'status', 'isi'];

    protected function casts(): array
    {
        return [
            'tanggal_kegiatan' => 'date',
        ];
    }
}
