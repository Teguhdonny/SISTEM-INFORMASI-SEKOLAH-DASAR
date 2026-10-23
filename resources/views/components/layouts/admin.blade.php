<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SDN Pojok 2</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Alpine.js untuk interaksi sidebar/dropdown -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased" x-data="{ sidebarOpen: true }">
    
    <div class="flex h-screen overflow-hidden">
        
        <!-- Memanggil Komponen Sidebar -->
        <x-admin.sidebar />

        <!-- Area Konten Utama -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Memanggil Komponen Topbar -->
            <x-admin.topbar />

            <!-- Main Content Slot -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                {{ $slot }}
            </main>

        </div>
    </div>

    <script>
        const isDigitsOnlyInput = (input) => input instanceof HTMLInputElement && (input.matches('[data-digits-only]') || input.type === 'number');
        const showDigitsOnlyWarning = (input, visible) => {
            let warning = input.nextElementSibling;
            if (!warning?.matches('[data-numeric-warning]')) {
                warning = document.createElement('p');
                warning.dataset.numericWarning = '';
                warning.className = 'mt-1 text-xs text-red-600';
                warning.setAttribute('role', 'status');
                warning.textContent = 'Kolom ini hanya boleh diisi angka.';
                warning.hidden = true;
                input.insertAdjacentElement('afterend', warning);
            }

            warning.hidden = !visible;
        };

        document.addEventListener('beforeinput', (event) => {
            const input = event.target;
            const insertedText = event.data ?? event.dataTransfer?.getData('text') ?? '';
            if (isDigitsOnlyInput(input) && insertedText !== '' && /[^0-9]/.test(insertedText)) {
                event.preventDefault();
                showDigitsOnlyWarning(input, true);
            }
        });

        document.addEventListener('paste', (event) => {
            const input = event.target;
            const pastedText = event.clipboardData?.getData('text') ?? '';
            if (isDigitsOnlyInput(input) && /[^0-9]/.test(pastedText)) {
                event.preventDefault();
                showDigitsOnlyWarning(input, true);
            }
        });

        document.addEventListener('input', (event) => {
            const input = event.target;
            if (!isDigitsOnlyInput(input)) {
                return;
            }

            const digitsOnlyValue = input.value.replace(/[^0-9]/g, '');
            const containedNonDigits = digitsOnlyValue !== input.value;
            if (containedNonDigits) {
                input.value = digitsOnlyValue;
            }

            showDigitsOnlyWarning(input, containedNonDigits);
        });
    </script>
</body>
</html>
