<?php
require_once('../tcpdf/tcpdf.php');
include_once("./viafirma/includes.php");
/* Iniciamos el funcionamiento de las sesiones de PHP */
session_start();
$_SESSION['CLIENT_VERIFY'] = $_SERVER['SSL_CLIENT_VERIFY'];
$_SESSION['CLIENT_V_REMAIN'] = $_SERVER['SSL_CLIENT_V_REMAIN'];
/* Comprobamos si tenemos los datos del certificado */
if ($_SESSION['CLIENT_VERIFY'] !== 'SUCCESS' || $_SESSION['CLIENT_V_REMAIN'] <= 0) {
    header("Location: http://localhost/GilGamboaAdrian_ProyectoAE/login.php");
} else {
    $apellidosNombreDNI = $_SERVER['SSL_CLIENT_S_DN_CN'];
    $apellidosNombreDNIArray = explode('-', $apellidosNombreDNI);


    $apellidosNombreArray = explode(' ', $apellidosNombreDNIArray[0]);
    if ($apellidosNombreArray[3] != null) {
        $nombre = $apellidosNombreArray[2] . $apellidosNombreArray[3];
    } else {
        $nombre = $apellidosNombreArray[2];
    }
    $apellidos = $apellidosNombreArray[0] . " " . $apellidosNombreArray[1];
    $DNI = $apellidosNombreDNIArray[1];
    $fechaEmision = $_SERVER['SSL_CLIENT_V_START'];
    $fechaCaducidad = $_SERVER['SSL_CLIENT_V_END'];
    $diasRestantesCaducidad = $_SERVER['SSL_CLIENT_V_REMAIN'];
    $numSerie = $_SERVER['SSL_CLIENT_M_SERIAL'];
    $datosServidorCertificado = explode('=', $_SERVER['SSL_CLIENT_I_DN']);
    $datosServCertCN = explode(',', $datosServidorCertificado[1]);
    $datosServCertOU = explode(',', $datosServidorCertificado[2]);
    $datosServCertO = explode(',', $datosServidorCertificado[3]);
    $datosServCertC = explode(',', $datosServidorCertificado[4]);

    //Comprobar si existe el archivo en nuestro equipo, para proceder a firmarlo o de lo contrario, descargarlo para visualizarlo.
    if (isset($_POST['firmar'])) {
        $filename = "C:\Users\adrii_gil99\Downloads\InfoCertDigital.pdf";
        if (!file_exists($filename)) {
            $PDF_HEADER_TITLE = "                                            "
                    . "Universidad Pablo de Olavide - Ingeniería Informática";
            $PDF_HEADER_STRING = "                                                                                      "
                    . "Proyecto de Administración Electrónica";
            $PDF_HEADER_LOGO = "Logo-UPO-1";

// create new PDF document

            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Adrián Gil');
            $pdf->SetTitle('Universidad Pablo de Olavide');
            $pdf->SetSubject('Administración Electrónica');

// set default header data
            $pdf->SetHeaderData($PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set font
            $pdf->SetFont('helvetica', '', 10, '', false);

// add a page
            $pdf->AddPage();


// set default form properties
            $pdf->setFormDefaultProp(array('lineWidth' => 1, 'borderStyle' => 'solid', 'fillColor' => array(255, 255, 200), 'strokeColor' => array(255, 128, 128)));

            $pdf->SetFont('helvetica', 'BI', 18);
            $pdf->Cell(0, 5, 'Información de Certificado Digital', 0, 1, 'C');
            $pdf->Ln(10);

            $pdf->SetFont('helvetica', 'BI', 14);
            $pdf->Cell(0, 5, 'Datos Personales', 0, 1, 'C');
            $pdf->Ln(5);

            $pdf->SetFont('helvetica', '', 12);

// First name
            $pdf->Cell(20, 5, 'Nombre: ');
            $pdf->Cell(45, 5, $nombre);
            #$pdf->Ln(6);
// Last name
            $pdf->Cell(20, 5, 'Apellidos: ');
            $pdf->Cell(50, 5, $apellidos);
            #$pdf->Ln(6);
// DNI
            $pdf->Cell(10, 5, 'DNI: ');
            $pdf->Cell(35, 5, $DNI);
            $pdf->Ln(20);

            $pdf->SetFont('helvetica', 'BI', 14);
            $pdf->Cell(0, 5, 'Datos Certificado Digital', 0, 1, 'C');
            $pdf->Ln(5);

            $pdf->SetFont('helvetica', '', 12);

            $pdf->Cell(60, 5, 'ID Certificado: ');
            $pdf->Cell(10, 5, $numSerie);
            $pdf->Ln(6);
            $pdf->Cell(60, 5, 'Autoridad Certificadora: ');
            $pdf->Cell(50, 5, $datosServCertCN[0]);
            $pdf->Ln(6);
            $pdf->Cell(60, 5, 'Departamento: ');
            $pdf->Cell(30, 5, $datosServCertOU[0]);
            $pdf->Ln(6);
            $pdf->Cell(60, 5, 'Prov. de Serv. de Cert.:(*) ');
            $pdf->Cell(50, 5, $datosServCertO[0]);
            $pdf->Ln(6);
            $pdf->Cell(60, 5, 'País: ');
            $pdf->Cell(30, 5, $datosServCertC[0]);

            $pdf->Ln(10);
            $pdf->Cell(60, 5, 'Fecha Emisión: ');
            $pdf->Cell(30, 5, $fechaEmision);
            $pdf->Ln(6);
            $pdf->Cell(60, 5, 'Fecha Caducidad: ');
            $pdf->Cell(50, 5, $fechaCaducidad);
            $pdf->Ln(6);
            $pdf->Cell(60, 5, 'Días Restantes de Validez: ');
            $pdf->Cell(30, 5, $diasRestantesCaducidad);
            $pdf->Ln(40);

            $pdf->Cell(60, 5, '(*)Proveedor de Servicios de Certificación');

            // ---------------------------------------------------------
//Close and output PDF document
            $pdf->Output('InfoCertDigital.pdf', 'D');
        } else {


            // URL en la que se encuentra el servicio de Viafirma y URL publica de la aplicacion
            ViafirmaClientFactory::init("$VIAFIRMA_SERVICE_URL", "$VIAFIRMA_SERVICE_URL" . "/rest", "http://localhost/GilGamboaAdrian_ProyectoAE/", "dev_UPO", "ZGWJM5TZ3W07C1HMAFXKP7X157BFL");
            $viafirmaClient = ViafirmaClientFactory::getInstance();

            $filename = "C:\Users\adrii_gil99\Downloads\InfoCertDigital.pdf";
            $file = fopen($filename, 'r');
            $datos = fread($file, filesize($filename));
            fclose($file);
            $documento = Document::newDocument("InfoCertDigital.pdf", $datos, MimeType::$PDF, SignatureType::$PADES_BASIC);

            #$idFirma = $viafirmaClient->prepareFirmaWithTypeFileAndFormatSign($filename, MimeType::$PDF, $datos, SignatureType::$PADES_BASIC);
            //Creamos el objeto Policy

            $policy = Policy::newPolicy();
            //Indica el tipo de firma (para firmas PDF se usa siempre ATTACHED)
            $policy->typeSign = TypeSign::$ATTACHED;
            //Indica el formato (en este caso digitalizada)
            $policy->typeFormatSign = SignatureType::$PADES_BASIC;

//-------------------------------  Inicio - Policy con Imagen
            //Rectangle justo en el final de la página
            $rectangle = new Rectangle(20, 40, 75, 650);
            $datosRectangle = $rectangle->getDatosRectangle();

            //Creamos policy
            //Pagina donde se insertará el sello, -1 para elegir la última página
            $policy->addParameter(PolicyParams::$DIGITAL_SIGN_PAGE, "1");
            //Rectangle donde colocar el sello
            $policy->addParameter(PolicyParams::$DIGITAL_SIGN_RECTANGLE, $datosRectangle);

            //Indica a Adobe reader que debe esconder la imagen de validación (la marca verde, naranja o roja sobre la imagen de sello)
            $policy->addParameter(PolicyParams::$DIGITAL_SIGN_STAMPER_HIDE_STATUS, "true");
            //Opcional, texto que aparecerá en el sello. Por defecto aparece: Digitally signed by: [CN]
            $policy->addParameter(PolicyParams::$DIGITAL_SIGN_STAMPER_TEXT, "Firmado por [CN] con DNI [SERIALNUMBER] trabajador de [O] en el departamento de [OU]");

            //Tipo del sello. "QR-BAR-H" para tipo QRCode
            $policy->addParameter(PolicyParams::$DIGITAL_SIGN_STAMPER_TYPE, "QR-BAR-H");
            $idtemporal = $viafirmaClient->prepareSignWithPolicy($policy, $documento);
            $viafirmaClient->solicitarFirma($idtemporal, "http://localhost/GilGamboaAdrian_ProyectoAE/viafirmaResponse.php");
        }
    } else {
        ?>
        <!DOCTYPE html>
        <html style="font-size: 16px;">
            <head>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta charset="utf-8">
                <meta name="keywords" content="Bienvenido">
                <meta name="description" content="">
                <meta name="page_type" content="np-template-header-footer-from-plugin">
                <title>P&aacute;gina principal</title>
                <link rel="stylesheet" href="nicepage.css" media="screen">
                <link rel="stylesheet" href="Style.css" media="screen">
                <script class="u-script" type="text/javascript" src="jquery.js" defer=""></script>
                <script class="u-script" type="text/javascript" src="nicepage.js" defer=""></script>
                <meta name="generator" content="Nicepage 4.8.2, nicepage.com">
                <link id="u-theme-google-font" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i|Open+Sans:300,300i,400,400i,500,500i,600,600i,700,700i,800,800i">



                <script type="application/ld+json">{
                    "@context": "http://schema.org",
                    "@type": "Organization",
                    "name": "",
                    "logo": "images/Logo-UPO-1.png"
                    }</script>
                <meta name="theme-color" content="#478ac9">
                <meta property="og:title" content="Style">
                <meta property="og:type" content="website">
            </head>
            <body data-home-page="#" data-home-page-title="Principal" class="u-body u-xl-mode"><header class="u-clearfix u-header u-header" id="sec-c5a5">
                    <a style="margin:auto; margin-top:15px;" href="https://www.upo.es/escuela-politecnica-superior/" class="u-image u-logo u-image-1" data-image-width="600" data-image-height="100">
                        <img src="images/Logo-UPO-1.png" class="u-logo-image u-logo-image-1">
                    </a>

                </header>
                <section class="u-align-center u-clearfix u-image u-shading u-section-1" src="" data-image-width="256" data-image-height="256" id="sec-1a82">
                    <div class="u-clearfix u-sheet u-valign-middle u-sheet-1">
                        <h1 class="u-text u-text-default u-title u-text-1">Bienvenido</h1>
                        <p class="u-large-text u-text u-text-default u-text-variant u-text-2">Por favor, <?php echo $nombre . " " . $apellidos ?>, consulte los datos relacionados con el certificado digital seleccionado.</p>
                        <a href="#carousel_5081" class="u-btn u-button-style u-palette-2-base u-btn-1">Visualizar información</a>
                    </div>
                </section>
                <section class="u-align-center u-clearfix u-grey-5 u-section-2" id="carousel_5081">
                    <div class="u-clearfix u-sheet u-sheet-1">
                        <div class="u-clearfix u-gutter-12 u-layout-wrap u-layout-wrap-1">
                            <div class="u-layout">
                                <div class="u-layout-row">
                                    <div class="u-container-style u-layout-cell u-size-60 u-layout-cell-1">
                                        <div class="u-container-layout u-container-layout-1">
                                            <div class="u-form u-form-1">
                                                <form action="#" method="post" style="padding: 0px;">
                                                    <div class="sections">
                                                        <label class="label-sections">Datos Personales</label>
                                                        <div class="u-form-group ">
                                                            <label class="u-label u-label-1">Nombre</label>
                                                            <input type="text" value="<?php echo $nombre ?>"  name="name1" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-2">Apellidos</label>
                                                            <input type="text" value="<?php echo $apellidos ?>"  name="apellidos" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">DNI</label>
                                                            <input type="text" value="<?php echo $DNI ?>"  name="dni" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="sections section-non-first">
                                                        <label class="label-sections">Datos Certificado Digital</label>
                                                        <div class="u-form-group ">
                                                            <label class="u-label u-label-1">ID Certificado</label>
                                                            <input type="text" value="<?php echo $numSerie ?>"  name="numSerie" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-2">Autoridad Certificadora</label>
                                                            <input type="text" value="<?php echo $datosServCertCN[0] ?>"  name="datosServCertCN" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">Departamento</label>
                                                            <input type="text" value="<?php echo $datosServCertOU[0] ?>"  name="datosServCertOU" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">Proveedor de Servicios de Certificados</label>
                                                            <input type="text" value="<?php echo $datosServCertO[0] ?>"  name="datosServCertO" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">Pa&iacute;s</label>
                                                            <input type="text" value="<?php echo $datosServCertC[0] ?>"  name="datosServCertC" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>

                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">Fecha de Emisi&oacute;n</label>
                                                            <input type="text" value="<?php echo $fechaEmision ?>"  name="fechaEmision" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">Fecha de Caducidad</label>
                                                            <input type="text" value="<?php echo $fechaCaducidad ?>"  name="fechaCaducidad" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                        <div class="u-form-group">
                                                            <label class="u-label u-label-3">D&iacute;as Restantes de Validez</label>
                                                            <input type="text" value="<?php echo $diasRestantesCaducidad ?>"  name="diasRestantesCaducidad" class="u-border-2 u-border-grey-75 u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" readonly>
                                                        </div>
                                                    </div>




                                                    <div class="u-form-group u-form-name u-form-group-3">
                                                        <label><input type="checkbox" id="cbox1" value="cbox1" required> Confirmo que todos los datos anteriores son correctos.</label><br>
                                                    </div>
                                                    <br/>
                                                    <div class="u-form-group u-form-name u-form-group-3">

                                                        <p>Al seleccionar el bot&oacute;n FIRMAR, se descargar&aacute; el archivo PDF generado (si no se encuentra ya descargado), y para firmarlo, deberá volver a presionar dicho bot&oacute;n.</p>
                                                    </div>
                                                    <div class="u-form-group u-form-name u-form-group-3">

                                                        <label><input type="checkbox" id="cbox2" value="cbox2" required> He le&iacute;do y comprendido el texto anterior.</label><br>
                                                    </div>
                                                    <div class="u-align-right u-form-group u-form-submit u-form-group-4">
                                                        <input type="submit" value="Firmar" name="firmar" class="u-black u-btn u-btn-submit u-button-style u-btn-1">
                                                    </div>

                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


                <footer class="u-align-center u-clearfix u-footer u-grey-80 u-footer" id="sec-d804"><div class="u-clearfix u-sheet u-sheet-1">
                        <p class="u-small-text u-text u-text-variant u-text-1">Universidad Pablo de Olavide<br>Ingeniería Informática en Sistemas de Información<br>Administración Electrónica
                        </p>
                    </div></footer>
            </body>
        </html>
        <?php
    }
}
    