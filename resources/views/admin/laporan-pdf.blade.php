<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi — MATAIR Auto Care</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; background: #fff; }

        .header { text-align:center; padding: 20px 0 15px; border-bottom: 2px solid #1a1a1a; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: 900; letter-spacing: 4px; margin-bottom: 2px; }
        .header p  { font-size: 9px; letter-spacing: 6px; color: #666; }
        .header .sub { font-size: 13px; font-weight: bold; margin-top: 8px; color: #333; }

        .info { display:flex; justify-content:space-between; margin-bottom: 15px; font-size: 10px; color: #555; }

        .summary { display:flex; gap: 15px; margin-bottom: 20px; }
        .summary-box {
            flex: 1; border: 1px solid #ddd; border-radius: 4px;
            padding: 10px; text-align: center;
        }
        .summary-box .label { font-size: 9px; color: #888; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .summary-box .value { font-size: 16px; font-weight: bold; color: #1a1a1a; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #1a1a1a; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 9px; letter-spacing: 1px; text-transform: uppercase; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        tbody tr:hover { background: #f0f0f0; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
        tfoot tr { background: #f0f0f0; font-weight: bold; }
        tfoot td { padding: 8px 10px; border-top: 2px solid #1a1a1a; }

        .footer { text-align:center; margin-top: 20px; font-size: 9px; color: #aaa; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>MATAIR</h1>
        <p>AUTO CARE</p>
        <div class="sub">LAPORAN TRANSAKSI</div>
    </div>

    <div class="info">
        <span>Periode: {{ $startDate }} s/d {{ $endDate }}</span>
        <span>Dicetak: {{ now()->format('d M Y, H:i') }} WIB</span>
    </div>

    <div class="summary">
        <div class="summary-box">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $totalCount }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-box">
            <div class="label">Rata-rata</div>
            <div class="value">Rp {{ $totalCount > 0 ? number_format($totalRevenue / $totalCount, 0, ',', '.') : 0 }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Pelanggan</th>
                <th>No. HP</th>
                <th>Kendaraan</th>
                <th>Plat</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $i => $res)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $res->customer_name }}</td>
                    <td>{{ $res->customer_phone }}</td>
                    <td>{{ $res->car_brand }} ({{ $res->vehicle_type }})</td>
                    <td>{{ $res->plate_number }}</td>
                    <td>{{ $res->service->name }}@if($res->service->size) - {{ $res->service->size }}@endif</td>
                    <td>{{ $res->reservation_date->format('d/m/Y') }}</td>
                    <td>{{ $res->reservation_time }}</td>
                    <td>{{ $res->use_free_wash ? 'GRATIS' : 'Rp ' . number_format($res->total_price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align:center; padding:20px; color:#999;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($totalCount > 0)
            <tfoot>
                <tr>
                    <td colspan="8" style="text-align:right;">Total Pendapatan</td>
                    <td>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        MATAIR Auto Care — Laporan dibuat otomatis oleh sistem
    </div>

</body>
</html>