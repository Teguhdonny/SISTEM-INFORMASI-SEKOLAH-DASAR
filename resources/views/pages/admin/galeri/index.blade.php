<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri Foto - Admin SDN Pojok 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800"
      x-data="{
          sidebarOpen: true,
          openCreateModal: false,
          openEditModal: false,
          editItem: { id: '', judul: '', kategori: 'Kegiatan', tanggal: '' },
          openEdit(item) {
              this.editItem = item;
              this.openEditModal = true;
          }
      }">

    <div class="flex h-screen overflow-hidden">
        @include('components.admin.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-4 sm:px-8 z-20 shrink-0 border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-blue-900 focus:outline-none transition">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <form method="GET" action="{{ route('admin.search') }}" class="relative hidden md:block">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <i class="fas fa-search text-xs"></i>
                        </span>
                        <input type="search" name="q" placeholder="Cari menu atau informasi..." class="w-72 lg:w-80 bg-gray-50 border border-gray-200 rounded-full pl-10 pr-4 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </form>
                </div>

                <div class="flex items-center gap-3 border-l pl-5 border-gray-200">
                    <div class="w-9 h-9 bg-blue-900 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-sm">AF</div>
                    <div class="hidden sm:block">
                        <p class="text-xs font-bold text-gray-800 leading-tight">Ahmad Fauzi</p>
                        <p class="text-[10px] text-gray-500 mt-0.5">Administrator</p>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-8 space-y-6">
                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl shadow-sm flex items-center gap-3">
                        <i class="fas fa-check-circle text-emerald-600"></i>
                        <span class="text-xs font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3.5 rounded-2xl shadow-sm text-xs font-semibold">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-blue-600">Dokumentasi Sekolah</span>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 mt-0.5">Kelola Galeri Foto</h1>
                        <p class="text-xs text-gray-500 mt-1">Foto yang diunggah akan tampil pada menu Profil Sekolah &rarr; Galeri di halaman publik.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/profil') }}" target="_blank" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-external-link-alt text-blue-600"></i> Lihat Galeri Publik
                        </a>
                        <button @click="openCreateModal = true" class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i> Upload Foto Baru
                        </button>
                    </div>
                </div>

                <!-- Grid Daftar Foto Galeri -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($galeris as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                            <div class="h-44 bg-blue-50 relative overflow-hidden flex items-center justify-center">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center text-blue-400">
                                        <i class="fas fa-camera text-3xl mb-1"></i>
                                        <span class="block text-[10px] font-semibold">Klik Edit untuk Upload Foto</span>
                                    </div>
                                @endif
                                <span class="absolute top-3 right-3 px-2.5 py-1 bg-white/90 text-blue-800 rounded-full text-[10px] font-bold shadow-sm">
                                    {{ $item->kategori }}
                                </span>
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="font-bold text-sm text-gray-900">{{ $item->judul }}</h3>
                                <p class="text-[11px] text-gray-400 mt-1 mb-4">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $item->tanggal->translatedFormat('d F Y') }}
                                </p>

                                <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                    <button type="button"
                                            @click="openEdit({
                                                id: '{{ $item->id }}',
                                                judul: @js($item->judul),
                                                kategori: '{{ $item->kategori }}',
                                                tanggal: '{{ $item->tanggal->format('Y-m-d') }}'
                                            })"
                                            class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-700 font-bold py-2 px-3 rounded-xl text-xs transition flex items-center justify-center gap-1.5">
                                        <i class="fas fa-upload"></i> {{ $item->foto ? 'Edit / Ganti Foto' : 'Upload Foto' }}
                                    </button>
                                    <form action="{{ route('admin.galeri.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus foto dokumentasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-3 rounded-xl text-xs transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- MODAL TAMBAH FOTO GALERI -->
                <div x-show="openCreateModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div @click.away="openCreateModal = false" class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-8 relative">
                        <button type="button" @click="openCreateModal = false" class="absolute top-5 right-5 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">Upload Foto Galeri Baru</h3>
                        <p class="text-xs text-gray-500 mb-5">Tambahkan foto dokumentasi kegiatan, fasilitas, atau prestasi.</p>

                        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            @csrf
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Judul Dokumentasi <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" required maxlength="150" placeholder="Contoh: Kegiatan Upacara Bendera" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Kategori</label>
                                    <select name="kategori" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Kegiatan">Kegiatan</option>
                                        <option value="Fasilitas">Fasilitas</option>
                                        <option value="Prestasi">Prestasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tanggal <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Pilih File Foto (JPG/PNG, Maks 2MB) <span class="text-red-500">*</span></label>
                                <input type="file" name="foto" required accept=".jpg,.jpeg,.png,.webp" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white">
                            </div>
                            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100">
                                <button type="button" @click="openCreateModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Batal</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm">Simpan Foto</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL EDIT FOTO GALERI -->
                <div x-show="openEditModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div @click.away="openEditModal = false" class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 sm:p-8 relative">
                        <button type="button" @click="openEditModal = false" class="absolute top-5 right-5 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">Edit / Upload Foto Galeri</h3>
                        <p class="text-xs text-gray-500 mb-5">Unggah foto asli untuk menggantikan ikon placeholder di halaman publik.</p>

                        <form :action="`/admin/galeri/${editItem.id}`" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Judul Dokumentasi <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" x-model="editItem.judul" required maxlength="150" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Kategori</label>
                                    <select name="kategori" x-model="editItem.kategori" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Kegiatan">Kegiatan</option>
                                        <option value="Fasilitas">Fasilitas</option>
                                        <option value="Prestasi">Prestasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tanggal <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal" x-model="editItem.tanggal" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Upload Foto Dokumentasi (JPG/PNG)</label>
                                <input type="file" name="foto" accept=".jpg,.jpeg,.png,.webp" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white">
                            </div>
                            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100">
                                <button type="button" @click="openEditModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Batal</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>