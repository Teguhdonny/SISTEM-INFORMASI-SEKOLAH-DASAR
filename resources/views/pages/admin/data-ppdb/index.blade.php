<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pendaftar PPDB - Admin SDN Pojok 2</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Alpine.js untuk Interaksi Sidebar, Modal, & Notifikasi -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800" 
      x-data="{ 
          sidebarOpen: true, 
          activeModal: null, 
          unreadNotification: {{ $menungguVerifikasi > 0 ? 'true' : 'false' }},
          notificationDropdown: false 
      }">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR KIRI -->
        @include('components.admin.sidebar')

        <!-- KONTEN UTAMA SEBELAH KANAN -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Navbar Atas -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 z-20 shrink-0 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-900 focus:outline-none transition">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <!-- Kotak Carian Presisi -->
                    <div class="relative hidden sm:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="text" placeholder="Cari menu atau informasi..." class="w-80 bg-gray-50 border border-gray-200 rounded-full pl-10 pr-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <!-- Bagian Kanan Profil & Notifikasi Interaktif -->
                <div class="flex items-center gap-5">
                    
                    <!-- Dropdown Lonceng Notifikasi -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open; unreadNotification = false" class="text-gray-400 hover:text-gray-600 relative focus:outline-none p-1 transition">
                            <i class="fas fa-bell text-lg"></i>
                            <!-- Badge Merah (Hilang otomatis setelah dibuka) -->
                            <span x-show="unreadNotification && {{ $menungguVerifikasi }} > 0" 
                                  class="absolute -top-0.5 -right-0.5 bg-red-600 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold shadow-sm animate-pulse">
                                {{ $menungguVerifikasi }}
                            </span>
                        </button>

                        <!-- Kotak Popup Notifikasi -->
                        <div x-show="open" @click.away="open = false" style="display: none;" 
                             class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 py-3 z-50 text-xs">
                            <div class="px-4 pb-2 border-b font-bold text-gray-800 flex justify-between items-center">
                                <span>Notifikasi PPDB</span>
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-semibold">{{ $menungguVerifikasi }} Baru</span>
                            </div>
                            <div class="max-h-60 overflow-y-auto divide-y divide-gray-100">
                                @if($menungguVerifikasi > 0)
                                    <div class="px-4 py-3 hover:bg-gray-50 transition cursor-pointer">
                                        <p class="font-semibold text-gray-800">Pendaftaran Baru Masuk</p>
                                        <p class="text-gray-500 text-[11px] mt-0.5">Terdapat {{ $menungguVerifikasi }} siswa menunggu verifikasi dokumen.</p>
                                    </div>
                                @else
                                    <div class="px-4 py-6 text-center text-gray-400">
                                        <p>Tidak ada notifikasi baru.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Profil Admin -->
                    <div class="flex items-center gap-3 border-l pl-5 border-gray-200">
                        <div class="w-9 h-9 bg-blue-900 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-sm">
                            AF
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-xs font-bold text-gray-800 leading-tight">Ahmad Fauzi</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Administrator</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Area Isi Utama (Diberi padding dan jarak yang lebih presisi) -->
            <main class="flex-1 overflow-y-auto p-8 space-y-6">
                
                <!-- Notifikasi Sukses -->
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3.5 rounded-2xl shadow-sm flex items-center gap-3" role="alert">
                    <i class="fas fa-check-circle text-green-600 text-base"></i>
                    <span class="text-xs font-semibold">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Hero Banner PPDB Presisi -->
                <div class="relative bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 rounded-3xl text-white p-8 shadow-md overflow-hidden flex flex-col md:flex-row justify-between items-center">
                    <div class="relative z-10 max-w-xl">
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-blue-700/80 px-3 py-1 rounded-lg text-blue-200 border border-blue-600">Modul PPDB</span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold mt-3">Daftar Pendaftar</h1>
                        <p class="text-xs sm:text-sm text-blue-100 mt-2 leading-relaxed">
                            Kelola data pendaftar siswa baru SDN Pojok 2 Kota Kediri. Lakukan verifikasi dokumen dan atur status secara real-time.
                        </p>
                    </div>
                    <div class="mt-4 md:mt-0 relative z-10 hidden lg:block">
                        <div class="w-36 h-20 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 flex flex-col items-center justify-center text-center shadow-inner">
                            <span class="text-[10px] text-blue-200 uppercase font-medium">Tahun Ajaran</span>
                            <span class="text-sm font-bold text-white mt-0.5">2026 / 2027</span>
                        </div>
                    </div>
                </div>

                <!-- 4 KAD STATISTIK RINGKASAN (Presisi & Rapi) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    
                    <!-- Total Pendaftar -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Total Pendaftar</p>
                            <h3 class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalPendaftar }}</h3>
                            <span class="text-[10px] text-green-600 font-semibold mt-1 inline-block"><i class="fas fa-arrow-up mr-1"></i> Data sinkron</span>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-lg shadow-inner">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>

                    <!-- Menunggu Verifikasi -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Menunggu Verifikasi</p>
                            <h3 class="text-2xl font-extrabold text-amber-600 mt-1">{{ $menungguVerifikasi }}</h3>
                            <span class="text-[10px] text-amber-600 font-semibold mt-1 inline-block"><i class="fas fa-clock mr-1"></i> Perlu tindakan</span>
                        </div>
                        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-lg shadow-inner">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>

                    <!-- Diterima -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Diterima</p>
                            <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $diterima }}</h3>
                            <span class="text-[10px] text-emerald-600 font-semibold mt-1 inline-block"><i class="fas fa-check mr-1"></i> Terverifikasi</span>
                        </div>
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-lg shadow-inner">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>

                    <!-- Ditolak -->
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex items-center justify-between transition hover:shadow-md">
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Ditolak</p>
                            <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ $ditolak }}</h3>
                            <span class="text-[10px] text-rose-600 font-semibold mt-1 inline-block"><i class="fas fa-times mr-1"></i> Tidak valid</span>
                        </div>
                        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-lg shadow-inner">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                </div>

                <!-- KOTAK UTAMA: JADUAL PENDAFTAR -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    <!-- Header Penapis Jadual -->
                    <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                        <div class="w-full sm:w-80">
                            <input type="text" placeholder="Cari nama, NISN, atau no pendaftaran..." class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                        <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                            <select class="bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-xs font-semibold text-gray-700 outline-none transition">
                                <option>Semua Status</option>
                                <option>Menunggu Verifikasi</option>
                                <option>Diterima</option>
                                <option>Ditolak</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tabel Data Pendaftar (Presisi Tinggi) -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100/70 border-b border-gray-200 text-[11px] font-bold text-gray-500 uppercase tracking-wider">
                                    <th class="px-6 py-4">No</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4">NISN</th>
                                    <th class="px-6 py-4">Tanggal Daftar</th>
                                    <th class="px-6 py-4">Jenjang</th>
                                    <th class="px-6 py-4">Status Verifikasi</th>
                                    <th class="px-6 py-4 text-center">Aksi & Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-xs text-gray-700">
                                @forelse($pendaftars as $index => $item)
                                <tr class="hover:bg-blue-50/40 transition">
                                    <td class="px-6 py-4 font-medium text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $item->nama_siswa }}</td>
                                    <td class="px-6 py-4 text-gray-600 font-mono">{{ $item->nisn }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-lg text-[11px] font-semibold border border-blue-100">
                                            {{ $item->jenjang }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $status = $item->daftarUlang->status ?? 'Menunggu Verifikasi';
                                            $badgeColor = 'bg-amber-100 text-amber-800 border border-amber-200';
                                            if($status == 'Diterima') $badgeColor = 'bg-emerald-100 text-emerald-800 border border-emerald-200';
                                            if($status == 'Ditolak') $badgeColor = 'bg-rose-100 text-rose-800 border border-rose-200';
                                        @endphp
                                        <span class="px-3.5 py-1 inline-flex text-[11px] font-semibold rounded-full {{ $badgeColor }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Tombol Detail / Verifikasi Presisi -->
                                        <button @click="activeModal = {{ $item->id }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-xs font-semibold shadow-sm transition inline-flex items-center gap-1.5">
                                            <i class="fas fa-eye text-[10px]"></i> Detail & Verifikasi
                                        </button>
                                    </td>
                                </tr>

                                <!-- MODAL DETAIL & VERIFIKASI -->
                                <div x-show="activeModal === {{ $item->id }}" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 backdrop-blur-sm p-4 overflow-y-auto" style="display: none;">
                                    <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-8 relative">
                                        
                                        <!-- Tombol Tutup X -->
                                        <button @click="activeModal = null" class="absolute top-6 right-6 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center font-bold transition">
                                            <i class="fas fa-times"></i>
                                        </button>

                                        <h3 class="text-xl font-bold text-gray-900 mb-1">Detail Pendaftar PPDB</h3>
                                        <p class="text-xs text-gray-500 mb-6">Periksa data diri dan dokumen pendukung siswa sebelum melakukan verifikasi.</p>

                                        <!-- Informasi Siswa -->
                                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-200 space-y-3 text-xs mb-6">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div><span class="text-gray-400 block mb-0.5">No. Pendaftaran:</span> <strong class="text-gray-800 text-sm font-mono">{{ $item->no_pendaftaran }}</strong></div>
                                                <div><span class="text-gray-400 block mb-0.5">NISN:</span> <strong class="text-gray-800 text-sm font-mono">{{ $item->nisn }}</strong></div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div><span class="text-gray-400 block mb-0.5">Nama Lengkap:</span> <strong class="text-gray-800 text-sm">{{ $item->nama_siswa }}</strong></div>
                                                <div><span class="text-gray-400 block mb-0.5">Jenjang:</span> <strong class="text-gray-800 text-sm">{{ $item->jenjang }}</strong></div>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3">
                                                <div><span class="text-gray-400 block mb-0.5">Asal Sekolah:</span> <strong class="text-gray-800 text-sm">{{ $item->asal_sekolah }}</strong></div>
                                                <div><span class="text-gray-400 block mb-0.5">Nama Wali:</span> <strong class="text-gray-800 text-sm">{{ $item->daftarUlang->nama_wali ?? '-' }}</strong></div>
                                            </div>
                                            <div><span class="text-gray-400 block mb-0.5">No. WhatsApp Wali:</span> <strong class="text-gray-800 text-sm font-mono">{{ $item->daftarUlang->no_whatsapp ?? '-' }}</strong></div>
                                        </div>

                                        <!-- Dokumen yang Diunggah -->
                                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Dokumen Lampiran</h4>
                                        <div class="grid grid-cols-2 gap-4 mb-6">
                                            <div class="border border-gray-200 rounded-2xl p-4 text-center bg-gray-50">
                                                <p class="text-xs font-semibold text-gray-700 mb-2">Akta Kelahiran</p>
                                                @if(optional($item->daftarUlang)->file_akta)
                                                    <a href="{{ asset('storage/' . $item->daftarUlang->file_akta) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold bg-blue-50 px-3.5 py-2 rounded-xl inline-block border border-blue-200 shadow-sm transition">
                                                        <i class="fas fa-file-pdf mr-1 text-red-500"></i> Lihat Akta
                                                    </a>
                                                @else
                                                    <span class="text-xs text-rose-500 italic">Tidak ada file</span>
                                                @endif
                                            </div>

                                            <div class="border border-gray-200 rounded-2xl p-4 text-center bg-gray-50">
                                                <p class="text-xs font-semibold text-gray-700 mb-2">Kartu Keluarga (KK)</p>
                                                @if(optional($item->daftarUlang)->file_kk)
                                                    <a href="{{ asset('storage/' . $item->daftarUlang->file_kk) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold bg-blue-50 px-3.5 py-2 rounded-xl inline-block border border-blue-200 shadow-sm transition">
                                                        <i class="fas fa-file-pdf mr-1 text-red-500"></i> Lihat KK
                                                    </a>
                                                @else
                                                    <span class="text-xs text-rose-500 italic">Tidak ada file</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Form Ubah Status Verifikasi -->
                                        <form action="{{ url('/admin/pendaftaran/' . $item->id . '/status') }}" method="POST" class="space-y-4 pt-4 border-t border-gray-200">
                                            @csrf
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 mb-1.5">Ubah Status Verifikasi Admin</label>
                                                <select name="status" class="w-full bg-white border border-gray-300 rounded-xl p-3 text-xs font-semibold text-gray-800 focus:ring-2 focus:ring-blue-500 outline-none transition">
                                                    <option value="Menunggu Verifikasi" {{ (optional($item->daftarUlang)->status == 'Menunggu Verifikasi') ? 'selected' : '' }}>Menunggu Verifikasi</option>
                                                    <option value="Diterima" {{ (optional($item->daftarUlang)->status == 'Diterima') ? 'selected' : '' }}>Diterima</option>
                                                    <option value="Ditolak" {{ (optional($item->daftarUlang)->status == 'Ditolak') ? 'selected' : '' }}>Ditolak</option>
                                                </select>
                                            </div>

                                            <div class="flex gap-3 pt-2">
                                                <button type="button" @click="activeModal = null" class="w-1/2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl text-xs transition">
                                                    Batal
                                                </button>
                                                <button type="submit" class="w-1/2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl text-xs shadow-md transition">
                                                    Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <!-- AKHIR MODAL -->

                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                        <i class="fas fa-folder-open text-3xl mb-2"></i>
                                        <p class="text-sm">Belum ada data pendaftar masuk ke sistem.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>

            </main>

            <!-- Footer Admin -->
            <footer class="bg-white border-t border-gray-200 py-4 px-8 text-center text-xs text-gray-500">
                <p>&copy; 2026 SDN Pojok 2 Kota Kediri - Panel Administrator.</p>
            </footer>

        </div>
    </div>

</body>
</html>