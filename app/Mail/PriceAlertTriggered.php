<?php

namespace App\Mail;

use App\Models\PriceAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceAlertTriggered extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $alert;

    public function __construct(PriceAlert $alert)
    {
        $this->alert = $alert;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Alerta de Preço Atingido! - ' . $this->alert->hotel->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.price-alert-triggered',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
