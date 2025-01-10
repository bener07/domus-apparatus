<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;
use App\Models\Requisicao;
use App\Models\Product;
use App\Classes\GestorDeRequisicoes;
use Illuminate\Support\Facades\Log;

class SendConfirmationRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct($requisicao)
    {
        $this->requisicao = $requisicao->requisicao;
        $this->admin = $requisicao->admin;
        $this->products = $requisicao->products;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('domus-app@example.com', 'Domus Apparatus'),
            subject: 'Confirmação de Requisição',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.admin.confirmRequisicao',
            with: [
               'requisicao' => $this->requisicao,
                'admin' => $this->admin,
                'products' => $this->products,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
