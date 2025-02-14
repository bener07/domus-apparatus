<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Requisicao;
use App\Models\Product;
use App\Classes\Notifications;

class NotifyUserOnRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Notifications $notification)
    {
        $cart = $notification->cart;
        $this->cart = $cart;
        $this->user = $notification->user;
        $this->admin = $notification->admin;
        $this->products = $notification->cart->items()->with('product')->get()->pluck('product');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A sua requisição foi enviado para confirmação',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.user.notifyUserOnRequest',
            with: [
                'cart' => $this->cart,
                'user' => $this->user,
                'admin' => $this->admin,
                'products' => $this->products,
                'quantity' => $this->cart->total,
            ]
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
