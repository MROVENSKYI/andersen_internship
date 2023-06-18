<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\SentMessage;
use Illuminate\Queue\SerializesModels;
use App;
use Illuminate\Support\Facades\Mail;

class DeleteUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(protected App\Models\User $user)
    {
    }

    public function content(): Content
    {
        return new Content(view: 'emails.user_deletion', with: [
            'email' => $this->user->email
        ]);
    }

    public function build(): ?SentMessage
    {
        $pdf = PDF::loadView('emails.user_deletion', [
            'email' => $this->user->email
        ]);

        return Mail::send('emails.user_deletion', [
            'email' => $this->user->email
        ], function ($message) use ($pdf) {
            $message
                ->to($this->user->email)
                ->from('jeffrey@example.com')
                ->subject('Account Deletion')
                ->attachData($pdf->output(), 'user_deletion.pdf');
        });
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = PDF::loadView('emails.user_deletion', [
            'email' => $this->user->email
        ]);

        return [
            Attachment::fromData(function () use ($pdf) {
                return $pdf->output();
            }, 'user_deletion.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
