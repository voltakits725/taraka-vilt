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
            background-color: #f3f4f6; /* Gray background for body */
        }
        .receipt-container {
            max-width: 700px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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

<div id="receipt-wrapper" class="receipt-container">
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
        <tr>
            @php
                $pt = $order->payment_type;
                if (str_starts_with($pt, 'bank_transfer_')) {
                    $bank = strtoupper(str_replace('bank_transfer_', '', $pt));
                    $paymentName = 'VA ' . $bank;
                } elseif ($pt === 'echannel') {
                    $paymentName = 'VA MANDIRI';
                } elseif (in_array($pt, ['qris', 'gopay', 'shopeepay'])) {
                    $paymentName = strtoupper($pt);
                } elseif ($pt === 'credit_card') {
                    $paymentName = 'KARTU KREDIT';
                } else {
                    $paymentName = strtoupper(str_replace('_', ' ', $pt ?: 'MIDTRANS'));
                }
            @endphp
            <td class="info-label">Metode Bayar</td>
            <td>: {{ $paymentName }}</td>
            <td colspan="2"></td>
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
                </td>
                <td>{{ $item->notes ?: '-' }}</td>
                <td class="text-right">Rp {{ number_format($item->subtotal / $item->quantity, 0, ',', '.') }}</td>
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
</div>

    @if(isset($autoDownloadImage) && $autoDownloadImage)
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        window.onload = function() {
            setTimeout(() => {
                const element = document.getElementById('receipt-wrapper');
                html2canvas(element, { 
                    scale: 2,
                    backgroundColor: '#ffffff'
                }).then(canvas => {
                    let link = document.createElement('a');
                    link.download = 'E-Bill_Taraka_{{ $order->midtrans_order_id }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                    
                    // Optional: show a message or close window
                    document.body.innerHTML = '<div style="text-align:center; padding: 50px; font-family: sans-serif;"><h2>Gambar berhasil diunduh!</h2><p>Anda dapat menutup tab ini.</p></div>';
                });
            }, 500); // small delay to ensure fonts load
        };
    </script>
    @endif
</body>
</html>
