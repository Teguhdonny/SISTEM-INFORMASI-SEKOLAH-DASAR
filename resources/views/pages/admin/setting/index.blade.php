<x-layouts.admin>
    <div class="p-8 space-y-6">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900">Pengaturan Sistem</h1>
            <p class="text-xs text-gray-500 mt-1">Kelola konfigurasi dan akses pendaftaran daftar ulang SDN Pojok 2.</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-2xl shadow-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-emerald-600"></i>
                <span class="text-xs font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 max-w-3xl">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Akses Pendaftaran Daftar Ulang</h3>
            <p class="text-sm text-gray-600 mb-6 pb-6 border-b border-gray-100">
                Aktifkan atau nonaktifkan akses wali murid ke formulir pendaftaran. Jika ditutup, sistem akan menampilkan pop-up penutupan.
            </p>

            <form action="{{ route('admin.setting.update-pendaftaran') }}" method="POST">
                @csrf
                <div class="flex flex-col sm:flex-row gap-6 mb-8">
                    <!-- Opsi Buka -->
                    <label class="relative flex cursor-pointer items-center rounded-2xl border {{ $isOpen ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 bg-white' }} p-5 transition-all hover:bg-gray-50 flex-1">
                        <input type="radio" name="status" value="buka" {{ $isOpen ? 'checked' : '' }} class="peer sr-only">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full {{ $isOpen ? 'bg-emerald-500 text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xl transition-all">
                                <i class="fas fa-door-open"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-gray-900 text-sm">Buka Pendaftaran</h4>
                                <p class="text-[11px] text-gray-500 mt-0.5">Formulir aktif dan bisa diakses.</p>
                            </div>
                        </div>
                    </label>

                    <!-- Opsi Tutup -->
                    <label class="relative flex cursor-pointer items-center rounded-2xl border {{ !$isOpen ? 'border-red-500 bg-red-50' : 'border-gray-200 bg-white' }} p-5 transition-all hover:bg-gray-50 flex-1">
                        <input type="radio" name="status" value="tutup" {{ !$isOpen ? 'checked' : '' }} class="peer sr-only">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full {{ !$isOpen ? 'bg-red-500 text-white' : 'bg-gray-100 text-gray-400' }} flex items-center justify-center text-xl transition-all">
                                <i class="fas fa-lock"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-gray-900 text-sm">Tutup Pendaftaran</h4>
                                <p class="text-[11px] text-gray-500 mt-0.5">Munculkan pop-up penutupan.</p>
                            </div>
                        </div>
                    </label>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm flex items-center gap-2 transition-all">
                        <i class="fas fa-save"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.admin>