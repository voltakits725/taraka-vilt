<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $order = \App\Models\Order::create([
        'user_id'           => 2,
        'customer_name'     => 'Alif',
        'order_type'        => 'takeaway',
        'table_number'      => null,
        'total_amount'      => 203500,
        'order_status'      => 'pending',
        'payment_status'    => 'unpaid',
        'midtrans_order_id' => 'TRK-' . time(),
    ]);
    echo "SUCCESS: Order ID " . $order->id . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
