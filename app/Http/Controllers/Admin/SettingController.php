<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Ambil baris pertama dari tabel settings
        $setting = Setting::first();
        
        // Cek nilai kolom is_registration_open (default false jika belum ada)
        $isOpen = $setting ? (bool) $setting->is_registration_open : false;

        return view('pages.admin.setting.index', compact('isOpen'));
    }

    public function updatePendaftaran(Request $request)
    {
        $request->validate([
            'status' => 'required|in:buka,tutup',
        ]);

        // Ambil baris pertama, atau buat baris baru jika tabel settings masih kosong
        $setting = Setting::first() ?? new Setting();
        $setting->is_registration_open = ($request->status === 'buka');
        $setting->save();

        return redirect()->back()->with('success', 'Status registrasi daftar ulang berhasil diperbarui!');
    }
}