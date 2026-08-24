<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ContactSupportMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $firstName;
    public string $logoUrl;
    public string $appName;

    public function __construct(string $fullName)
    {
        $this->firstName = explode(' ', trim($fullName))[0];
        $this->appName   = config('app.name', '3 Pro App');

        $logo = cache()->remember('system_logo', 3600, function () {
            return DB::table('system_settings')->value('logo');
        });

        $this->logoUrl = $logo ? url($logo) : url('/backend/assets/img/logo.svg');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "We got your message, {$this->firstName}"
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-support'
        );
    }
}
