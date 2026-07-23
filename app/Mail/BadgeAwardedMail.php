<?php

namespace App\Mail;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserPiller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class BadgeAwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $recipient;
    public Badge $badge;
    public User $assignedBy;
    public UserPiller $pillar;
    public string $logoUrl;
    public string $appName;

    public function __construct(User $recipient, Badge $badge, User $assignedBy, UserPiller $pillar)
    {
        $this->recipient = $recipient;
        $this->badge = $badge;
        $this->assignedBy = $assignedBy;
        $this->pillar = $pillar;
        $this->appName = config('app.name', 'Application');

        $logo = cache()->remember('system_logo', 3600, function () {
            return DB::table('system_settings')->value('logo');
        });

        $this->logoUrl = $logo ? url($logo) : url('/backend/assets/img/logo.svg');
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: "You received a new badge on {$this->appName}");
    }

    public function content(): Content
    {
        return new Content(view: 'emails.badge-awarded');
    }
}
