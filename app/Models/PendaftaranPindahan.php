<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranPindahan extends Model
{
    protected $fillable = [
        'no_pendaftaran',
        'nama_siswa',
        'nisn',
        'nisn_aktif_dapodik',
        'nik',
        'jenis_kelamin',
        'asal_sekolah',
        'kelas_asal',
        'kelas_tujuan',
        'tahun_ajaran',
        'alasan_pindah',
        'nama_wali',
        'no_whatsapp',
        'file_kk',
        'file_akta',
        'file_rapor',
        'file_rekomendasi_diterima',
        'file_surat_permohonan',
        'file_surat_pindah',
        'file_rekomendasi_dinas',
        'status',
        'catatan_admin',
    ];

    protected function casts(): array
    {
        return ['nisn_aktif_dapodik' => 'boolean'];
    }
}
