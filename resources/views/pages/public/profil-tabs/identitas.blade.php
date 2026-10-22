<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
    <h2 class="text-xl font-extrabold text-blue-950 mb-6 pb-3 border-b border-gray-100">Identitas Sekolah</h2>
    @php
        $identitas = [
            'NPSN' => '20534365',
            'Nama Sekolah' => 'SD Negeri Pojok 2 Kota Kediri',
            'Status' => 'Negeri',
            'Jenjang' => 'Sekolah Dasar (SD)',
            'Alamat' => 'Jalan Mastrip Nomor 59, Kota Kediri (64115)',
            'Kelurahan' => 'Pojok',
            'Kecamatan' => 'Mojoroto',
            'Kota' => 'Kota Kediri',
            'Provinsi' => 'Jawa Timur',
            'Email Resmi' => 'pojok2mojoroto@gmail.com',
            'Akreditasi' => 'B',
        ];
    @endphp
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
            <tbody class="divide-y divide-gray-100">
                @foreach ($identitas as $label => $nilai)
                    <tr class="hover:bg-gray-50/80">
                        <td class="py-3.5 px-4 font-bold text-blue-950 w-1/3 sm:w-1/4">{{ $label }}</td>
                        <td class="py-3.5 px-2 text-gray-400 w-4">:</td>
                        <td class="py-3.5 px-4 text-gray-700 font-medium">{{ $nilai }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>