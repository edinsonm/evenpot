<?
require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');

class PDF extends FPDF
{
//Cabecera de página
function Header()
{
//Logo

//Arial bold 15
$this->SetFont('Arial','B',15);
//Movernos a la derecha
$this->Cell(80);
//Título
$this->Cell(40,10,'Titulo del archivo',0,0,'C');
//Salto de línea
$this->Ln(20);
}

//Pie de página
function Footer()
{
QRcode::png('El texto que deseo almacenar en código QR', 'mi_qr.png');
$this->Image("mi_qr.png" , 145 ,60, 40 , 40 , "PNG");
$this->Image("images/logo_pdf_ES.png" , 20 ,250, 70 , 17 , "PNG" ,"http://www.plannergo.com");
//Posición: a 1,5 cm del final
$this->SetY(-15);
//Arial italic 8
$this->SetFont('Arial','I',8);
//Número de página
$this->Cell(0,10,'Desarrollado por Microware',0,0,C);
}
}
//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();

//Primera página
$pdf->AddPage();
$pdf->SetFont('Arial','',15);
$pdf->Cell(190,20, 'Por favor no imprima este documento si el organizador del evento no lo requiere',0,2);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(120,8, 'Edinson Morales');
$pdf->Cell(70,60,'QR',1,0);
$pdf->Cell(70,4, '',0,1);
$pdf->SetFont('Arial','',10);
$pdf->Cell(120,8, 'edinson_morales@hotmail.com',0,1);
$pdf->Cell(120,20, 'Lugar',1,1);
$pdf->Cell(45,20, 'Dia',1);
$pdf->Cell(45,20, 'Hora',1,1);
$pdf->Cell(70,20, 'Nota',1);

$pdf->Output();
?>