<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $anggota; // Data anggota

    /**
     * Create a new message instance.
     *
     * @param $anggota
     */
    public function __construct($anggota)
    {
        $this->anggota = $anggota;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Pemberitahuan Penolakan Keanggotaan')
                    ->view('email.reject_notification') // Templat email
                    ->with([
                        'name' => $this->anggota->name,
                        'reason' => $this->anggota->alasan_ditolak, // Mengambil alasan penolakan dari database
                    ]);
    }
}
