<nav x-data="{ isMenuOpen: false }" @keydown.escape.window="isMenuOpen = false" class="sticky top-0 z-50 bg-white shadow-sm">
    <div class="mx-auto w-full px-4 sm:px-6 lg:px-16 xl:px-24">
        <div class="flex items-center justify-between gap-4 py-3 sm:py-4">
            <a href="/" class="flex min-w-0 items-center gap-3 transition hover:opacity-80">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded bg-blue-600 text-white sm:h-12 sm:w-12">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="min-w-0">
                    <h1 class="text-base font-bold leading-tight text-blue-900 sm:text-lg">SDN POJOK 2</h1>
                    <p class="text-xs text-gray-500 sm:text-sm">Kota Kediri</p>
                </div>
            </a>

            <div class="hidden items-center space-x-5 text-sm font-semibold text-gray-600 xl:flex">
                <a href="/" class="{{ request()->is('/') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Beranda</a>
                <a href="/profil" class="{{ request()->is('profil') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Profil Sekolah</a>
                <a href="/visi-misi" class="{{ request()->is('visi-misi') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Visi & Misi</a>
                <a href="/daftar-guru" class="{{ request()->is('daftar-guru') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Daftar Guru</a>
                <a href="/ekstrakurikuler" class="{{ request()->is('ekstrakurikuler') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Ekstrakurikuler</a>
                <a href="/program-sekolah" class="{{ request()->is('program-sekolah') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Program Sekolah</a>
                <a href="{{ route('ppdb.public.index') }}" class="{{ request()->is('informasi-ppdb') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">PPDB</a>
                <a href="{{ route('registrasi.public.create') }}" class="{{ request()->is('registrasi-daftar-ulang') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Registrasi Daftar Ulang</a>
                <a href="{{ route('pendaftaran-pindahan.public.create') }}" class="{{ request()->is('pendaftaran-pindahan') ? 'text-red-600 border-b-2 border-red-600 pb-1' : 'hover:text-red-600 transition' }}">Murid Pindahan</a>
            </div>

            <div class="hidden shrink-0 items-center gap-4 xl:flex">
                @unless ((request()->routeIs('profil.public.index') && request()->query('tab') === 'galeri') || (request()->routeIs('home') && request()->query('preview') === 'admin'))
                    <a href="/login" class="rounded-full bg-red-600 px-5 py-2 text-sm font-bold text-white shadow transition hover:bg-red-700">
                        <i class="fas fa-user mr-1"></i> Login Admin
                    </a>
                @endunless
            </div>

            <button type="button" @click="isMenuOpen = !isMenuOpen" :aria-expanded="isMenuOpen.toString()" :aria-label="isMenuOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'" aria-controls="public-mobile-menu" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-xl text-blue-900 transition hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-600 xl:hidden">
                <i class="fas" :class="isMenuOpen ? 'fa-times' : 'fa-bars'"></i>
            </button>
        </div>

        <div id="public-mobile-menu" x-show="isMenuOpen" x-cloak x-transition.origin.top @click.outside="isMenuOpen = false" class="border-t border-gray-100 pb-4 pt-2 xl:hidden">
            <div class="grid gap-1 text-sm font-semibold text-gray-700">
                <a @click="isMenuOpen = false" href="/" class="rounded-lg px-3 py-2.5 {{ request()->is('/') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Beranda</a>
                <a @click="isMenuOpen = false" href="/profil" class="rounded-lg px-3 py-2.5 {{ request()->is('profil') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Profil Sekolah</a>
                <a @click="isMenuOpen = false" href="/visi-misi" class="rounded-lg px-3 py-2.5 {{ request()->is('visi-misi') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Visi & Misi</a>
                <a @click="isMenuOpen = false" href="/daftar-guru" class="rounded-lg px-3 py-2.5 {{ request()->is('daftar-guru') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Daftar Guru</a>
                <a @click="isMenuOpen = false" href="/ekstrakurikuler" class="rounded-lg px-3 py-2.5 {{ request()->is('ekstrakurikuler') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Ekstrakurikuler</a>
                <a @click="isMenuOpen = false" href="/program-sekolah" class="rounded-lg px-3 py-2.5 {{ request()->is('program-sekolah') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Program Sekolah</a>
                <a @click="isMenuOpen = false" href="{{ route('ppdb.public.index') }}" class="rounded-lg px-3 py-2.5 {{ request()->is('informasi-ppdb') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">PPDB</a>
                <a @click="isMenuOpen = false" href="{{ route('registrasi.public.create') }}" class="rounded-lg px-3 py-2.5 {{ request()->is('registrasi-daftar-ulang') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Registrasi Daftar Ulang</a>
                <a @click="isMenuOpen = false" href="{{ route('pendaftaran-pindahan.public.create') }}" class="rounded-lg px-3 py-2.5 {{ request()->is('pendaftaran-pindahan') ? 'bg-red-50 text-red-600' : 'hover:bg-gray-50 hover:text-red-600' }}">Murid Pindahan</a>
            </div>

            @unless ((request()->routeIs('profil.public.index') && request()->query('tab') === 'galeri') || (request()->routeIs('home') && request()->query('preview') === 'admin'))
                <a href="/login" class="mt-3 flex items-center justify-center gap-2 rounded-full bg-red-600 px-5 py-3 text-sm font-bold text-white shadow transition hover:bg-red-700">
                    <i class="fas fa-user"></i> Login Admin
                </a>
            @endunless
        </div>
    </div>
</nav>
