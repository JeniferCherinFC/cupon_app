<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use chillerlan\QRCode\QRCode;

class QRController extends Controller
{
    public function generateQRCode($data)
    {
        // Create a QRCode instance
        $qrcode = new QRCode();

        // Set additional options if needed
        // $qrcode->setModuleValues([1, 255]);
        // $qrcode->setSize(300);

        // Generate the QR code as a PNG image
        $image = $qrcode->render($data);

        // Output the QR code image
        // header('Content-Type: image/png');
        return $image;

        
    }
}