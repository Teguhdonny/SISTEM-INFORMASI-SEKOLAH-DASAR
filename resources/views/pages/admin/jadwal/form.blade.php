@php
    $guruOptions = $guru->map(function ($item): array {
        return [
            'id' => $item->id,
            'nama' => $item->nama,
            'kompetensi' => collect(preg_split('/\R/', (string) $item->kompetensi, -1, PREG_SPLIT_NO_EMPTY))
                ->map(fn (string $kompetensi): string => mb_strtolower(trim($kompetensi)))
                ->values()
                ->all(),
        ];
    })->values();
    $selectedSubject = old('mata_pelajaran', $jadwalPelajaran->mata_pelajaran);
    $selectedTeacher = (string) old('id_guru', $jadwalPelajaran->id_guru ?? '');
    $selectedDate = old('tanggal', $jadwalPelajaran->tanggal?->format('Y-m-d'));
@endphp

<x-layouts.admin>
    <div class="mx-auto max-w-4xl space-y-6">
        <div>
            <a href="{{ route('admin.jadwal.index') }}" class="text-sm font-semibold text-blue-700 hover:text-red-600"><i class="fas fa-arrow-left mr-2"></i>Kembali ke Jadwal Pelajaran</a>
            <h1 class="mt-3 text-2xl font-bold text-gray-800">{{ $jadwalPelajaran->exists ? 'Ubah Jadwal Pelajaran' : 'Tambah Jadwal Pelajaran' }}</h1>
            <p class="mt-1 text-sm text-gray-500">Isi hari, tanggal, waktu, mata pelajaran, dan guru sesuai kompetensinya.</p>
        </div>

        <form x-data="scheduleForm(@js($guruOptions), @js($selectedSubject), @js($selectedTeacher), @js(old('hari', $jadwalPelajaran->hari)))" action="{{ $jadwalPelajaran->exists ? route('admin.jadwal.update', $jadwalPelajaran) : route('admin.jadwal.store') }}" method="POST" class="space-y-5 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm md:p-8">
            @csrf
            @if ($jadwalPelajaran->exists) @method('PUT') @endif
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="kelas" class="mb-2 block text-sm font-semibold text-gray-700">Kelas *</label>
                    <select id="kelas" name="kelas" required class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm">
                        @foreach ($kelas as $namaKelas)<option value="{{ $namaKelas }}" @selected(old('kelas', $jadwalPelajaran->kelas) === $namaKelas)>{{ $namaKelas }}</option>@endforeach
                    </select>
                    @error('kelas')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="tanggal" class="mb-2 block text-sm font-semibold text-gray-700">Tanggal *</label>
                    <input id="tanggal" name="tanggal" type="date" value="{{ $selectedDate }}" required @change="syncDayFromDate" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                    @error('tanggal')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="hari" class="mb-2 block text-sm font-semibold text-gray-700">Hari *</label>
                    <select id="hari" name="hari" required x-model="hari" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm">
                        @foreach ($hari as $namaHari)<option value="{{ $namaHari }}">{{ $namaHari }}</option>@endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Hari akan mengikuti tanggal saat tanggal diubah.</p>
                    @error('hari')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div x-data="clockPicker('{{ old('jam_mulai', $jadwalPelajaran->jam_mulai ? substr($jadwalPelajaran->jam_mulai, 0, 5) : '07:00') }}')">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Jam mulai *</label>
                    <input type="hidden" name="jam_mulai" x-model="value">
                    <button type="button" @click="openPicker" class="flex w-full items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-3 text-left text-sm text-gray-700 transition hover:border-blue-400 hover:ring-2 hover:ring-blue-100"><span x-text="displayValue"></span><i class="far fa-clock text-lg text-blue-700"></i></button>
                    @error('jam_mulai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    @include('pages.admin.jadwal.clock-picker')
                </div>
                <div x-data="clockPicker('{{ old('jam_selesai', $jadwalPelajaran->jam_selesai ? substr($jadwalPelajaran->jam_selesai, 0, 5) : '08:00') }}')">
                    <label class="mb-2 block text-sm font-semibold text-gray-700">Jam selesai *</label>
                    <input type="hidden" name="jam_selesai" x-model="value">
                    <button type="button" @click="openPicker" class="flex w-full items-center justify-between rounded-xl border border-gray-200 bg-white px-4 py-3 text-left text-sm text-gray-700 transition hover:border-blue-400 hover:ring-2 hover:ring-blue-100"><span x-text="displayValue"></span><i class="far fa-clock text-lg text-blue-700"></i></button>
                    @error('jam_selesai')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    @include('pages.admin.jadwal.clock-picker')
                </div>
                <div>
                    <label for="mata_pelajaran" class="mb-2 block text-sm font-semibold text-gray-700">Mata pelajaran *</label>
                    <select id="mata_pelajaran" name="mata_pelajaran" required x-model="subject" @change="syncTeacher" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm">
                        <option value="">Pilih mata pelajaran</option>
                        @if ($selectedSubject && ! in_array($selectedSubject, $mataPelajaran, true))<option value="{{ $selectedSubject }}">{{ $selectedSubject }}</option>@endif
                        @foreach ($mataPelajaran as $subject)<option value="{{ $subject }}">{{ $subject }}</option>@endforeach
                    </select>
                    @error('mata_pelajaran')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="id_guru" class="mb-2 block text-sm font-semibold text-gray-700">Guru pengajar *</label>
                    <select id="id_guru" name="id_guru" required x-model="teacherId" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm">
                        <option value="">Pilih guru sesuai kompetensi</option>
                        <template x-for="teacher in compatibleTeachers" :key="teacher.id">
                            <option :value="String(teacher.id)" x-text="teacher.nama"></option>
                        </template>
                    </select>
                    <p x-show="subject && compatibleTeachers.length === 0" class="mt-1 text-xs text-amber-600">Belum ada guru dengan kompetensi mata pelajaran ini.</p>
                    @error('id_guru')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="flex justify-end gap-3 border-t border-gray-100 pt-5"><a href="{{ route('admin.jadwal.index') }}" class="rounded-xl border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-600">Batal</a><button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-800"><i class="fas fa-save mr-2"></i>Simpan</button></div>
        </form>
    </div>
    <script>
        function scheduleForm(guru, subject, teacherId, hari) {
            return {
                guru,
                subject,
                teacherId,
                hari,
                get compatibleTeachers() {
                    const normalizedSubject = (this.subject || '').trim().toLowerCase();

                    return normalizedSubject === ''
                        ? []
                        : this.guru.filter((teacher) => teacher.kompetensi.includes(normalizedSubject));
                },
                syncTeacher() {
                    if (!this.compatibleTeachers.some((teacher) => String(teacher.id) === String(this.teacherId))) {
                        this.teacherId = '';
                    }
                },
                syncDayFromDate(event) {
                    const date = event.target.value;
                    if (!date) {
                        return;
                    }

                    const dayNames = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    this.hari = dayNames[new Date(`${date}T00:00:00`).getDay()];
                },
            };
        }

        function clockPicker(initialValue) {
            const [hour, minute] = (initialValue || '07:00').split(':').map(Number);

            return {
                value: initialValue || '07:00',
                hour24: Number.isNaN(hour) ? 7 : hour,
                minute: Number.isNaN(minute) ? 0 : minute,
                open: false,
                mode: 'hour',
                hours: Array.from({ length: 12 }, (_, index) => index + 1),
                minutes: Array.from({ length: 12 }, (_, index) => index * 5),
                get hour12() { return this.hour24 % 12 || 12; },
                get period() { return this.hour24 >= 12 ? 'PM' : 'AM'; },
                get displayValue() { return `${String(this.hour12).padStart(2, '0')}:${String(this.minute).padStart(2, '0')} ${this.period}`; },
                openPicker() { this.mode = 'hour'; this.open = true; },
                selectHour(hour12) { this.hour24 = this.period === 'PM' ? (hour12 === 12 ? 12 : hour12 + 12) : (hour12 === 12 ? 0 : hour12); this.mode = 'minute'; },
                selectMinute(minute) { this.minute = minute; this.value = `${String(this.hour24).padStart(2, '0')}:${String(this.minute).padStart(2, '0')}`; this.open = false; },
                setPeriod(period) { if (period === 'PM' && this.hour24 < 12) this.hour24 += 12; if (period === 'AM' && this.hour24 >= 12) this.hour24 -= 12; this.value = `${String(this.hour24).padStart(2, '0')}:${String(this.minute).padStart(2, '0')}`; },
                clockPosition(index) { const angle = index * 30; return `left: 50%; top: 50%; transform: rotate(${angle}deg) translateY(-82px) rotate(-${angle}deg);`; },
            };
        }
    </script>
</x-layouts.admin>
