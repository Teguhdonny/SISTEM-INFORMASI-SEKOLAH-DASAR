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
    @php
        $adminUser = auth()->user();
        $adminName = $adminUser?->name ?: ($adminUser?->username ?: 'Admin');
    @endphp

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
                </div>

                <!-- Bagian Kanan Profil & Notifikasi Interaktif -->
                <div class="flex items-center gap-5">
                    
                    <!-- Dropdown Lonceng Notifikasi -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open; unreadNotification = false" class="text-gray-400 hover:text-gray-600 relative focus:outline-none p-1 transition">
                            <i class="fas fa-bell text-lg"></i>
                            <!-- Badge Merah (Hilang otomatis setelah dibuka) -->
                            <span x-show="unreadNotification"
                                  class="absolute -top-0.5 -right-0.5 bg-red-600 text-white rounded-full text-[10px] w-4 h-4 flex items-center justify-center font-bold shadow-sm animate-pulse">
                                <span id="registration-notification-badge">{{ $menungguVerifikasi }}</span>
                            </span>
                        </button>

                        <!-- Kotak Popup Notifikasi -->
                        <div x-show="open" @click.away="open = false" style="display: none;" 
                             class="absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-xl border border-gray-100 py-3 z-50 text-xs">
                            <div class="px-4 pb-2 border-b font-bold text-gray-800 flex justify-between items-center">
                                <span>Notifikasi PPDB</span>
                                <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-full font-semibold"><span id="registration-notification-count">{{ $menungguVerifikasi }}</span> Baru</span>
                            </div>
                            <div class="max-h-60 overflow-y-auto divide-y divide-gray-100">
                                @if($menungguVerifikasi > 0)
                                    <div class="px-4 py-3 hover:bg-gray-50 transition cursor-pointer">
                                        <p class="font-semibold text-gray-800">Pendaftaran Baru Masuk</p>
                                        <p class="text-gray-500 text-[11px] mt-0.5">Terdapat <span id="registration-notification-text-count">{{ $menungguVerifikasi }}</span> siswa menunggu verifikasi dokumen.</p>
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
                    <div class="relative border-l border-gray-200 pl-5" x-data="{ profileOpen: false }" @click.away="profileOpen = false">
                        <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true" class="flex items-center gap-3 rounded-xl p-1.5 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                <i class="fas fa-user" aria-hidden="true"></i>
                                <span class="sr-only">{{ $adminName }}</span>
                            </div>
                            <div class="hidden text-left sm:block">
                                <p class="text-xs font-bold text-gray-800 leading-tight">{{ $adminName }}</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">{{ ucfirst($adminUser?->role ?? 'Admin') }}</p>
                            </div>
                            <i class="fas fa-chevron-down ml-1 text-xs text-gray-400 transition-transform" :class="{ 'rotate-180': profileOpen }"></i>
                        </button>
                        <div x-show="profileOpen" x-transition @keydown.escape.window="profileOpen = false" class="absolute right-0 top-full z-50 mt-2 w-64 rounded-xl border border-gray-100 bg-white p-2 shadow-lg" style="display: none;">
                            <div class="border-b border-gray-100 px-3 py-2">
                                <p class="text-sm font-semibold text-gray-800">{{ $adminName }}</p>
                                <p class="text-xs text-gray-500">{{ $adminUser?->username ?? 'Akun admin' }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-lg px-3 py-2 text-left text-sm font-semibold text-red-600 transition hover:bg-red-50"><i class="fas fa-right-from-bracket w-4"></i>Keluar</button>
                            </form>
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
                @if(session('warning'))
                <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-3.5 rounded-2xl shadow-sm flex items-center gap-3" role="alert">
                    <i class="fas fa-triangle-exclamation text-amber-600 text-base"></i>
                    <span class="text-xs font-semibold">{{ session('warning') }}</span>
                </div>
                @endif
                @if(session()->has('whatsapp_notification_sent') && ! session('whatsapp_notification_sent'))
                <div class="mt-3 bg-amber-50 border border-amber-200 text-amber-800 px-5 py-3.5 rounded-2xl shadow-sm flex items-center gap-3" role="alert">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span class="text-xs font-semibold">Status tersimpan, tetapi notifikasi WhatsApp belum terkirim. Periksa koneksi perangkat Fonnte.</span>
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
                            <h3 id="registration-total-count" class="text-2xl font-extrabold text-gray-900 mt-1">{{ $totalPendaftar }}</h3>
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
                            <h3 id="registration-waiting-count" class="text-2xl font-extrabold text-amber-600 mt-1">{{ $menungguVerifikasi }}</h3>
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
                            <h3 id="registration-accepted-count" class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $diterima }}</h3>
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
                            <h3 id="registration-rejected-count" class="text-2xl font-extrabold text-rose-600 mt-1">{{ $ditolak }}</h3>
                            <span class="text-[10px] text-rose-600 font-semibold mt-1 inline-block"><i class="fas fa-times mr-1"></i> Tidak valid</span>
                        </div>
                        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-lg shadow-inner">
                            <i class="fas fa-user-times"></i>
                        </div>
                    </div>
                </div>

                <!-- KOTAK UTAMA: JADUAL PENDAFTAR -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-200 overflow-hidden">
                    
                    <!-- Header Pencarian dan Penapis Jadual -->
                    <div class="p-6 border-b border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-50/50">
                        <form id="registration-filter-form" method="GET" action="{{ route('admin.pendaftaran.index') }}" class="flex w-full flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                            <label class="relative w-full sm:w-80">
                                <span class="sr-only">Cari pendaftar</span>
                                <i class="fas fa-search pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                                <input id="registration-search" type="search" name="search" value="{{ $search }}" placeholder="Cari" aria-label="Cari data pendaftar" aria-describedby="registration-filter-status" class="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-xs outline-none transition focus:ring-2 focus:ring-blue-500">
                            </label>
                            <select id="registration-status" name="status" aria-label="Filter status verifikasi" class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 outline-none transition">
                                <option value="">Semua Status</option>
                                @foreach (['Menunggu Verifikasi', 'Diterima', 'Ditolak'] as $statusOption)
                                    <option value="{{ $statusOption }}" @selected($status === $statusOption)>{{ $statusOption }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-xl bg-blue-700 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800">Cari</button>
                        </form>
                        <span id="registration-filter-status" class="sr-only" aria-live="polite"></span>
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
                                    <th class="px-6 py-4">Jenis Kelamin</th>
                                    <th class="px-6 py-4">Status Verifikasi</th>
                                    <th class="px-6 py-4 text-center">Aksi & Verifikasi</th>
                                </tr>
                            </thead>
                            <tbody id="registration-results" class="divide-y divide-gray-200 text-xs text-gray-700">
                                @include('pages.admin.data-ppdb.rows')
                            </tbody>
                        </table>
                    </div>
                    <div id="registration-modals">
                        @include('pages.admin.data-ppdb.modals')
                    </div>

                </div>

            </main>

            <!-- Footer Admin -->
            <footer class="bg-white border-t border-gray-200 py-4 px-8 text-center text-xs text-gray-500">
                <p>&copy; 2026 SDN Pojok 2 Kota Kediri - Panel Administrator.</p>
            </footer>

        </div>
    </div>

<script>
    (() => {
        const form = document.getElementById('registration-filter-form');
        const searchInput = document.getElementById('registration-search');
        const statusSelect = document.getElementById('registration-status');
        const results = document.getElementById('registration-results');
        const modals = document.getElementById('registration-modals');
        const filterStatus = document.getElementById('registration-filter-status');
        const totalCount = document.getElementById('registration-total-count');
        const waitingCount = document.getElementById('registration-waiting-count');
        const acceptedCount = document.getElementById('registration-accepted-count');
        const rejectedCount = document.getElementById('registration-rejected-count');

        let debounceTimer;
        let activeRequest;
        let requestNumber = 0;

        if (!form || !searchInput || !statusSelect || !results || !modals || !filterStatus) {
            return;
        }

        const updateResults = async () => {
            const currentRequest = ++requestNumber;
            const url = new URL(form.action);
            const searchTerm = searchInput.value.trim();
            const selectedStatus = statusSelect.value;

            url.search = '';
            if (searchTerm !== '') {
                url.searchParams.set('search', searchTerm);
            }
            if (selectedStatus !== '') {
                url.searchParams.set('status', selectedStatus);
            }

            activeRequest?.abort();
            activeRequest = new AbortController();
            filterStatus.textContent = 'Memuat data pendaftar…';

            try {
                const response = await fetch(url, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: activeRequest.signal,
                });

                if (!response.ok) {
                    throw new Error('Filter gagal dimuat. Silakan coba lagi.');
                }

                const data = await response.json();
                if (currentRequest !== requestNumber) {
                    return;
                }

                results.innerHTML = data.rows;
                modals.innerHTML = data.modals;
                totalCount.textContent = Number(data.total).toLocaleString('id-ID');
                waitingCount.textContent = Number(data.waiting).toLocaleString('id-ID');
                acceptedCount.textContent = Number(data.accepted).toLocaleString('id-ID');
                rejectedCount.textContent = Number(data.rejected).toLocaleString('id-ID');
                filterStatus.textContent = `${data.total} pendaftar ditemukan.`;
                window.history.replaceState({}, '', url);
            } catch (error) {
                if (error.name !== 'AbortError' && currentRequest === requestNumber) {
                    filterStatus.textContent = error.message;
                }
            }
        };

        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            activeRequest?.abort();
            requestNumber++;
            debounceTimer = window.setTimeout(updateResults, 250);
        });

        statusSelect.addEventListener('change', () => {
            clearTimeout(debounceTimer);
            updateResults();
        });

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            clearTimeout(debounceTimer);
            updateResults();
        });
    })();
</script>
</body>
</html>
