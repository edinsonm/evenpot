<?
error_reporting(0);
include_once('mail_newcomment.php');
include_once('model_comments.php');
include_once('time_stamp.php');

//$idu=4; // id del usuario

if(isset($_POST['content_txt']))
{
	if(mb_strlen($_POST['content_txt']) < 1 || mb_strlen($_POST['content_txt'])>140)
	die("0");
	
	$time=date("Y-n-j").date(" H:i:s");
	$update=utf8_decode($_POST['content_txt']); 
	$idu = $_POST['Id_user'];
	$id_evento = $_POST['Id_evento']; //$_POST['Id_evento'];
	$comentario=stripslashes(htmlentities(strtolower($update))); 
	//$img = imagen($idu);
	send_mailcomment($idu, $comentario, $id_evento);
	$idcom = insertar_comentario($idu, $comentario, $id_evento, $time);
 ?>
<div class="stbody" id="stbody<? echo $idcom; ?>">
    <div class="sttimg"><img src="images/<? echo $img; ?>" class='big_face'/></div> 
    <div class="sttext"><a class="stdelete" href="#" id="<? echo $idcom;?>" title="Borrar comentario">X</a>
    <? echo $comentario;?>
   	<div class="sttime"><? time_stamp($time);?></div> 
</div> 

<?
}
?>

 <?php
error_reporting(0);
include_once('include/database.php');

if(isset($_POST['id_comentario']))
{
	$comentario_id=$_POST['id_comentario'];
	$data=eliminar_comentario($comentario_id);
	echo $data;
}
?>