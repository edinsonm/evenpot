<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('mail_register.php'); ?>
<?php require_once('mail_sender.php'); ?>
<?php include_once('time_stamp.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>

<?php
session_start();
$showinfo=0;
if ($_POST["send"]=="on")
{
if ($_POST["Ticket"]==""){
	$message = "Debe escribir el codigo del ticket";
	}
else{
	$Cod_ticket=$_POST["Ticket"];
	if (validar_ticket($Cod_ticket)!=false){
		if (mysql_num_rows(validar_ticket($Cod_ticket)) >0) {
		$row_AsisEve = mysql_fetch_assoc(validar_ticket($Cod_ticket));

		$Nombre=$row_AsisEve['Nom_asistente'];
		$Code=$row_AsisEve['Cod_ticket'];
		$Imagen = substr ($row_AsisEve['Imagen'], strpos($row_AsisEve['Imagen'],"/"));
		$message = "Codigo Valido";
		$showinfo=1;
		}
	else $message = "El codigo de entrada no existe";
	}
	else $message = "Ocurrio un error, por favor intente nuevamente";
}
	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Validar entrada | evenpot </title>
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
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="account/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="account/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="account/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="account/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="account/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
	
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <link rel='stylesheet' href='css/jAlert.css'>
</head>

<body class="layout-top-nav skin-green">
<div class="wrapper">
    <!-- Mobile menu overlay mask -->
	
  <header class="main-header">
	<?php include ('navbar.php'); ?>  
  </header>
  <!-- Left side column. contains the logo and sidebar -->
<!-- /header container-->

  <div class="content-wrapper">
      <section class="content">
<div class="row">
    <!-- /.col -->  
   <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">

		<div class="box box-primary">
		               <div class="box-header">
                        <h3 class="box-title">
                          	Validar Entrada
						</h3>
                        </div>
                <div class="panel-body">
                            <div class="body"> 
			<form method="post" action="<?php echo $editFormAction; ?>">
					<div class="form-group">
						<input type="Text" name="Ticket" id="Correo" class="form-control input-lg"  value="" placeholder="Codigo Entrada" tabindex="1">
					</div>

<?php echo $message; ?> 

			<div class="row">
				<div class="col-md-6"><input type="submit" value="Validar" class="btn btn-primary" tabindex="2"></div>				
			</div>
        

<input name="send" type="hidden" value="on" />
<?php  	
		$fecha=date("Y-n-j").date(" H:i:s");
		echo "<input type='hidden' name='Fecha' value='$fecha'>"; 
		?>
        </form>
			<p>Dudas? e-mail: <a href="#">info@evenpot.com</a></p>
		  </div>  
    </div>
	</div>
<? if ($showinfo==1) { ?>
	<div class="panel panel-default">	
	<table class="table">
  <tr>
  <td colspan="2" align="center">
      <? if($row_eventinfo['Imagen']) {?>
	<img itemprop="image" src="/<?php echo $row_eventinfo['Imagen']; ?>" alt="<?php echo $row_eventinfo['Nombre']; ?>" class="img-responsive"  width="200" height="70">
                                                
    <? } else {?>	<img src="/eventimg/iconsport.jpg" alt="" class="img-responsive" width="280" height="120">                                            
    <? } ?>
  </td>
  </tr>
  <tr>
    <td><b>Nombre:&nbsp;</b></td>
    <td>
	 <?php echo $row_AsisEve["Nombre"];?>
   
    </td>
  </tr>
  <tr>
    <td><b>Ciudad:&nbsp;</b></td>
    <td>

	<?php 
	echo $row_AsisEve['Ciudad']." / ";
	if ($row_AsisEve["Pais"]=='54') echo Argentina;
    else if ($row_AsisEve["Pais"]=='55') echo Brasil;
    else if ($row_AsisEve["Pais"]=='56') echo Chile;
    else if ($row_AsisEve["Pais"]=='57') echo Colombia;
    else if ($row_AsisEve["Pais"]=='52') echo Mexico;
    else if ($row_AsisEve["Pais"]=='595') echo Paraguay;
    else if ($row_AsisEve["Pais"]=='51') echo Peru;
    else if ($row_AsisEve["Pais"]=='598') echo Uruguay;
    else if ($row_AsisEve["Pais"]=='58') echo Venezuela;
	  ?>
	
  </td>
  </tr>
  
  <tr>
    <td><b>Lugar:&nbsp;</b></td>
    <td>
	<?php echo $row_AsisEve['Lugar'];?>
	  </td>
  </tr>

  <tr>
    <td><b>Fecha:&nbsp;</b></td>
    <td>
	<?php echo $row_AsisEve['Fecha'];?>
	  </td>
  </tr>

  <tr>
    <td><b>Entrada:&nbsp;</b></td>
    <td>
	<?php echo $row_AsisEve['Nombre_tkt'];?>
	  </td>
  </tr>

  <tr>
    <td><b>Estado:&nbsp;</b></td>
    <td>
	<?php if ($row_AsisEve['Ingreso']==1) echo "<p class='text-red'>Entrada ya fue usada</p>";
	else echo "<p class='text-green'>Entrada sin usar</p>";?>
	  </td>
  </tr>
  
</table>
</div>
	<? } ?>
	</div>
</div>
 </section>  
</div>

<?php include ('footer.php'); ?>    

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="account/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="account/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="account/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="account/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="account/dist/js/demo.js"></script>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	
</body>
</html>