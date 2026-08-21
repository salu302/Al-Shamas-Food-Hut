<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class OrderConfirmationMail extends Mailable
{
    use Queueable;

    public function __construct(
        public Order $order,
        public bool $isCustomerCopy = false,
    ) {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->isCustomerCopy ? 'Your order confirmation from Al-Shamas Pizza Hut' : 'New order received for Al-Shamas Pizza Hut',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation',
            with: [
                'order' => $this->order,
                'isCustomerCopy' => $this->isCustomerCopy,
            ],
        );
    }
}
