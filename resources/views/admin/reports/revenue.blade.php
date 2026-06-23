<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan Taraka Cafe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #d97706; /* Tema Taraka */
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #666;
        }
        .summary {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        .summary-grid {
            width: 100%;
        }
        .summary-grid td {
            padding: 5px 0;
        }
        .summary-grid td.label {
            font-weight: bold;
            width: 150px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .text-center {
            text-align: center !important;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            color: #888;
            text-align: center;
        }
        .items-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .items-list li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>TARAKA CAFE</h1>
        <p>Laporan Pendapatan Detail</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <table class="summary-grid">
            <tr>
                <td class="label">Periode:</td>
                <td>{{ $startDate->translatedFormat('d F Y') }} - {{ $endDate->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td class="label">Total Pesanan:</td>
                <td>{{ $orders->count() }} Pesanan Sukses</td>
            </tr>
            <tr>
                <td class="label">Total Pendapatan:</td>
                <td style="color: #d97706; font-weight: bold; font-size: 18px;">
                    Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Order ID</th>
                <th width="15%">Waktu Transaksi</th>
                <th width="15%">Tipe / Meja</th>
                <th width="35%">Item Pesanan</th>
                <th width="15%" class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $index => $order)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $order->midtrans_order_id ?? 'N/A' }}</td>
                <td>{{ $order->created_at->translatedFormat('d M Y H:i') }}</td>
                <td>
                    @if($order->order_type === 'dine_in')
                        Dine-in (Meja {{ $order->table_number }})
                    @else
                        Takeaway
                    @endif
                </td>
                <td>
                    <ul class="items-list">
                        @foreach($order->orderItems as $item)
                            <li>{{ $item->quantity }}x {{ $item->menu->name }}</li>
                        @endforeach
                    </ul>
                </td>
                <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada transaksi pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y H:i:s') }} oleh Sistem Taraka Cafe
    </div>

</body>
</html>
