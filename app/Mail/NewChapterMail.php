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

class NewChapterMail extends Mailable
{
    use Queueable, SerializesModels;

    private Fanfiction $fanfic;
    private Chapter $chapter;

    /**
     * @return void
     */
    public function __construct(Fanfiction $fanfic, Chapter $chapter)
    {
        $this->fanfic = $fanfic;
        $this->chapter = $chapter;
    }

    /**
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address('notification@fanfics.com.ua', 'Оповіщення Fanfics.com.UA'),
            subject: "[Фанфіки] Новий розділ {$this->chapter->title} ({$this->fanfic->title}) від {$this->fanfic->author->name}",
        );
    }

    /**
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.new-chapter',
            with: [
                'fanfic' => $this->fanfic,
                'chapter' => $this->chapter,
            ]

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
