<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class OtpCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public int $ttlMinutes,
        public string $title = 'Verification Code'
    ) {}
    public function build()
    {
        $logo = cache()->remember('system_logo', 3600, function () {
            return DB::table('system_settings')->value('logo');
        });
        $logoUrl = $logo ? url($logo) : url('/backend/assets/img/logo.svg');

        return $this->subject("{$this->title}: {$this->code}")
            ->view('emails.otp')
            ->with([
                'code'       => $this->code,
                'ttl'        => $this->ttlMinutes,
                'supportUrl' => 'https://dayba.com/contact',
                'logoUrl'    => $logoUrl,
                'appName'    => config('app.name', 'iFire Protection'),
                'sentAt'     => now()->format('M d, Y H:i'),
            ]);
    }
}
