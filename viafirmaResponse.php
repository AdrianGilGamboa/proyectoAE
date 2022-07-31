<?php

require_once('../tcpdf/tcpdf.php');
include_once("./viafirma/includes.php");

class viafirmaResponse extends ViafirmaClientResponse {

// Proceso de Autenticacion correcto: recibe un objeto UsuarioGenericoViafirma
    public function authenticateOK($usuarioGenerico) {
        echo "Proceso de autenticacion correcto:";
    }

    // Firma o Autenticacion cancelada por el usuario
    public function cancel() {
        echo "Cancelado por el usuario";
    }

    // Error en el proceso de Firma o Autenticacion recibe String con el error
    public function error($error) {
        echo "Error: El usuario no ha sido reconocido";
    }

    // Proceso de Firma correcto: recibe un objeto UsuarioGenericoViafirma
    public function signOK($usuarioGenerico) {
        $PDF_HEADER_TITLE = "                                            "
                . "Universidad Pablo de Olavide - Ingeniería Informática";
        $PDF_HEADER_STRING = "                                                                                      "
                . "Proyecto de Administración Electrónica";
        $PDF_HEADER_LOGO = "Logo-UPO-1";
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default header data
        $pdf->SetHeaderData($PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->AddPage();
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
// QRCODE,L : QR-CODE Low error correction
        $idFirma = explode(";", $usuarioGenerico->signId);
        $pdf->write2DBarcode('https://testservices.viafirma.com/viafirma/v/' . $idFirma[0], 'QRCODE,H', 150, 30, 40, 40, $style, 'N');
        $pdf->Text(158, 25, 'Código QR');
#$pdf->write2DBarcode('www.tcpdf.org', 'PDF417', 80, 30, 0, 30, $style, 'N');
#$pdf->Text(80, 85, 'PDF417 (ISO/IEC 15438:2006)');
        $pdf->Text(14, 40, 'Firmado por: '.$usuarioGenerico->firstName.' '.$usuarioGenerico->lastName.' - '.$usuarioGenerico->numberUserId);
        $pdf->Text(14, 50, 'Puede comprobar el documento firmado en: ');
        $pdf->Text(20, 55, '');
        $pdf->writeHTML('<p style="margin-left:200px;"><a href="https://testservices.viafirma.com/viafirma/v/' . $idFirma[0] . '">https://testservices.viafirma.com</a></p>', false, false, false, false, 'L');

        $pdf->Output('viafirmaResponse.pdf', 'I');
    }

}

//Launching the process...
$test = new viafirmaResponse();
$test->process();
?>

