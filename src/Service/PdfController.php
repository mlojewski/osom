<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PdfController extends AbstractController
{
    //TODO nie w serwisach
    public function generatePdf(string $route, array $parameters, string $name): void
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Times');
    
        $domPdf = new Dompdf($pdfOptions);
        $html = $this->renderView(
            $route,
            $parameters
        );
        
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        $domPdf->loadHtml($html);

        $domPdf->setPaper('A4', 'portrait');
      
        $domPdf->render();

        $domPdf->stream(
            $name.'.pdf',
            [
                "Attachment" => true
            ]
        );
    }
}
