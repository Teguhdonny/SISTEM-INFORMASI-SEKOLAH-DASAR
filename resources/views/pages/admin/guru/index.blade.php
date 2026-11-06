<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold text-blue-700">Konten Website</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Daftar Guru & Staff</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola profil tenaga pendidik yang tampil di website sekolah.</p>
            </div>
            <a href="{{ route('admin.guru.create') }}" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-red-700">
                <i class="fas fa-plus mr-2"></i>Tambah Guru
            </a>
        </div>

        @if (session('success'))
            <div class="flex items-center gap-3 rounded-xl border border-green-100 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">
                <i class="fas fa-check-circle"></i>{{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">Total Data Guru</p>
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-700"><i class="fas fa-users"></i></span>
                </div>
                <p class="mt-3 text-3xl font-black text-gray-800">{{ $totalGuru }}</p>
                <p class="mt-1 text-xs text-gray-500">Tenaga pendidik terdaftar</p>
            </div>
        </div>

        <section class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-gray-100 p-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="flex items-center gap-2 font-bold text-gray-800"><span class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-blue-700"><i class="fas fa-user-group"></i></span>Semua Guru</h2>
                    <p class="mt-1 text-xs text-gray-500"><span id="guru-result-count">{{ $guru->total() }}</span> data tenaga pendidik</p>
                </div>
                <form id="guru-search-form" method="GET" action="{{ route('admin.guru.index') }}" class="flex gap-2">
                    <input id="guru-search" type="search" name="search" value="{{ $search }}" placeholder="Cari" aria-label="Cari data guru" aria-describedby="guru-search-status" class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none sm:w-72">
                    <button type="submit" class="rounded-xl bg-blue-700 px-4 text-white transition hover:bg-blue-800" aria-label="Cari"><i class="fas fa-search"></i></button>
                </form>
                <span id="guru-search-status" class="sr-only" aria-live="polite"></span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                        <tr><th class="px-5 py-4 font-semibold">Nama</th><th class="px-5 py-4 font-semibold">NIP</th><th class="px-5 py-4 font-semibold">Status & Tugas</th><th class="px-5 py-4 font-semibold">Jenjang Pendidikan</th><th class="px-5 py-4 text-right font-semibold">Aksi</th></tr>
                    </thead>
                    <tbody id="guru-results" class="divide-y divide-gray-100">
                        @include('pages.admin.guru.rows')
                    </tbody>
                </table>
            </div>
            <div id="guru-pagination" class="border-t border-gray-100 px-5 py-4 @unless ($guru->hasPages()) hidden @endunless">{{ $guru->links() }}</div>
        </section>
    </div>

    <script>
        (() => {
            const form = document.getElementById('guru-search-form');
            const input = document.getElementById('guru-search');
            const results = document.getElementById('guru-results');
            const resultCount = document.getElementById('guru-result-count');
            const pagination = document.getElementById('guru-pagination');
            const status = document.getElementById('guru-search-status');

            let debounceTimer;
            let activeRequest;
            let requestNumber = 0;

            if (!form || !input || !results || !resultCount || !pagination || !status) {
                return;
            }

            const updateResults = async () => {
                const currentRequest = ++requestNumber;
                const searchTerm = input.value.trim();
                const url = new URL(form.action);

                url.search = '';
                if (searchTerm !== '') {
                    url.searchParams.set('search', searchTerm);
                }

                activeRequest?.abort();
                activeRequest = new AbortController();
                status.textContent = 'Mencari data guru…';

                try {
                    const response = await fetch(url, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: activeRequest.signal,
                    });

                    if (!response.ok) {
                        throw new Error('Pencarian gagal. Silakan coba lagi.');
                    }

                    const data = await response.json();
                    if (currentRequest !== requestNumber) {
                        return;
                    }

                    results.innerHTML = data.rows;
                    resultCount.textContent = Number(data.count).toLocaleString('id-ID');
                    pagination.innerHTML = data.pagination;
                    pagination.classList.toggle('hidden', data.pagination === '');
                    status.textContent = data.count === 0
                        ? 'Tidak ada data guru yang sesuai.'
                        : `${data.count} data guru ditemukan.`;
                    window.history.replaceState({}, '', url);
                } catch (error) {
                    if (error.name !== 'AbortError' && currentRequest === requestNumber) {
                        status.textContent = error.message;
                    }
                }
            };

            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                activeRequest?.abort();
                requestNumber++;
                debounceTimer = window.setTimeout(updateResults, 250);
            });

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                clearTimeout(debounceTimer);
                updateResults();
            });
        })();
    </script>
</x-layouts.admin>
