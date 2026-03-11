<?php
namespace App\Mail;

use App\Models\AuditLead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAuditMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public AuditLead $lead) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔍 New Free Audit Request from ' . $this->lead->name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.audit');
    }
}