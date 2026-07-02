<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Bill - {{ $order->midtrans_order_id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #D2691E; /* Accent color roughly */
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 12px;
            color: #777;
        }
        .info-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-table td {
            padding: 5px 0;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .items-table th {
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .text-right {
            text-align: right !important;
        }
        .summary-table {
            width: 50%;
            float: right;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 8px 10px;
        }
        .summary-total {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #333;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">Taraka Cafe</div>
        <div class="subtitle">Jl. Siliwangi No.12, Bogor | IG: @taraka.cafe</div>
        <h2>E-BILL / OFFICIAL RECEIPT</h2>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Order ID</td>
            <td>: {{ $order->midtrans_order_id }}</td>
            <td class="info-label">Tanggal</td>
            <td>: {{ $order->created_at->format('d M Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="info-label">Nama Pelanggan</td>
            <td>: {{ $order->customer_name }}</td>
            <td class="info-label">Tipe Pesanan</td>
            <td>: {{ strtoupper($order->order_type) }}</td>
        </tr>
        <tr>
            <td class="info-label">Status Bayar</td>
            <td>: LUNAS</td>
            <td class="info-label">Nomor Meja</td>
            <td>: {{ $order->order_type === 'takeaway' ? '-' : $order->table_number }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Menu</th>
                <th>Catatan</th>
                <th class="text-right">Harga</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    <strong>{{ $item->menu->name }}</strong>
                    <div style="font-size: 11px; color: #666;">Sugar: {{ $item->sugar_level }}</div>
                </td>
                <td>{{ $item->notes ?: '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->menu->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <!-- Assuming tax is included or calculated. For Taraka we haven't explicitly separated tax in DB yet, but we can show Subtotal = Total as tax inclusive if needed. Or we just show Total. -->
        <tr>
            <td>Subtotal</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Tax (PB1 10%)</td>
            <td class="text-right">Termasuk</td>
        </tr>
        <tr class="summary-total">
            <td>TOTAL</td>
            <td class="text-right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        Terima kasih atas kunjungan Anda.<br>
        Simpan struk ini sebagai bukti pembayaran yang sah.
    </div>

</body>
</html>
