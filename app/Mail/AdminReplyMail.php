<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class AdminReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $firstName;
    public string $replyMessage;
    public string $logoUrl;
    public string $appName;

    public function __construct(string $fullName, string $replyMessage)
    {
        $this->firstName    = explode(' ', trim($fullName))[0];
        $this->replyMessage = $replyMessage;
        $this->appName      = config('app.name', '3 Pro App');

        $logo = cache()->remember('system_logo', 3600, function () {
            return DB::table('system_settings')->value('logo');
        });

        $this->logoUrl = $logo ? url($logo) : url('/backend/assets/img/logo.svg');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Re: Your support request — {$this->appName}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-reply'
        );
    }
}
