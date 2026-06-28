<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public $reservation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
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
        $timeStr = Carbon::createFromFormat('H:i:s', $this->reservation->reservation_time)->format('H:i');
        
        if ($this->reservation->status === 'confirmed') {
            return 'Hore! Booking Meja ' . $this->reservation->table_number . ' untuk jam ' . $timeStr . ' sudah diamankan.';
        } elseif ($this->reservation->status === 'cancelled') {
            return 'Maaf, booking Meja ' . $this->reservation->table_number . ' untuk jam ' . $timeStr . ' terpaksa kami tolak/batalkan. Silakan hubungi admin.';
        }
        
        return 'Status reservasi meja kamu saat ini: ' . $this->reservation->status;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'reservation',
            'reservation_id' => $this->reservation->id,
            'table_number' => $this->reservation->table_number,
            'status' => $this->reservation->status,
            'message' => $this->getDynamicMessage(),
        ];
    }

    /**
     * Get the broadcast representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => 'reservation',
            'reservation_id' => $this->reservation->id,
            'table_number' => $this->reservation->table_number,
            'status' => $this->reservation->status,
            'message' => $this->getDynamicMessage(),
        ]);
    }
}
