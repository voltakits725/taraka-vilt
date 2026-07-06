<?php

namespace App\Http\Controllers\Customer\Table;

use App\Http\Controllers\Controller;

class TableScanController extends Controller
{
    public function __invoke($table)
    {
        if (!is_numeric($table) || $table < 1 || $table > 15) {
            abort(404, 'Meja tidak ditemukan');
        }

        session([
            'table_number' => $table,
            'table_scan_time' => now()->timestamp
        ]);

        return redirect()->route('customer.menu')->with('message', "Kamu terhubung dengan Meja $table. Silakan pesan!");
    }
}
