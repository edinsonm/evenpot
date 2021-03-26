<?php require_once('connections/dbsel.php'); ?>
<?php require_once('model_eventos.php'); ?>
<?php require_once('model_user.php'); ?>
<?php include('connections/comun.php'); ?>
<?php
session_start();
if(isset($_SESSION['Id_user']))
{ 
$Id_user=$_SESSION['Id_user'];
}
else {
	$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	header('Location: login.php');
	}

$Id_evento=$_GET['Id_evento'];

$TablaTeamEveUs = consultar_ownteameventus($Id_evento, $Id_user);
$Rows_TeamEveUs = mysql_num_rows($TablaTeamEveUs);
if($Rows_TeamEveUs) {
				
$Code=$_GET['Code'];
$validate=0;

$TablaAsis = consultar_codeuser($Id_evento, $Code);
$row_TablaAsis = mysql_fetch_assoc($TablaAsis);
$Rows_TablaAsis = mysql_num_rows($TablaAsis);
	if(($Rows_TablaAsis>0) && ($row_TablaAsis['Ingreso']==1))
	{$msg="Codigo ya fue ingresado";
	$validate=1;}
	else if (($Rows_TablaAsis>0) && ($row_TablaAsis['Ingreso']==0))
	{$msg="Bienvenido: ".$row_TablaAsis['Nom_asistente'];}
	else if($Rows_TablaAsis<1)
	{$msg="Codigo no existe";
	$validate=1;}

if($validate==0){
    $redimido=date("Y-n-j").date(" H:i:s");
  	$insertSQL = 'UPDATE asisxevento SET Ingreso=1, Date_redem=\''.$redimido.'\' WHERE Id_evento=\''.$Id_evento.'\' and Cod_ticket=\''.$Code.'\'';
	$Result1= mysql_query($insertSQL)or die(mysql_error());
}
}
else $msg="No tiene permisos para validar";
	
?>

<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="es"> <!--<![endif]-->

<!-- BEGIN HEAD-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<link href="/css2/base.css" rel="stylesheet">
</head>
    
<body>

<div class="container margin_60">
	<div class="row">
  	
	<div class="col-lg-6">
	
	<div class="share">
 <div class="panel panel-default">
                      
	<div class="panel-body" align="center">
		<h3><? echo $msg;?></h3>
	</div>
</div>
</div>
	
<button onclick="closeCurrentWindow()" class="btn_full">Cerrar</button>
</div>
</div>
</div>

<script type="text/javascript">
function closeCurrentWindow()
{
  window.close();
}
</script>
</body>
</html>
