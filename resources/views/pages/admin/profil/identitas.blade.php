<section x-show="activeTab === 'identitas'" style="display: none;" class="space-y-6">
    <div>
        <p class="text-xs font-bold text-blue-600">Profil Sekolah</p>
        <h2 class="mt-1 text-xl font-extrabold text-gray-900">Identitas Sekolah</h2>
        <p class="mt-1 text-xs text-gray-500">Informasi resmi sekolah yang ditampilkan pada halaman publik.</p>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <tbody class="divide-y divide-gray-100">
                <tr><th class="w-1/3 px-5 py-4 font-bold text-blue-950">Nama Sekolah</th><td class="px-5 py-4 text-gray-700">{{ $profilSekolah->nama_sekolah }}</td></tr>
                <tr><th class="px-5 py-4 font-bold text-blue-950">Alamat</th><td class="px-5 py-4 text-gray-700">{{ $profilSekolah->alamat }}</td></tr>
                <tr><th class="px-5 py-4 font-bold text-blue-950">Email Resmi</th><td class="px-5 py-4 text-gray-700">{{ $profilSekolah->email_sekolah }}</td></tr>
                @foreach ($identitasSekolah as $item)
                    <tr><th class="px-5 py-4 font-bold text-blue-950">{{ $item['label'] }}</th><td class="px-5 py-4 text-gray-700">{{ $item['nilai'] }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
