<?php

namespace App\Services;

use App\Models\LibroReclamacion;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Genera la constancia en PDF del Libro de Reclamaciones (INDECOPI).
 * Incluye QR con el código del reclamo para verificación.
 */
class LibroReclamacionPdfService
{
    public function generarConstanciaPdf(LibroReclamacion $reclamo): \Barryvdh\DomPDF\PDF
    {
        $qrDataUri = $this->qrDataUri($reclamo->codigo_reclamo);
        $reclamo->load('evento');

        $pdf = Pdf::loadView('libro-reclamaciones.constancia-pdf', [
            'reclamo' => $reclamo,
            'qr_data_uri' => $qrDataUri,
        ]);
        $pdf->setPaper('a4', 'portrait');

        return $pdf;
    }

    public function qrDataUri(string $codigo): string
    {
        $qrCode = new QrCode($codigo);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        return $result->getDataUri();
    }
}
