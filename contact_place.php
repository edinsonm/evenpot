<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_places.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php

$Email = strtolower(htmlentities($_POST["Correo"], ENT_QUOTES));  
$Interesado = $_POST["name"];
$Mailinteresado = $_POST["email"];
$Mensaje = $_POST["password"];
$Id_sitio = $_POST["Id_sitio"];
$Nom_sitio = $_POST["Nom_sitio"];

$cuerpo=
"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
<head>
<meta http-equiv=Content-Type content='text/html; charset=unicode'>
<meta name=Generator content='Microsoft SafeHTML'>
<body>
<h2 class='ReadMsgSubject'>Haz recibido un mensaje a traves de evenpot</h2>
<br>
<b>".$Interesado."</b> quiere saber mas acerca de tu sitio ".$Nom_sitio.".
<br>
Y te ha dejado el siguiente mensaje:
<br>
<br>
".$Mensaje." 
<br>
<br>
Puedes responder su inquietud al siguiente correo: ".$Mailinteresado."  
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

mail($Email, "Te han contactado - Evenpot", $cuerpo, $headers);

//echo $cuerpo;

$GoTo = "view_placedetail.php?Id_sitio=$Id_sitio";
		
header(sprintf("Location: %s", $GoTo));

?>

  