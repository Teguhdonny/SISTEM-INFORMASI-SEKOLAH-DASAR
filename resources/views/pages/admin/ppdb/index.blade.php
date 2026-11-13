<x-layouts.admin>
    <div class="space-y-6">
        <div class="flex flex-col justify-between gap-4 xl:flex-row xl:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Manajemen PPDB</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-800">Siswa Diterima PPDB</h1>
                <p class="mt-1 text-sm text-gray-500">Kelola data siswa dari hasil PPDB pemerintah. NIK dan NISN hanya dapat dilihat admin.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a id="ppdb-export-pdf" href="{{ route('admin.ppdb.export.pdf', request()->only('search')) }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm font-bold text-gray-700 transition hover:bg-gray-50"><i class="fas fa-file-pdf text-red-600"></i>PDF</a>
                <a id="ppdb-export-excel" href="{{ route('admin.ppdb.export.xlsx', request()->only('search')) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-bold text-green-800 transition hover:bg-green-100"><i class="fas fa-file-excel"></i>Excel</a>
                <a href="{{ route('admin.ppdb.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-800"><i class="fas fa-plus"></i>Tambah Siswa</a>
            </div>
        </div>

        @if (session('success'))
            <div role="status" class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.ppdb.import.xlsx') }}" enctype="multipart/form-data" class="flex flex-col gap-3 rounded-2xl border border-blue-100 bg-blue-50/60 p-4 sm:flex-row sm:items-end sm:p-5">
            @csrf
            <div class="min-w-0 flex-1">
                <label for="ppdb-import-file" class="mb-1 block text-sm font-bold text-gray-800">Impor data siswa dari Excel</label>
                <p class="mb-2 text-xs text-gray-600">Impor menambahkan siswa untuk tahun ajaran aktif, maksimal 500 baris. Isi NIK/NISN sebagai teks agar nol awal dan digitnya tidak berubah.</p>
                <input id="ppdb-import-file" name="file" type="file" accept=".xlsx" required class="block w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-white file:px-3 file:py-2 file:text-xs file:font-semibold file:text-blue-700">
            </div>
            <div class="flex shrink-0 gap-2">
                <a href="{{ route('admin.ppdb.template.xlsx') }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-xl border border-gray-200 bg-white px-4 py-3 text-xs font-bold text-gray-700 hover:bg-gray-50">Unduh template</a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-xl bg-blue-700 px-4 py-3 text-xs font-bold text-white hover:bg-blue-800"><i class="fas fa-upload"></i>Impor Excel</button>
            </div>
        </form>

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
            const exportLinks = [document.getElementById('ppdb-export-pdf'), document.getElementById('ppdb-export-excel')];

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
                exportLinks.forEach((link) => {
                    if (link) {
                        const exportUrl = new URL(link.href);
                        exportUrl.search = url.search;
                        link.href = exportUrl;
                    }
                });

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
