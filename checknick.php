<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>

<?php
 
if(!empty($_POST["nick"])) {
  $query = "SELECT * FROM user WHERE Nick='".$_POST["nick"]."' AND Id_user!='".$_POST["id"]."'";
  //$user_count = $db_handle->numRows($query);
  $resultq = mysql_query($query);
  $user_count = mysql_num_rows($resultq);
  if($user_count>0) {
      echo "<span style='font-weight:bold;color:red;'>Nick no disponible.</span>";
  }else{
      echo "<span style='font-weight:bold;color:green;'>Disponible.</span>";
  }
}
?>