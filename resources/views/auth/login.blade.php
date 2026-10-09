<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SDN Pojok 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#f8fafc] flex items-center justify-center min-h-screen relative overflow-hidden font-sans">

    <!-- Dekorasi Kiri Atas -->
    <div class="absolute top-8 left-10 pointer-events-none opacity-90 hidden lg:block">
        <div class="w-10 h-3 bg-red-600 rounded-full rotate-45 mb-2 shadow-sm"></div>
        <div class="w-20 h-3 bg-blue-700 rounded-full -rotate-45 -ml-5 shadow-sm"></div>
    </div>

    <!-- Dekorasi Kanan Atas -->
    <div class="absolute top-12 right-12 pointer-events-none opacity-20 hidden lg:block">
        <svg width="60" height="60" fill="none" viewBox="0 0 80 80">
            <pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle fill="#1e3a8a" cx="2" cy="2" r="2"></circle>
            </pattern>
            <rect x="0" y="0" width="100%" height="100%" fill="url(#dots)"></rect>
        </svg>
    </div>

    <!-- Dekorasi Kanan Bawah (Bentuk Elips CSS Murni - Lebih Rapi) -->
    <div class="absolute -bottom-32 -right-32 w-[35rem] h-[35rem] pointer-events-none hidden lg:block z-0">
        <!-- Bulatan Merah Belakang -->
        <div class="absolute inset-0 bg-[#dc2626] rounded-full opacity-90 blur-[1px] transform -translate-x-12 -translate-y-8"></div>
        <!-- Bulatan Biru Depan -->
        <div class="absolute inset-0 bg-[#3b82f6] rounded-full transform translate-x-12 translate-y-12"></div>
    </div>

    <!-- Main Container Card -->
    <div class="w-full max-w-[850px] bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] flex flex-col md:flex-row relative z-10 m-4 md:m-8 overflow-hidden">
        
        <!-- Panel Kiri: Branding (Biru) -->
        <div class="md:w-[45%] bg-[#1d4ed8] text-white relative flex flex-col justify-between overflow-hidden">
            
            <!-- Gambar Sekolah (Diperbaiki menggunakan path absolut /images/) -->
            <div class="absolute inset-0 z-0 bg-cover bg-center opacity-30 mix-blend-overlay" style="background-image: url('/images/sekolah.jpg');"></div>
            <!-- Gradient Overlay Gelap ke Terang -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#1e3a8a] via-[#1d4ed8]/90 to-[#2563eb]/70 z-0"></div>

            <!-- Konten Atas Panel Kiri -->
            <div class="relative z-10 p-10 mt-4">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center text-[#1d4ed8] shrink-0 shadow-lg">
                        <i class="fas fa-book-open text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight">SDN POJOK 2</h1>
                        <p class="text-xs text-blue-200 mt-0.5">Kota Kediri</p>
                    </div>
                </div>
                
                <div class="w-10 h-1 bg-[#ef4444] rounded-full mb-5"></div>
                
                <h2 class="text-xl font-bold leading-snug text-white shadow-sm">
                    Bersama Membangun<br>Generasi Berprestasi
                </h2>
            </div>
            
            <!-- Gelombang Lengkung Merah Putih di Bawah Panel Kiri -->
            <div class="relative z-10 mt-auto">
                <svg viewBox="0 0 1440 320" class="w-full h-auto text-white align-bottom">
                    <!-- Layer Merah (Belakang) -->
                    <path fill="#ef4444" d="M0,160L48,170.7C96,181,192,203,288,208C384,213,480,203,576,192C672,181,768,171,864,181.3C960,192,1056,224,1152,213.3C1248,203,1344,149,1392,122.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z" transform="translate(0, 40)"></path>
                    <!-- Layer Putih (Depan) -->
                    <path fill="#ffffff" d="M0,256L48,245.3C96,235,192,213,288,197.3C384,181,480,171,576,181.3C672,192,768,224,864,213.3C960,203,1056,149,1152,133.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
                </svg>
            </div>
        </div>

        <!-- Panel Kanan: Form Login -->
        <div class="md:w-[55%] p-10 md:p-12 bg-white flex flex-col justify-center relative z-20">
            
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-[#1e3a8a] mb-1.5">Selamat Datang</h2>
                <p class="text-gray-400 text-xs font-medium">Silakan masuk ke akun Anda untuk melanjutkan</p>
            </div>

            <form action="/admin/dashboard" method="GET" class="space-y-5">
                <!-- Username -->
                <div>
                    <label class="block text-xs font-bold text-[#1e3a8a] mb-1.5 flex items-center gap-1.5">
                        <i class="fas fa-user-circle text-gray-400"></i> Username / Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-300 text-xs"></i>
                        </div>
                        <input type="text" class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent outline-none transition text-sm shadow-sm" placeholder="Masukkan username atau email">
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-[#1e3a8a] mb-1.5 flex items-center gap-1.5">
                        <i class="fas fa-lock text-gray-400"></i> Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-300 text-xs"></i>
                        </div>
                        <input type="password" class="w-full pl-9 pr-10 py-2.5 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#3b82f6] focus:border-transparent outline-none transition text-sm shadow-sm" placeholder="Masukkan password">
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center cursor-pointer text-gray-300 hover:text-[#3b82f6] transition">
                            <i class="fas fa-eye-slash text-xs"></i>
                        </div>
                    </div>
                </div>

                <!-- Opsi (Ingat Saya & Lupa Password) -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center cursor-pointer group">
                        <input type="checkbox" class="w-3.5 h-3.5 rounded border-gray-300 text-[#3b82f6] focus:ring-[#3b82f6] transition">
                        <span class="ml-2 text-xs text-gray-500 font-medium group-hover:text-[#1d4ed8] transition">Ingat saya</span>
                    </label>
                    <a href="#" class="text-xs text-[#3b82f6] hover:text-[#1d4ed8] font-bold transition">Lupa Password?</a>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-[#3b82f6] text-white font-bold py-3 px-4 rounded-lg hover:bg-[#2563eb] hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 mt-5 text-sm shadow-md">
                    Login Masuk <i class="fas fa-arrow-right text-xs"></i>
                </button>
            </form>

            <!-- Garis Bawah (Footer) -->
            <div class="mt-10 text-center relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                    <div class="w-full border-t border-gray-100"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-3 bg-white text-[9px] uppercase tracking-wider text-gray-300 font-bold">SDN POJOK 2 Kota Kediri</span>
                </div>
            </div>
            
        </div>
    </div>

</body>
</html>