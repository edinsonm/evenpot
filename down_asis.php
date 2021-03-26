<?php require_once('connections/dbsel.php'); ?>
<?php require_once('model_eventos.php'); ?>
<?php include('connections/comun.php'); ?>
<?php 
header("Pragma: public");
header("Expires: 0");
ob_start('ob_gzhandler');
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename="filename.csv"');
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");

$dbHost     = 'localhost';
$dbUsername = 'evenpot14root';
$dbPassword = 'evenpot2011abc'; 
$dbName     = 'evenpot_sitio';

//Create connection and select DB
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if($db->connect_error){
    die("Unable to connect database: " . $db->connect_error);
}
$fp = fopen($filename, "w");
$Id_evento=664;
$query="SELECT ae.Id_asisxevento, ae.Id_evento, ae.Id_user, ae.Id_ticketxevent, te.Nombre_tkt, ae.Cod_ticket, u.Correo, CONCAT(u.Nombre,' ',u.Apellidos) AS Nom_asistente, 
de.Fecha, de.Hora, ae.Date_redem, ae.Ingreso, CONCAT(u.Type_ID,' ',u.Num_ID) AS Identificacion, u.Birthday, u.Gender, u.Institution, u.Profesion, u.Telefono, u.Pais, u.Ciudad_nac
FROM asisxevento ae, ticketxevento te, datexevento de, user u
WHERE ae.Id_evento='$Id_evento' 
AND ae.Id_ticketxevent=te.Id_ticketxevent
AND ae.Id_datexevento=de.Id_datexevento
AND u.Id_user = ae.Id_user
ORDER BY ae.Ingreso DESC, ae.Id_asisxevento ASC";

$TablaReg = consultar_controlreg($Id_event);
$Rows_TablaReg = mysql_fetch_assoc($TablaReg);

//$query = consultar_asistentes($Id_event);
$query = $db->query($query);
if($query->num_rows > 0){
    $delimiter = ",";
    $filename = "asistentes_" . date('Y-m-d') . ".csv";
    
    //create a file pointer
    $f = fopen('php://memory', 'w');

    $fields = array('Nombres', 'Correo', 'Identificacion', 'Fecha_nac', 'Genero', 'Institucion', 'Cargo', 'Telefono', 'Asistencia');
    fputcsv($f, $fields, $delimiter);
    
    //output each row of the data, format line as csv and write to file pointer
    while($row = $query->fetch_assoc()){
        //$status = ($row['status'] == '1')?'Active':'Inactive';
	if ($row['Ingreso']=='0')  $Asistencia = 'No'; else $Asistencia = 'Si';
        $lineData = array($row['Nom_asistente'], $row['Correo'], $row['Identificacion'], $row['Birthday'], $row['Gender'], $row['Institution'], $row['Profesion'], $row['Telefono'], $row['Pais'], $row['Ciudad_nac'], $Asistencia);
		fputcsv($f, $lineData, $delimiter);
    }
    
    //move back to beginning of file
    fseek($f, 0);
    
    //set headers to download file rather than displayed
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    
    //output all remaining data on a file pointer
    fpassthru($f);
}
exit;

?>