<?php

namespace App\Mail;

use App\Models\Chapter;
use App\Models\Fanfiction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('notification@fanfics.com.ua', 'Тестування Fanfics.com.UA'),
            subject: "Перевірка, чи працює відправка листів.",
        );
    }

    /**
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.test',

        );
    }

    /**
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
