<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    public const DAFTAR_KELAS = ['Kelas 1', 'Kelas 2', 'Kelas 3', 'Kelas 4', 'Kelas 5', 'Kelas 6'];

    public const DAFTAR_HARI = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

    protected $table = 'jadwal_pelajaran';

    protected $fillable = ['kelas', 'hari', 'jam_mulai', 'jam_selesai', 'mata_pelajaran', 'id_guru'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru');
    }

    /**
     * Urutkan berdasarkan kelas, hari (Senin–Sabtu), lalu jam mulai.
     */
    public function scopeUrut(Builder $query): Builder
    {
        $urutanHari = collect(self::DAFTAR_HARI)
            ->map(fn (string $hari, int $index): string => "WHEN '{$hari}' THEN ".($index + 1))
            ->implode(' ');

        return $query->orderBy('kelas')
            ->orderByRaw("CASE hari {$urutanHari} ELSE 99 END")
            ->orderBy('jam_mulai');
    }

    /**
     * Jam mulai dalam format "H:i" (untuk input form).
     */
    public function getJamMulaiInputAttribute(): string
    {
        return substr((string) $this->jam_mulai, 0, 5);
    }

    /**
     * Jam selesai dalam format "H:i" (untuk input form).
     */
    public function getJamSelesaiInputAttribute(): string
    {
        return substr((string) $this->jam_selesai, 0, 5);
    }

    /**
     * Label jam tampil, contoh: "07.00 - 07.35".
     */
    public function getJamLabelAttribute(): string
    {
        return str_replace(':', '.', $this->jam_mulai_input).' - '.str_replace(':', '.', $this->jam_selesai_input);
    }
}
