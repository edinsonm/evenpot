<?php require_once('connections/dbsel.php'); ?>
<?php require_once("model_eventos.php");?>
<?php require_once("model_dates.php");?>
<?php require_once("model_tickets.php");?>
<?php include_once('model_user.php'); ?>
<?php include('connections/comun.php'); ?>
<?php
session_start();
if(isset($_SESSION['Id_user']) )
{ 
$Id_user=$_SESSION['Id_user'];
}
//mysql_select_db($database_dbsel, $dbsel);
$TablaUser = consultar_user($Id_user);
$row_User = mysql_fetch_assoc($TablaUser);

//$Id_asisxevento=$_GET['Id_asisxevento'];
$Id_asisxevento = $_GET['Id_asisxevento'];

$TablaAsisEve = get_myticket($Id_user, $Id_asisxevento);
$row_AsisEve = mysql_fetch_assoc($TablaAsisEve);
$refVenta=$row_AsisEve['Ref_POL'];
$Id_evento=$row_AsisEve['Id_evento'];
/*
if($row_AsisEve){
$Nombre=$row_AsisEve['Nom_asistente'];
$Correo=$row_AsisEve['Correo'];
$Code=$row_AsisEve['Cod_ticket'];
*/
require_once('fpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Evenpot');
$pdf->SetTitle('Entrada');
$pdf->SetSubject('Entrada Evento');
$pdf->SetKeywords('Entrada, Evento, Evenpot');

$TablaUltTik = consultar_myticketbuy($Id_evento, $Id_user, $refVenta);
			while ($row_TablaUltTik = mysql_fetch_assoc($TablaUltTik)){
			$Id_asisxevento = $row_TablaUltTik['Id_asisxevento'];
			
$TablaAsisEve = get_myticket($Id_user, $Id_asisxevento);
$row_AsisEve = mysql_fetch_assoc($TablaAsisEve);

//if($row_AsisEve){
$Nombre=$row_AsisEve['Nom_asistente'];
$Correo=$row_AsisEve['Correo'];
$Code=$row_AsisEve['Cod_ticket'];
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $row_AsisEve['Nombre_ev'], 'www.evenpot.com');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// NOTE: 2D barcode algorithms must be implemented on 2dbarcode.php class file.

// set font
$pdf->SetFont('helvetica', '', 14);

// add a page
$pdf->AddPage();

// print a message
$pdf->Text(20, 25, 'Nombre:');
$pdf->Text(20, 25, 'Nombre:');
$pdf->Text(60, 25, $Nombre);

