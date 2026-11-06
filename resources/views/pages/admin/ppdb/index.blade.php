<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Manajemen PPDB</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Siswa Diterima PPDB</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola data siswa dari hasil PPDB pemerintah. NIK dan NISN hanya dapat dilihat admin.</p>
            </div>
            <a href="{{ route('admin.ppdb.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"><i class="fas fa-plus"></i>Tambah Siswa</a>
        </div>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500"><span id="ppdb-result-count">{{ $siswa->total() }}</span> siswa tercatat</p>
                <form id="ppdb-search-form" method="GET" action="{{ route('admin.ppdb.index') }}" class="flex gap-2">
                    <input id="ppdb-search" type="search" name="search" value="{{ $search }}" placeholder="Cari" aria-label="Cari data PPDB" aria-describedby="ppdb-search-status" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm sm:w-72">
                    <button type="submit" class="rounded-lg bg-gray-800 px-4 py-2 text-sm font-semibold text-white">Cari</button>
                </form>
                <span id="ppdb-search-status" class="sr-only" aria-live="polite"></span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[850px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500"><tr><th class="px-5 py-3">No.</th><th class="px-5 py-3">Nama Siswa</th><th class="px-5 py-3">Jenis Kelamin</th><th class="px-5 py-3">NIK</th><th class="px-5 py-3">NISN</th><th class="px-5 py-3">Tahun Ajaran</th><th class="px-5 py-3 text-right">Aksi</th></tr></thead>
                    <tbody id="ppdb-results" class="divide-y divide-gray-100">
                        @include('pages.admin.ppdb.rows')
                    </tbody>
                </table>
            </div>
            <div id="ppdb-pagination" class="border-t border-gray-100 p-4 @unless ($siswa->hasPages()) hidden @endunless">{{ $siswa->links() }}</div>
        </div>
    </div>

    <script>
        (() => {
            const form = document.getElementById('ppdb-search-form');
            const input = document.getElementById('ppdb-search');
            const results = document.getElementById('ppdb-results');
            const resultCount = document.getElementById('ppdb-result-count');
            const pagination = document.getElementById('ppdb-pagination');
            const status = document.getElementById('ppdb-search-status');

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
                status.textContent = 'Mencari data siswa…';

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
                        ? 'Tidak ada data siswa yang sesuai.'
                        : `${data.count} data siswa ditemukan.`;
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
