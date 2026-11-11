<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $fillable = ['kelas', 'hari', 'mata_pelajaran', 'jam_mulai', 'jam_selesai', 'id_guru'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }
}
