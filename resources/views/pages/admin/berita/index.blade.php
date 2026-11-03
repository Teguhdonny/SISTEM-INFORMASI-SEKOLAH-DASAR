<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita & Pengumuman - Admin SDN Pojok 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-800"
      x-data="{
          sidebarOpen: true,
          openCreateModal: false,
          openEditModal: false,
          editItem: { id: '', judul: '', kategori: 'Kegiatan', tanggal: '', isi: '', status: 'Aktif' },
          openEdit(item) {
              this.editItem = item;
              this.openEditModal = true;
          }
      }">

    <div class="flex h-screen overflow-hidden">
        <!-- SIDEBAR KIRI -->
        @include('components.admin.sidebar')

        <!-- KONTEN UTAMA -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar Atas -->
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

                <!-- Header & Tombol Tambah -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-blue-600">Konten Beranda</span>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-900 mt-0.5">Kelola Berita & Pengumuman</h1>
                        <p class="text-xs text-gray-500 mt-1">4 berita terbaru berstatus Aktif akan otomatis tampil di halaman Beranda publik.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" target="_blank" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-external-link-alt text-blue-600"></i> Lihat di Beranda
                        </a>
                        <button @click="openCreateModal = true" class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold shadow-sm transition inline-flex items-center gap-2">
                            <i class="fas fa-plus"></i> Tambah Berita Baru
                        </button>
                    </div>
                </div>

                <!-- Grid Daftar Berita -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($beritas as $berita)
                        @php
                            $badgeColors = [
                                'Pengumuman' => 'bg-red-100 text-red-600',
                                'Prestasi' => 'bg-blue-100 text-blue-600',
                                'Kegiatan' => 'bg-emerald-100 text-emerald-600',
                                'Informasi' => 'bg-purple-100 text-purple-600',
                            ];
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                            <div class="h-44 bg-gray-100 relative overflow-hidden flex items-center justify-center">
                                @if($berita->gambar)
                                    <img src="{{ asset('storage/' . $berita->gambar) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                                @else
                                    <div class="text-center text-gray-400">
                                        <i class="fas fa-image text-3xl mb-1"></i>
                                        <span class="block text-[10px]">Belum Ada Foto</span>
                                    </div>
                                @endif
                                <span class="absolute top-3 left-3 text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm {{ $badgeColors[$berita->kategori] ?? 'bg-blue-100 text-blue-600' }}">
                                    {{ $berita->kategori }}
                                </span>
                                <span class="absolute top-3 right-3 text-[10px] font-bold px-2 py-0.5 rounded-md {{ $berita->status === 'Aktif' ? 'bg-emerald-500 text-white' : 'bg-gray-500 text-white' }}">
                                    {{ $berita->status }}
                                </span>
                            </div>

                            <div class="p-4 flex-1 flex flex-col">
                                <p class="text-[11px] text-gray-400 mb-1.5">
                                    <i class="far fa-calendar-alt mr-1"></i> {{ $berita->tanggal->translatedFormat('d F Y') }}
                                </p>
                                <h3 class="font-bold text-sm text-gray-900 mb-2 line-clamp-2">{{ $berita->judul }}</h3>
                                <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $berita->isi }}</p>

                                <div class="mt-auto pt-3 border-t border-gray-100 flex items-center justify-between gap-2">
                                    <button type="button"
                                            @click="openEdit({
                                                id: '{{ $berita->id }}',
                                                judul: @js($berita->judul),
                                                kategori: '{{ $berita->kategori }}',
                                                tanggal: '{{ $berita->tanggal->format('Y-m-d') }}',
                                                isi: @js($berita->isi),
                                                status: '{{ $berita->status }}'
                                            })"
                                            class="flex-1 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold py-2 px-3 rounded-xl text-xs transition flex items-center justify-center gap-1.5">
                                        <i class="fas fa-edit"></i> Edit / Foto
                                    </button>
                                    <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" onsubmit="return confirm('Hapus berita ini?')">
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

                <!-- MODAL TAMBAH BERITA -->
                <div x-show="openCreateModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div @click.away="openCreateModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 relative">
                        <button type="button" @click="openCreateModal = false" class="absolute top-5 right-5 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">Tambah Berita & Pengumuman</h3>
                        <p class="text-xs text-gray-500 mb-5">Unggah foto kegiatan dan isi informasi berita sekolah.</p>

                        <form action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            @csrf
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Judul Berita / Pengumuman <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" required maxlength="200" placeholder="Contoh: Upacara HUT RI ke-81 di SDN Pojok 2" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Kategori Label</label>
                                    <select name="kategori" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Pengumuman">Pengumuman</option>
                                        <option value="Prestasi">Prestasi</option>
                                        <option value="Kegiatan">Kegiatan</option>
                                        <option value="Informasi">Informasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Upload Foto Kegiatan (JPG/PNG)</label>
                                    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Status Tampil</label>
                                    <select name="status" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Aktif">Aktif (Tampil di Beranda)</option>
                                        <option value="Draft">Draft (Sembunyikan)</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Isi Lengkap Berita / Pengumuman <span class="text-red-500">*</span></label>
                                <textarea name="isi" rows="4" required placeholder="Tuliskan detail berita atau pengumuman..." class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                            </div>
                            <div class="flex justify-end gap-2.5 pt-4 border-t border-gray-100">
                                <button type="button" @click="openCreateModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold">Batal</button>
                                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow-sm">Simpan Berita</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- MODAL EDIT BERITA -->
                <div x-show="openEditModal" style="display: none;" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
                    <div @click.away="openEditModal = false" class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 relative">
                        <button type="button" @click="openEditModal = false" class="absolute top-5 right-5 w-8 h-8 bg-gray-100 text-gray-500 hover:bg-gray-200 rounded-full flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                        <h3 class="text-lg font-extrabold text-gray-900 mb-1">Edit Berita & Upload Foto</h3>
                        <p class="text-xs text-gray-500 mb-5">Perbarui judul, foto sampul, atau isi berita.</p>

                        <form :action="`/admin/berita/${editItem.id}`" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Judul Berita / Pengumuman <span class="text-red-500">*</span></label>
                                <input type="text" name="judul" x-model="editItem.judul" required maxlength="200" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Kategori Label</label>
                                    <select name="kategori" x-model="editItem.kategori" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Pengumuman">Pengumuman</option>
                                        <option value="Prestasi">Prestasi</option>
                                        <option value="Kegiatan">Kegiatan</option>
                                        <option value="Informasi">Informasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                                    <input type="date" name="tanggal" x-model="editItem.tanggal" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Ganti Foto Kegiatan (Opsional)</label>
                                    <input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-xs file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700">
                                </div>
                                <div>
                                    <label class="font-bold text-gray-700 block mb-1">Status Tampil</label>
                                    <select name="status" x-model="editItem.status" class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 bg-white focus:ring-2 focus:ring-blue-500 outline-none">
                                        <option value="Aktif">Aktif (Tampil di Beranda)</option>
                                        <option value="Draft">Draft (Sembunyikan)</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="font-bold text-gray-700 block mb-1">Isi Lengkap Berita / Pengumuman <span class="text-red-500">*</span></label>
                                <textarea name="isi" x-model="editItem.isi" rows="4" required class="w-full border border-gray-300 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
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