<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlang;
use App\Models\PendaftaranPindahan;
use App\Models\RegistrationPeriod;
use App\Models\SiswaPpdb;
use App\Services\ExcelWorkbook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentDataTransferController extends Controller
{
    public function ppdbExcel(Request $request, ExcelWorkbook $workbook): StreamedResponse
    {
        $rows = $this->ppdbQuery($request)->get()->map(fn (SiswaPpdb $student): array => [
            $student->no_pendaftaran,
            $student->nama_siswa,
            $student->jenis_kelamin,
            $student->nik,
            $student->nisn,
            $student->tahun_ajaran,
            $student->status_diterima ? 'Diterima' : 'Tidak diterima',
        ]);

        return $workbook->download('data-ppdb.xlsx', ['No. Pendaftaran', 'Nama Siswa', 'Jenis Kelamin', 'NIK', 'NISN', 'Tahun Ajaran', 'Status'], $rows);
    }

    public function ppdbPdf(Request $request): View
    {
        $rows = $this->ppdbQuery($request)->get()->map(fn (SiswaPpdb $student): array => [
            $student->no_pendaftaran, $student->nama_siswa, $student->jenis_kelamin, $student->nik,
            $student->nisn, $student->tahun_ajaran, $student->status_diterima ? 'Diterima' : 'Tidak diterima',
        ])->all();

        return $this->printView('Data PPDB', ['No. Pendaftaran', 'Nama Siswa', 'Jenis Kelamin', 'NIK', 'NISN', 'Tahun Ajaran', 'Status'], $rows);
    }

    public function ppdbTemplate(ExcelWorkbook $workbook): StreamedResponse
    {
        return $workbook->download('template-impor-ppdb.xlsx', ['Nama Siswa', 'Jenis Kelamin', 'NIK', 'NISN'], []);
    }

    public function importPpdb(Request $request, ExcelWorkbook $workbook): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx', 'extensions:xlsx', 'max:10240']]);
        $academicYear = RegistrationPeriod::query()->value('tahun_ajaran');
        if (! $academicYear) {
            return back()->with('error', 'Atur tahun ajaran aktif sebelum mengimpor data PPDB.');
        }

        try {
            $rows = $workbook->read($request->file('file'));
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $seenNiks = [];
        $seenNisns = [];
        $validatedRows = [];
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $validator = Validator::make($row, [
                'nama_siswa' => ['required', 'string', 'max:150'],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'nik' => ['required', 'digits:16', 'unique:siswa_ppdb,nik'],
                'nisn' => ['required', 'digits:10', 'unique:siswa_ppdb,nisn'],
            ]);
            if ($validator->fails()) {
                return back()->with('error', "Baris {$rowNumber}: ".$validator->errors()->first());
            }

            $data = $validator->validated();
            if (isset($seenNiks[$data['nik']]) || isset($seenNisns[$data['nisn']])) {
                return back()->with('error', "Baris {$rowNumber}: NIK atau NISN duplikat di dalam file.");
            }
            $seenNiks[$data['nik']] = true;
            $seenNisns[$data['nisn']] = true;
            $validatedRows[] = $data;
        }

        DB::transaction(function () use ($validatedRows, $academicYear): void {
            foreach ($validatedRows as $row) {
                SiswaPpdb::query()->create([
                    ...$row,
                    'no_pendaftaran' => 'PPDB-ADMIN-'.Str::upper(Str::random(8)),
                    'tahun_ajaran' => $academicYear,
                    'status_diterima' => true,
                    'dicatat_admin' => true,
                ]);
            }
        });

        return back()->with('success', count($validatedRows).' data siswa PPDB berhasil diimpor.');
    }

    public function daftarUlangExcel(Request $request, ExcelWorkbook $workbook): StreamedResponse
    {
        $rows = $this->daftarUlangQuery($request)->get()->map(fn (SiswaPpdb $student): array => [
            $student->nisn,
            $student->nama_siswa,
            $student->tahun_ajaran,
            $student->daftarUlang->nama_wali,
            $student->daftarUlang->no_whatsapp,
            $student->daftarUlang->status,
            $student->daftarUlang->file_akta ? 'Tersedia' : 'Belum ada',
            $student->daftarUlang->file_kk ? 'Tersedia' : 'Belum ada',
        ]);

        return $workbook->download('data-daftar-ulang.xlsx', ['NISN', 'Nama Siswa', 'Tahun Ajaran', 'Nama Wali', 'No WhatsApp', 'Status', 'Akta', 'Kartu Keluarga'], $rows);
    }

    public function daftarUlangPdf(Request $request): View
    {
        $rows = $this->daftarUlangQuery($request)->get()->map(fn (SiswaPpdb $student): array => [
            $student->nisn, $student->nama_siswa, $student->tahun_ajaran,
            $student->daftarUlang->nama_wali, $student->daftarUlang->no_whatsapp,
            $student->daftarUlang->status,
            $student->daftarUlang->file_akta ? 'Ada' : 'Belum ada',
            $student->daftarUlang->file_kk ? 'Ada' : 'Belum ada',
        ])->all();

        return $this->printView('Data Daftar Ulang', ['NISN', 'Nama Siswa', 'Tahun Ajaran', 'Nama Wali', 'No WhatsApp', 'Status', 'Akta', 'Kartu Keluarga'], $rows);
    }

    public function daftarUlangTemplate(ExcelWorkbook $workbook): StreamedResponse
    {
        return $workbook->download('template-impor-daftar-ulang.xlsx', ['NISN', 'Nama Wali', 'No WhatsApp'], []);
    }

    public function importDaftarUlang(Request $request, ExcelWorkbook $workbook): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx', 'extensions:xlsx', 'max:10240']]);
        try {
            $rows = $workbook->read($request->file('file'));
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $updates = [];
        $seenNisns = [];
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $validator = Validator::make($row, [
                'nisn' => ['required', 'digits:10', 'exists:siswa_ppdb,nisn'],
                'nama_wali' => ['required', 'string', 'max:100'],
                'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            ]);
            if ($validator->fails()) {
                return back()->with('error', "Baris {$rowNumber}: ".$validator->errors()->first());
            }
            $data = $validator->validated();
            $student = SiswaPpdb::query()
                ->where('nisn', $data['nisn'])
                ->where('dicatat_admin', true)
                ->whereHas('daftarUlang')
                ->first();
            if (! $student) {
                return back()->with('error', "Baris {$rowNumber}: NISN tidak ditemukan pada data daftar ulang yang sudah ada.");
            }
            if (isset($seenNisns[$data['nisn']])) {
                return back()->with('error', "Baris {$rowNumber}: NISN duplikat di dalam file.");
            }
            $seenNisns[$data['nisn']] = true;
            $updates[$student->daftarUlang->id] = [
                'id' => $student->daftarUlang->id,
                'nama_wali' => $data['nama_wali'],
                'no_whatsapp' => $data['no_whatsapp'],
            ];
        }

        DB::transaction(function () use ($updates): void {
            foreach ($updates as $update) {
                DaftarUlang::query()->whereKey($update['id'])->update([
                    'nama_wali' => $update['nama_wali'],
                    'no_whatsapp' => $update['no_whatsapp'],
                ]);
            }
        });

        return back()->with('success', count($updates).' data daftar ulang berhasil diperbarui. Status dan dokumen tidak diubah.');
    }

    public function pindahanExcel(Request $request, ExcelWorkbook $workbook): StreamedResponse
    {
        $rows = $this->pindahanQuery($request)->get()->map(fn (PendaftaranPindahan $record): array => [
            $record->no_pendaftaran, $record->nama_siswa, $record->nisn, $record->nik,
            $record->jenis_kelamin, $record->asal_sekolah, $record->kelas_asal,
            $record->kelas_tujuan, $record->tahun_ajaran, $record->alasan_pindah,
            $record->nama_wali, $record->no_whatsapp, $record->status,
        ]);

        return $workbook->download('data-murid-pindahan.xlsx', ['No. Pendaftaran', 'Nama Siswa', 'NISN', 'NIK', 'Jenis Kelamin', 'Asal Sekolah', 'Kelas Asal', 'Kelas Tujuan', 'Tahun Ajaran', 'Alasan Pindah', 'Nama Wali', 'No WhatsApp', 'Status'], $rows);
    }

    public function pindahanPdf(Request $request): View
    {
        $rows = $this->pindahanQuery($request)->get()->map(fn (PendaftaranPindahan $record): array => [
            $record->no_pendaftaran, $record->nama_siswa, $record->nisn, $record->nik,
            $record->jenis_kelamin, $record->asal_sekolah, $record->kelas_asal,
            $record->kelas_tujuan, $record->tahun_ajaran, $record->alasan_pindah,
            $record->nama_wali, $record->no_whatsapp, $record->status,
        ])->all();

        return $this->printView('Data Murid Pindahan', ['No. Pendaftaran', 'Nama Siswa', 'NISN', 'NIK', 'Jenis Kelamin', 'Asal Sekolah', 'Kelas Asal', 'Kelas Tujuan', 'Tahun Ajaran', 'Alasan Pindah', 'Nama Wali', 'No WhatsApp', 'Status'], $rows);
    }

    public function pindahanTemplate(ExcelWorkbook $workbook): StreamedResponse
    {
        return $workbook->download('template-impor-murid-pindahan.xlsx', ['No. Pendaftaran', 'Nama Siswa', 'NISN', 'NIK', 'Jenis Kelamin', 'Asal Sekolah', 'Kelas Asal', 'Kelas Tujuan', 'Tahun Ajaran', 'Alasan Pindah', 'Nama Wali', 'No WhatsApp'], []);
    }

    public function importPindahan(Request $request, ExcelWorkbook $workbook): RedirectResponse
    {
        $request->validate(['file' => ['required', 'file', 'mimes:xlsx', 'extensions:xlsx', 'max:10240']]);
        try {
            $rows = $workbook->read($request->file('file'));
        } catch (InvalidArgumentException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $updates = [];
        $seenRegistrationNumbers = [];
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2;
            $validator = Validator::make($row, [
                'no_pendaftaran' => ['required', 'string', 'max:40', 'exists:pendaftaran_pindahans,no_pendaftaran'],
                'nama_siswa' => ['required', 'string', 'max:150'],
                'nisn' => ['required', 'digits:10'],
                'nik' => ['required', 'digits:16'],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'asal_sekolah' => ['required', 'string', 'max:150'],
                'kelas_asal' => ['required', 'string', 'max:50'],
                'kelas_tujuan' => ['required', 'string', 'max:50'],
                'tahun_ajaran' => ['required', 'string', 'max:9'],
                'alasan_pindah' => ['required', 'string', 'max:5000'],
                'nama_wali' => ['required', 'string', 'max:100'],
                'no_whatsapp' => ['required', 'regex:/^[0-9]{8,20}$/'],
            ]);
            if ($validator->fails()) {
                return back()->with('error', "Baris {$rowNumber}: ".$validator->errors()->first());
            }
            $data = $validator->validated();
            if (isset($seenRegistrationNumbers[$data['no_pendaftaran']])) {
                return back()->with('error', "Baris {$rowNumber}: nomor pendaftaran duplikat di dalam file.");
            }
            $seenRegistrationNumbers[$data['no_pendaftaran']] = true;
            $updates[] = $data;
        }

        DB::transaction(function () use ($updates): void {
            foreach ($updates as $update) {
                PendaftaranPindahan::query()->where('no_pendaftaran', $update['no_pendaftaran'])->update(collect($update)->except('no_pendaftaran')->all());
            }
        });

        return back()->with('success', count($updates).' data murid pindahan berhasil diperbarui. Status dan dokumen tidak diubah.');
    }

    private function ppdbQuery(Request $request): Builder
    {
        $search = $request->string('search')->trim()->toString();

        return SiswaPpdb::query()->where('dicatat_admin', true)
            ->when($search !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($search): void {
                $query->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('tahun_ajaran', 'like', "%{$search}%");
            }))->latest();
    }

    private function daftarUlangQuery(Request $request): Builder
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();

        return SiswaPpdb::query()->where('dicatat_admin', true)->whereHas('daftarUlang')->with('daftarUlang')
            ->when($search !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($search): void {
                $query->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%");
            }))
            ->when(in_array($status, ['Menunggu Verifikasi', 'Diterima', 'Ditolak'], true), fn (Builder $query) => $query->whereHas('daftarUlang', function (Builder $query) use ($status): void {
                $query->where('status', $status);
                if ($status === 'Menunggu Verifikasi') {
                    $query->orWhereNull('status');
                }
            }))->latest();
    }

    private function pindahanQuery(Request $request): Builder
    {
        $search = $request->string('search')->trim()->toString();
        $status = $request->string('status')->toString();
        $statuses = ['Menunggu Verifikasi', 'Perlu Perbaikan', 'Diterima', 'Ditolak', 'Selesai Daftar'];

        return PendaftaranPindahan::query()
            ->when($search !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($search): void {
                $query->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('asal_sekolah', 'like', "%{$search}%");
            }))
            ->when(in_array($status, $statuses, true), fn (Builder $query) => $query->where('status', $status))
            ->latest('id');
    }

    /** @param array<int, string> $columns
     * @param  array<int, array<int, scalar|null>>  $rows
     */
    private function printView(string $title, array $columns, array $rows): View
    {
        return view('pages.admin.exports.print', [
            'title' => $title,
            'columns' => $columns,
            'rows' => $rows,
            'printedAt' => now('Asia/Jakarta')->format('d/m/Y H:i').' WIB',
        ]);
    }
}
