<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Order  $order,
        public readonly string $oldStatus,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'تحديث حالة طلبك #' . $this->order->id . ' — ECAVO',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_status_changed',
        );
    }
}
