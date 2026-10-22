<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SDN Pojok 2 Kota Kediri' }}</title>
    
    <!-- Memanggil Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Memanggil animasi AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Font Awesome 6.4.0 untuk Ikon Lengkap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Script Alpine.js (Ditaruh di head dengan defer agar langsung aktif saat halaman dimuat) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Mencegah efek berkedip (flicker) pada elemen x-show Alpine.js -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">
    
    <!-- Komponen Navbar Publik -->
    <x-public.navbar />

    <!-- Konten Utama akan dirender di sini -->
    <main class="flex-grow">
        {{ $slot }}
    </main>

    <!-- Komponen Footer Publik -->
    <x-public.footer />

    <!-- Script AOS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            once: true
        });
    </script>
</body>
</html>