$pdf->Text(20, 32, 'e-mail:');
$pdf->Text(20, 32, 'e-mail:');
$pdf->Text(60, 32, $Correo);


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// set style for barcode
$style = array(
    'border' => 1,
    'vpadding' => 'auto',
    'hpadding' => 'auto',
    'fgcolor' => array(0,0,0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

// -------------------------------------------------------------------
// PDF417 (ISO/IEC 15438:2006)

/*

 The $type parameter can be simple 'PDF417' or 'PDF417' followed by a
 number of comma-separated options:

 'PDF417,a,e,t,s,f,o0,o1,o2,o3,o4,o5,o6'

 Possible options are:

     a  = aspect ratio (width/height);
     e  = error correction level (0-8);

     Macro Control Block options:

     t  = total number of macro segments;
     s  = macro segment index (0-99998);
     f  = file ID;
     o0 = File Name (text);
     o1 = Segment Count (numeric);
     o2 = Time Stamp (numeric);
     o3 = Sender (text);
     o4 = Addressee (text);
     o5 = File Size (numeric);
     o6 = Checksum (numeric).

 Parameters t, s and f are required for a Macro Control Block, all other parametrs are optional.
 To use a comma character ',' on text options, replace it with the character 255: "\xff".

*/
$pdf->Text(43, 44, $Code);
$pdf->write2DBarcode($Code, 'PDF417,5,5', 20, 50, 70, 30, $style, 'N');

$pdf->Image($row_AsisEve['Imagen'], 145 ,20, 50, 35, "JPEG" ,"http://www.evenpot.com/");

$pdf->Text(100, 56, 'Fecha:');
$pdf->Text(100, 56, 'Fecha:');
$pdf->Text(118, 56, $row_AsisEve['Fecha']);

$pdf->Text(150, 56, 'Hora:');
$pdf->Text(150, 56, 'Hora:');
$pdf->Text(164, 56, $row_AsisEve['Hora']);

$pdf->Text(100, 62, 'Lugar:');
$pdf->Text(100, 62, 'Lugar:');
$pdf->Text(118, 62, $row_AsisEve['Lugar']);

$pdf->SetXY(20, 70);
// Move to 8 cm to the right
$pdf->Cell(80);
// Centered text in a framed 20*10 mm cell and line break
$pdf->SetFillColor(200);
$pdf->Cell(0,10,'Entrada: '.$row_AsisEve['Nombre_tkt'],0,1,'C',true,'','',true,50,30);


$pdf->SetFont('helvetica', '', 10);
$txt = "Evite imprimir esta entrada presentandola desde su dispositivo movil.\n";
$pdf->MultiCell(70, 50, $txt, 0, 'J', false, 1, 20, 82, true, 0, false, true, 0, 'T', false);

$pdf->Text(100, 105, 'Organiza');
$pdf->Text(100, 105, 'Organiza');
$pdf->Text(120, 105, $row_AsisEve['Organiza']);

$pdf->Image("fpdf/Logo.jpg" , 20 ,105, 25 , 6 , "JPEG" ,"http://www.evenpot.com/");

$pdf->Line(15, 113, 195, 113);

}
// Filename that will be used for the file as the attachment
$cad1= substr(str_replace(' ', '', $row_AsisEve['Nombre_ev']),0,12);
$cad2 = substr(str_replace(' ', '', $row_AsisEve['Nombre']),0,8);
$cad3 = substr(md5(uniqid(rand(), true)),0, 3);

$fileatt_name = $cad1.$cad2.$cad3.'.pdf';
//$fileatt_name = "test.pdf";
//$dir='pdffile/';
// save pdf in directory
//$pdf ->Output($dir.$fileatt_name);
//....................
$pdf ->Output($fileatt_name, 'D');
//$pdf ->Output($fileatt_name);
//....................
$data = $pdf->Output("", "S");
//..................
$email_from = "Evenpot <info@evenpot.com>"; // Who the email is from
$email_subject = "Entrada a tu evento"; // The Subject of the email
$email_to = $row_User['Correo']; // Who the email is to

$semi_rand = md5(time());
$data = chunk_split(base64_encode($data));

$fileatt_type = "application/pdf"; // File Type
$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

// set header ........................
$headers = "From: ".$email_from;
$headers .= "\nMIME-Version: 1.0\n" .
"Content-Type: multipart/mixed;\n" .
" boundary=\"{$mime_boundary}\"";

// set email message......................
$email_message = "Felicidades! ";
$email_message .= "Haz adquirido la entrada a tu evento.<br><br><br>";// Message that the email has in it
$email_message .= "Equipo Evenpot.<br>";// Message that the email has in it
$email_message .= "This is a multi-part message in MIME format.\n\n" .
"--{$mime_boundary}\n" .
"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" .
$email_message .= "\n\n";
$email_message .= "--{$mime_boundary}\n" .
"Content-Type: {$fileatt_type};\n" .
" name=\"{$fileatt_name}\"\n" .
"Content-Disposition: attachment;\n" .
" filename=\"{$fileatt_name}\"\n" .
"Content-Transfer-Encoding: base64\n\n" .
$data .= "\n\n" .
"--{$mime_boundary}--\n";

$sent = @mail($email_to, $email_subject, $email_message, $headers);
//..................
?>

<SCRIPT LANGUAGE="javascript">
    location.href = "view_eventticket.php?Id_asisxevento=41&Id_evento=<? echo $Id_evento; ?>";
</SCRIPT>
<? // } else { ?>
<SCRIPT LANGUAGE="javascript">
    location.href = "index.php";
</SCRIPT>

<? // } ?>