<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SDN Pojok 2 Kota Kediri' }}</title>
    
    <!-- Memanggil Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Memanggil animasi AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Font Awesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

    <!-- Script Alpine.js dan AOS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>
</html>