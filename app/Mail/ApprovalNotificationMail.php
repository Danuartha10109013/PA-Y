<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;    

    public $user;
    public $simpanan;

    public function __construct($user, $simpanan)
    {
        $this->user = $user;
        $this->simpanan = $simpanan;
    }

    public function build()
    {
        return $this->subject('Pengajuan Simpanan Disetujui')
            ->view('email.notification_approval')
            ->with([
                'userName' => $this->user->name,
                'noSimpanan' => $this->simpanan->no_simpanan,
                'virtualAccount' => $this->simpanan->virtual_account,
                'expiredAt' => $this->simpanan->expired_at,
            ]);
    }
}
