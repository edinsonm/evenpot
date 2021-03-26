<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();

$Nombres = strtolower(htmlentities($_POST["Nombres"], ENT_QUOTES));    
$Email = strtolower(htmlentities($_POST["Email"], ENT_QUOTES));    
$Mensaje = $_POST["Mensaje"];  
$Id_sitio = $_POST["Id_sitio"];  
 
$cuerpo=
"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
<head>
<meta http-equiv=Content-Type content='text/html; charset=unicode'>
<meta name=Generator content='Microsoft SafeHTML'>
<body>
<h2 class='ReadMsgSubject'>Tienes un nuevo mensaje para tu sitio: ".$row."</h2>
<br>
".$Nombres." 
<br>
<br>
Te escribio lo siguiente:
<br>
<br>
".$Mensaje." 
<br>
<br>
<br>
Consideramos importante anotar que la informaci&oacute;n relacionada en la presente comunicaci&oacute;n no debe ser conocida por terceras personas con el fin de garantizar la confidencialidad y seguridad de la misma.
<br>
<br>
Cordialmente,
<br>
Equipo de Evenpot

</body></html>";

$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

//dirección del remitente
$headers .= "From: Evenpot <info@evenpot.com>\r\n";

//dirección de respuesta, si queremos que sea distinta que la del remitente
$headers .= "Reply-To: info@evenpot.com\r\n";

//ruta del mensaje desde origen a destino
$headers .= "Return-path: info@evenpot.com\r\n";

mail($Email, "Nuevo mensaje - Evenpot", $cuerpo, $headers);

?>
<SCRIPT LANGUAGE="javascript">
location.href = "view_placedetail.php?Id_sitio=<? echo $Id_sitio;?>";
</SCRIPT>