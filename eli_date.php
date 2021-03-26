<?php require_once('connections/dbsel.php'); ?>
<?php require_once("model_dates.php");?>
<?php require_once("model_tickets.php");?>
<?php
	mysql_select_db($database_dbsel, $dbsel);

session_start(); 
if(isset($_SESSION['Id_user']))
{ 
$Id_user=$_SESSION['Id_user'];
}
else {
	$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	header('Location: login.php');
	}

	
$Id_datexevento=$_GET['Id_de'];
$Id_evento=$_GET['Id_evento'];
		
		if (eliminar_date($Id_datexevento) && eliminar_aforodate($Id_datexevento)) {
		$deleteGoTo = "new_date.php?Id_evento=$Id_evento";
		
  		header(sprintf("Location: %s", $deleteGoTo));
  
		}else{
		echo "Error de eliminacion";
			}	
		
			?>