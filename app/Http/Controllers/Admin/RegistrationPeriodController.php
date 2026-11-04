<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegistrationPeriodController extends Controller
{
    public function edit(): View
    {
        $registrationPeriod = RegistrationPeriod::query()->first() ?? new RegistrationPeriod(['is_open' => false]);

        return view('pages.admin.registration-period.edit', compact('registrationPeriod'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tahun_ajaran' => ['required', 'regex:/^[0-9]{4}\/[0-9]{4}$/'],
            'opens_on' => ['required', 'date_format:Y-m-d'],
            'closes_on' => ['required', 'date_format:Y-m-d', 'after_or_equal:opens_on'],
            'is_open' => ['nullable', 'boolean'],
        ], [
            'opens_on.required' => 'Tanggal mulai wajib diisi.',
            'closes_on.required' => 'Tanggal tutup wajib diisi.',
            'closes_on.after_or_equal' => 'Tanggal tutup harus sama dengan atau setelah tanggal mulai.',
        ]);

        $opensAt = CarbonImmutable::createFromFormat('!Y-m-d', $validated['opens_on'], 'Asia/Jakarta');
        $closesAt = CarbonImmutable::createFromFormat('!Y-m-d', $validated['closes_on'], 'Asia/Jakarta')->setTime(23, 59, 59);

        $registrationPeriod = RegistrationPeriod::query()->firstOrNew(['id' => 1]);
        $registrationPeriod->fill([
            'is_open' => $request->boolean('is_open'),
            'tahun_ajaran' => $validated['tahun_ajaran'],
            'opens_at' => $opensAt->utc(),
            'closes_at' => $closesAt->utc(),
        ]);
        $registrationPeriod->save();

        SiswaPpdb::query()
            ->where('dicatat_admin', true)
            ->whereNull('tahun_ajaran')
            ->update(['tahun_ajaran' => $registrationPeriod->tahun_ajaran]);

        return redirect()->route('admin.registration-period.edit')->with('success', 'Jadwal registrasi berhasil diperbarui.');
    }
}
