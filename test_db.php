<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    DB::insert("insert into orders (user_id, customer_name, order_type, table_number, total_amount, order_status, payment_status, midtrans_order_id, updated_at, created_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [2, 'Alif', 'takeaway', null, 203500, 'pending', 'unpaid', 'TRK-12345678', '2026-06-26 09:54:29', '2026-06-26 09:54:29']);
    echo "SUCCESS\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
