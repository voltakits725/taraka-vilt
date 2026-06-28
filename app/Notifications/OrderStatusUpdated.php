<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    private function getDynamicMessage(): string
    {
        if ($this->order->order_status === 'processing') {
            return 'Pesanan kamu sedang disiapkan oleh koki!';
        } elseif ($this->order->order_status === 'completed') {
            if ($this->order->order_type === 'takeaway') {
                return 'Pesanan kamu sudah siap! Silakan ambil di kasir.';
            } else {
                return 'Pesanan kamu sudah siap dan akan segera dihidangkan ke Meja ' . $this->order->table_number . '.';
            }
        }
        
        return 'Status pesanan kamu saat ini: ' . $this->order->order_status;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order',
            'order_id' => $this->order->id,
            'status' => $this->order->order_status,
            'message' => $this->getDynamicMessage(),
            'midtrans_order_id' => $this->order->midtrans_order_id,
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'order',
            'order_id' => $this->order->id,
            'status' => $this->order->order_status,
            'message' => $this->getDynamicMessage(),
            'midtrans_order_id' => $this->order->midtrans_order_id,
        ]);
    }
}
