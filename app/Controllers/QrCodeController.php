<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Controllers\BaseController;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Dompdf\Dompdf;
use Dompdf\Options;

class QrCodeController extends BaseController
{
    public function downloadPdf()
    {
        // Registration page encoded in the QR code.
        $registrationUrl = site_url('welcome-register');

        // Generate a real QR code PNG in memory.
        $qrCode = new QrCode(
            data: $registrationUrl,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 350,
            margin: 15,
            roundBlockSizeMode: RoundBlockSizeMode::Margin
        );

        $writer = new PngWriter();
        $qrResult = $writer->write($qrCode);

        // Embed the QR PNG directly into the PDF.
        $qrBase64 = base64_encode($qrResult->getString());
        $qrDataUri = 'data:image/png;base64,' . $qrBase64;

        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <style>
                @page {
                    margin: 35px;
                }

                body {
                    font-family: DejaVu Sans, sans-serif;
                    text-align: center;
                    color: #222;
                }

                .brand {
                    font-size: 30px;
                    font-weight: bold;
                    margin-top: 35px;
                }

                .offer {
                    font-size: 24px;
                    color: #16803c;
                    margin: 20px 0;
                }

                .qr {
                    width: 320px;
                    height: 320px;
                    margin-top: 15px;
                }

                .instructions {
                    font-size: 16px;
                    margin-top: 20px;
                }

                .url {
                    font-size: 10px;
                    margin-top: 15px;
                    word-wrap: break-word;
                }
            </style>
        </head>
        <body>
            <div class="brand">AJOBMART</div>

            <h2>Scan to Register</h2>

            <div class="offer">
                Get ₹100 Welcome Cashback!
            </div>

            <img class="qr" src="' . $qrDataUri . '" alt="Ajobmart QR Code">

            <div class="instructions">
                Scan this QR code to open the Ajobmart registration page.
            </div>

            <div class="url">
                ' . esc($registrationUrl) . '
            </div>
        </body>
        </html>';

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader(
                'Content-Disposition',
                'attachment; filename="Ajobmart_Welcome_QR.pdf"'
            )
            ->setBody($dompdf->output());
    }
}
