<x-layouts.admin>
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <a href="{{ route('admin.ppdb.index') }}" class="text-sm font-semibold text-blue-700 hover:text-red-600"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Data PPDB</a>
            <h1 class="mt-3 text-2xl font-bold text-gray-800">Ubah Data Siswa PPDB</h1>
            <p class="mt-1 text-sm text-gray-500">Perbarui nama, NIK, atau NISN siswa.</p>
        </div>
        <form action="{{ route('admin.ppdb.update', $siswaPpdb) }}" method="POST" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
            @method('PUT')
            @include('pages.admin.ppdb._form', ['siswaPpdb' => $siswaPpdb])
        </form>
    </div>
</x-layouts.admin>
