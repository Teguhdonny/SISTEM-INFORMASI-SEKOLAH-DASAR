<x-layouts.admin>
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <a href="{{ route('admin.guru.index') }}" class="text-sm font-semibold text-blue-700 hover:text-red-600"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Guru</a>
            <h1 class="mt-3 text-2xl font-bold text-gray-800">Tambah Guru</h1>
            <p class="mt-1 text-sm text-gray-500">Lengkapi informasi tenaga pendidik sekolah.</p>
        </div>

        <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
            @include('pages.admin.guru._form', ['guru' => null])
        </form>
    </div>
</x-layouts.admin>
