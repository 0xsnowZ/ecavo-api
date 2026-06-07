<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewOrderNotification extends Notification
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_order',
            'order_id' => $this->order->id,
            'customer_name' => $this->order->name,
            'total_amount' => $this->order->total_amount,
            'message_ar' => "طلب جديد #{$this->order->id} بقيمة {$this->order->total_amount}$",
            'message_en' => "New Order #{$this->order->id} of {$this->order->total_amount}$",
            'desc_ar' => "قام {$this->order->name} بطلب جديد",
            'desc_en' => "{$this->order->name} placed a new order",
        ];
    }
}
