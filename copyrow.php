<?php require_once('connections/dbsel.php'); ?>
<?php
mysql_select_db($database_dbsel, $dbsel);

if($_POST['MM_insert'] == 'frmaddteam')
{
$Id_evento=664;
$Row=$_POST['Row'];
$Copy=$_POST['Copy'];

$query = "SELECT * FROM seats WHERE Id_event='$Id_evento' AND Row='$Row' ORDER BY Id_seat";
		$result = mysql_query($query);	
		
while ($Row_fec_act = mysql_fetch_assoc($result)){
		$Seat = $Row_fec_act['Seat'];
		$State = $Row_fec_act['State'];
		$queryp = "INSERT INTO seats (Id_event, Row, Seat, State) VALUES ('$Id_evento', '$Copy', '$Seat', '$State')";
		$resultp = mysql_query($queryp);	
	}
}
?>

<form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>">
Fila<input name="Row" type="text" id="Nombre" value="" size="18" maxlength="45"/>
<br>
Copiar en:<input name="Copy" type="text" id="Copy" value="" size="18" maxlength="45"/>
<input type="submit" value="Agregar" class="btn btn-danger" />
<input type="hidden" name="MM_insert" value="frmaddteam"> 
</form>