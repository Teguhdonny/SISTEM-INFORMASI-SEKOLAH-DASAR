<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan {{ $title }} - SDN Pojok 2</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; color: #172033; font-family: Arial, sans-serif; }
        .toolbar { display: flex; justify-content: flex-end; margin: 0 auto 18px; max-width: 1200px; }
        button { border: 0; border-radius: 8px; background: #1d4ed8; color: #fff; cursor: pointer; font-size: 14px; font-weight: 700; padding: 11px 18px; }
        .report { margin: 0 auto; max-width: 1200px; }
        h1 { margin: 0; font-size: 22px; }
        .school { margin: 0 0 5px; color: #1d4ed8; font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .meta { margin: 8px 0 18px; color: #64748b; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th, td { border: 1px solid #dbe2ea; padding: 7px 8px; text-align: left; vertical-align: top; overflow-wrap: anywhere; }
        th { background: #eff4ff; color: #1e3a8a; font-size: 9px; text-transform: uppercase; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .empty { padding: 24px; text-align: center; color: #64748b; }
        @page { size: landscape; margin: 10mm; }
        @media print {
            body { padding: 0; }
            .toolbar { display: none; }
            .report { max-width: none; }
            tr { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="toolbar"><button type="button" onclick="window.print()">Simpan sebagai PDF</button></div>
    <main class="report">
        <p class="school">SDN Pojok 2 Kota Kediri</p>
        <h1>Laporan {{ $title }}</h1>
        <p class="meta">Dicetak {{ $printedAt }} · {{ count($rows) }} data</p>
        <table>
            <thead><tr>@foreach ($columns as $column)<th>{{ $column }}</th>@endforeach</tr></thead>
            <tbody>
                @forelse ($rows as $row)
                    <tr>@foreach ($row as $value)<td>{{ $value }}</td>@endforeach</tr>
                @empty
                    <tr><td class="empty" colspan="{{ count($columns) }}">Tidak ada data untuk dicetak.</td></tr>
                @endforelse
            </tbody>
        </table>
    </main>
    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
