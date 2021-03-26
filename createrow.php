<?php require_once('connections/dbsel.php'); ?>
<?php
mysql_select_db($database_dbsel, $dbsel);

if($_POST['MM_insert'] == 'frmaddteam')
{
$Id_evento=664;
$Row=$_POST['Row'];
$Seats=$_POST['Seats'];

for ($i = 1; $i <= $Seats; $i++) {
		$query = "INSERT INTO seats (Id_event, Row, Seat, State) VALUES ('$Id_evento', '$Row', '$i', 'A')";
		$result = mysql_query($query);	
	}
}
?>

<form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>">
Fila<input name="Row" type="text" id="Nombre" value="" size="18" maxlength="45"/>
<br>
Sillas<input name="Seats" type="text" id="Seats" value="" size="18" maxlength="45"/>
<input type="submit" value="Agregar" class="btn btn-danger" />
<input type="hidden" name="MM_insert" value="frmaddteam"> 
</form>