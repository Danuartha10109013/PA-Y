<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Anggota; // Import model Anggota

class Mailkonfir extends Mailable
{
    use Queueable, SerializesModels;

    public $anggota; // Variabel untuk menyimpan data anggota

    /**
     * Create a new message instance.
     */
    public function __construct(Anggota $anggota)
    {
        $this->anggota = $anggota; // Assign data anggota ke variabel
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('email.register_informasi');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Informasi Registrasi', // Subjek email
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.register_informasi', // View yang akan digunakan untuk email
            with: [
                'nama' => $this->anggota->nama, // Mengirim data anggota ke view
                'email' => $this->anggota->email_kantor,
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
