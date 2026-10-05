<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SDN Pojok 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col">
        <!-- Header Halaman Login -->
        <div class="bg-blue-800 p-8 text-center">
            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                <i class="fas fa-book-open text-3xl text-blue-800"></i>
            </div>
            <h1 class="text-2xl font-bold text-white mb-1">SDN POJOK 2</h1>
            <p class="text-blue-200 text-sm">Panel Manajemen Sekolah</p>
        </div>

        <!-- Form Login -->
        <div class="p-8">
            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Masuk ke Akun Anda</h2>
            
            <!-- Tukar action ke /admin/dashboard dan method ke GET untuk simulasi sementara -->
            <form action="/admin/dashboard" method="GET" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Username / Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" placeholder="Masukkan username">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-600 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-semibold">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-red-700 transition duration-300 shadow-md">
                    Login Masuk &rarr;
                </button>
            </form>
        </div>
    </div>

</body>
</html>