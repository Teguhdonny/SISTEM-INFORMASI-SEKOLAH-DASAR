<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class RegistrationPeriod extends Model
{
    protected $fillable = [
        'is_open',
        'tahun_ajaran',
        'opens_at',
        'closes_at',
    ];

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
            'opens_at' => 'datetime',
            'closes_at' => 'datetime',
        ];
    }

    public function acceptsRegistrationsAt(Carbon $dateTime): bool
    {
        $registrationDate = $dateTime->copy()->timezone('Asia/Jakarta')->startOfDay();
        $opensOn = $this->opens_at?->copy()->timezone('Asia/Jakarta')->startOfDay();
        $closesOn = $this->closes_at?->copy()->timezone('Asia/Jakarta')->startOfDay();

        return $this->is_open
            && $opensOn !== null
            && $closesOn !== null
            && $registrationDate->greaterThanOrEqualTo($opensOn)
            && $registrationDate->lessThanOrEqualTo($closesOn);
    }
}
