<? function envia_changemsg($Email, $Nombre, $New)
{
$Email = strtolower(htmlentities($Email, ENT_QUOTES));
$cuerpo=

"<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>
<html>
<head>
<meta http-equiv=Content-Type content='text/html; charset=unicode'>
<meta name=Generator content='Microsoft SafeHTML'>
<?php include ('meta.php'); ?>
</head>
<body>
<h2 class='ReadMsgSubject'>".$Nombre."</h2>
<br>
Reciba un cordial saludo. 
<br>
<br>
Hemos recibido una solicitud de cambio de clave en nuestro sistema (www.evenpot.com).
<br>
<br>
Nombre de usuario: ".$Email." 
<br>
Clave: ".$New."
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
mail($Email, "Cambio de clave - Evenpot", $cuerpo, $headers);
}
?>