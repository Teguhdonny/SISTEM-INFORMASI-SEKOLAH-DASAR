<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';

    protected $fillable = [
        'nip',
        'nama',
        'lulusan',
        'bagian',
        'kompetensi',
        'foto',
    ];

    public function hasCompetency(string $subject): bool
    {
        $normalizedSubject = mb_strtolower(trim($subject));

        return collect(preg_split('/\R/', (string) $this->kompetensi, -1, PREG_SPLIT_NO_EMPTY))
            ->map(fn (string $competency): string => mb_strtolower(trim($competency)))
            ->contains(fn (string $competency): bool => $competency === $normalizedSubject);
    }
}
