<?php

namespace App\Mail;

use App\Models\LibroReclamacion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Confirmación automática al consumidor tras registrar reclamo/queja.
 * Incluye constancia en PDF según INDECOPI.
 */
class ReclamacionRegistradaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public LibroReclamacion $reclamo
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Constancia de registro - Libro de Reclamaciones ' . $this->reclamo->codigo_reclamo,
            replyTo: [config('mail.from.address')],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reclamacion-registrada'
        );
    }

    public function attachments(): array
    {
        $pdfService = app(\App\Services\LibroReclamacionPdfService::class);
        $pdf = $pdfService->generarConstanciaPdf($this->reclamo);
        $content = $pdf->output();

        return [
            Attachment::fromData($content, 'constancia-' . $this->reclamo->codigo_reclamo . '.pdf', [
                'mime' => 'application/pdf',
            ]),
        ];
    }
}
