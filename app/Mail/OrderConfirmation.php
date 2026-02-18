<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public ?string $pdfContent = null
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirmación de compra - ' . $this->order->order_number,
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-confirmation'
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        if ($this->pdfContent === null || $this->pdfContent === '') {
            return [];
        }

        return [
            Attachment::fromData(
                fn () => $this->pdfContent,
                'entradas-' . $this->order->order_number . '.pdf',
                ['mime' => 'application/pdf']
            ),
        ];
    }
}
