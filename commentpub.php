<?php require_once('connections/dbsel.php'); ?>
<?php
mysql_select_db($database_dbsel, $dbsel);
session_start(); 
if(isset($_SESSION['Id_user']))
{ 
$Id_user = $_SESSION['Id_user']; 
 
$ConsCom = "SELECT *
			FROM user 
			WHERE Id_user = '$Id_user'";
$result = mysql_query($ConsCom);
$Rows_TablaConsCom = mysql_num_rows($result);
$row_ConsCom = mysql_fetch_assoc($result);

$Id_emotionxuser = $_POST['Id_foreign'];
$textcmnt = $_POST['comment'];
date_default_timezone_set("America/Bogota");
$Creado = date("Y-n-j").date(" H:i:s");

	    $Likes=$Likes+1;
		$query = "INSERT INTO emotionxcmnt (Id_emotionxuser, Comment, Id_user, Datetime) VALUES ('$Id_emotionxuser', '$textcmnt', '$Id_user', '$Creado')";
		$result = mysql_query($query);

		//echo $ConsCom;
		echo '<div class="box-comment">
                <!-- User image -->
				<img class="img-circle img-sm" src="../'.  $row_ConsCom['Photo_user'].'" alt="User Image">
                <div class="comment-text">
                      <span class="username">
                        '.$row_ConsCom['Nombre'].'
                        <span class="text-muted pull-right">Ahora</span>
                      </span><!-- /.username -->
                  '.$textcmnt.'
                </div>
                <!-- /.comment-text -->
              </div>';
		
		$query = "SELECT el.*, eu.Id_user as User_owner
		FROM emotionxcmnt el, emotionxuser eu
		WHERE el.Id_emotionxuser = '$Id_emotionxuser' AND el.Id_emotionxuser = eu.Id_emotionxuser AND el.Id_user = '$Id_user'";
		$result = mysql_query($query);
		$row_Emolike = mysql_fetch_assoc($result);
		$User_owner = $row_Emolike['User_owner'];
		$query = "INSERT INTO notificaxuser (Tipo_not, User_owner, Id_user, Id_foreign, Fecha) VALUES ('2', '$User_owner', '$Id_user', '$Id_emotionxuser', '$Creado')";
		$result = mysql_query($query);

}
?>