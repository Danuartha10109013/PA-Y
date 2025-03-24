<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Anggota;

class Mailkonfir extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $email_kantor;
    public $users;
    public $password;


    public function __construct($email_kantor, $password)
    {
        // $this->anggota->nama, // Mengirim data anggota ke view
        // $this->anggota->email_kantor,
        // $this->nama = $nama;
        // $this->email_kantor = $email;
        $anggota = Anggota::where('email_kantor', $email_kantor)->first();

        if ($anggota) {
            // Assign data ke variabel class untuk digunakan di view
            $this->nama = $anggota->nama;
            $this->email_kantor = $anggota->email_kantor;
            $this->password = $password;
        } else {
            // Jika anggota tidak ditemukan, assign nilai default atau handle error
            $this->nama = 'Pengguna';
            $this->email_kantor = $email_kantor;
            $this->password = $password;
        }
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('emails.konfirmasi')
            > with([
                'nama' => $this->nama,
                'email_kantor' => $this->email_kantor,
                'password' => $this->password,

                //     'nama' => $this->nama,
                //     'email' => $this->email,
            ]);
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'This is your account confirmation email.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.register_informasi', // View yang akan digunakan untuk email
            // with: [
            // 'nama' => $this->anggota->nama, // Mengirim data anggota ke view
            // 'email' => $this->anggota->email_kantor,
            // ]
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
