<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    /**
     * Handle webhook notification dari Midtrans
     * Route ini tanpa CSRF dan tanpa auth
     */
    public function handle(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $fraudStatus       = $notification->fraud_status;
            $paymentType       = $notification->payment_type;

            // Jika bank transfer, coba dapatkan nama banknya
            if ($paymentType === 'bank_transfer') {
                $vaNumbers = $request->input('va_numbers');
                if (is_array($vaNumbers) && count($vaNumbers) > 0) {
                    $bank = $vaNumbers[0]['bank'] ?? '';
                    if ($bank) {
                        $paymentType = 'bank_transfer_' . strtolower($bank);
                    }
                } elseif ($request->has('permata_va_number')) {
                    $paymentType = 'bank_transfer_permata';
                }
            } elseif ($paymentType === 'echannel') {
                $paymentType = 'bank_transfer_mandiri'; // Mandiri bill payment
            }

            if (str_starts_with($orderId, 'RES-')) {
                // Proses Webhook untuk Reservasi (Booking Fee)
                $reservation = \App\Models\Reservation::where('midtrans_order_id', $orderId)->first();

                if (!$reservation) {
                    Log::warning("Midtrans webhook: Reservation not found for ID: {$orderId}");
                    return response()->json(['message' => 'Reservation not found'], 404);
                }

                if ($transactionStatus === 'capture') {
                    if ($fraudStatus === 'accept') {
                        $reservation->payment_status = 'paid';
                        $reservation->status = 'confirmed';
                    } else {
                        $reservation->payment_status = 'failed';
                    }
                } elseif ($transactionStatus === 'settlement') {
                    $reservation->payment_status = 'paid';
                    $reservation->status = 'confirmed';
                } elseif ($transactionStatus === 'pending') {
                    $reservation->payment_status = 'unpaid';
                } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                    $reservation->payment_status = $transactionStatus === 'expire' ? 'expired' : 'failed';
                    if (in_array($reservation->status, ['pending', 'pending_payment'])) {
                        $reservation->status = 'cancelled';
                    }
                }

                $reservation->save();
                Log::info("Midtrans webhook: Reservation {$orderId} updated — status: {$transactionStatus}");

            } else {
                // Proses Webhook untuk Order Makanan (TRK-...)
                $order = Order::where('midtrans_order_id', $orderId)->first();

                if (!$order) {
                    Log::warning("Midtrans webhook: Order not found for ID: {$orderId}");
                    return response()->json(['message' => 'Order not found'], 404);
                }

                // Update payment_type
                $order->payment_type = $paymentType;

                // Tentukan status berdasarkan transaction_status
                if ($transactionStatus === 'capture') {
                    if ($fraudStatus === 'accept') {
                        $order->payment_status = 'paid';
                    } else {
                        $order->payment_status = 'failed';
                    }
                } elseif ($transactionStatus === 'settlement') {
                    $order->payment_status = 'paid';
                } elseif ($transactionStatus === 'pending') {
                    $order->payment_status = 'unpaid';
                } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
                    $order->payment_status = $transactionStatus === 'expire' ? 'expired' : 'failed';
                }

                $order->save();
                Log::info("Midtrans webhook: Order {$orderId} updated — status: {$transactionStatus}, payment: {$paymentType}");
            }

            return response()->json(['message' => 'OK']);

        } catch (\Exception $e) {
            Log::error("Midtrans webhook error: " . $e->getMessage());
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
