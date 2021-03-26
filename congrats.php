<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('mail_register.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();
$Id_user = $_SESSION["Id_user"];
$query_us = "SELECT * FROM user WHERE Id_user='$Id_user'";
$row_dataus = mysql_fetch_assoc(mysql_query($query_us));
$_POST['Nombre'] = $row_dataus['Nombre'];
$_POST["Apellido"]= $row_dataus['Apellidos'];
$_POST["Email"]= $row_dataus['Correo'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Concurso U2 | evenpot</title>
<?php include ('meta.php'); ?>
	<!-- CSS -->
    <link href="/css2/base.css" rel="stylesheet">
    
    <!-- CSS -->
	<link href="/css2/date_time_picker.css" rel="stylesheet">
	<link rel="stylesheet" href="/css/blueimp-gallery.css">
	<link rel="stylesheet" href="/css/blueimp-gallery-indicator.css">
	<link rel="stylesheet" href="admin/assets/css/bootstrap-fileupload.min.css" />
	<link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css" rel="stylesheet">
		
     <!-- Google web fonts -->
	<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Gochi+Hand' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
    
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

<link href="/css/star-rating.css" rel="stylesheet">
<link rel='stylesheet' href='/css/jAlert.css'>
</head>
<body>	
<? if(isset($_SESSION['Id_user']))
{ ?>

    <div class="layer"></div>
    <!-- Mobile menu overlay mask -->
	
    <header class="sticky"> 
    <?php include ('head_menu.php'); ?>
	</header>
	
<div class="container margin_60">
<div class="inner" style="min-height:300px;">
<div class="row">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
<? if ($msg!=""){ ?>
<div class="alert alert-dismissible alert-warning">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <p><? echo $msg; ?></p>
</div>
<? } ?>

	 <hr class="colorgraph">		
		<b><h2 class="heading-desc" align="center">!Felicidades <? echo $_POST['Nombre'];?>!</h2></b>
			<br>
			<h3 class="heading-desc" align="center">Ya estas participando</h3>

			 <hr class="colorgraph">

	</div>
</div>
</div>
<!-- Modal -->
</div>
<br>

<?php include ('footer.php'); ?>    
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
<script src="js/register.js"></script>
<? }
else{
$redirect="concursoU2.php";
header('Location: '.$redirect);
} ?>
</body>
</html>