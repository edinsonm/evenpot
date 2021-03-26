<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_comments.php'); ?>
<?php include_once('time_stamp.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();
if(isset($_SESSION['Id_user']))
{ 
$Id_user=$_SESSION['Id_user'];
$Origen=$_GET['Origen'];
}
$Id_friend=$_GET['Id_user'];
?>

<!-- Message. Default to the left -->
					<?
					$TablaComments = view_message($Id_friend, $Id_user);
								$Rows_TablaComment = mysql_num_rows($TablaComments);
								if($Rows_TablaComment>0){
									while ($row_TablaComment = mysql_fetch_assoc($TablaComments)){ 				
										$comentario_id = $row_TablaComment['Id_comment'];
										$usuario_id = $row_TablaComment['Id_user'];
										if ($row_TablaComment['Nick'])
										$Nick= $row_TablaComment['Nick'];
										else $Nick= $row_TablaComment['Nombre'];
										$comentario = stripslashes(htmlentities($row_TablaComment['Message']));
										$imagen = $row_TablaComment['Photo_user'];
										$meses = array('','Ene.','Feb.','Mar.','Abr.','May.','Jun.','Jul.','Ago.','Sep.','Oct.','Nov.','Dic.');
										$mesini = $meses[date('n', strtotime($row_TablaComment['Fecha']))];
										if(substr($row_TablaComment['Fecha'], 8, 1)!=0)
										$diaini = substr($row_TablaComment['Fecha'], 8, 2);
										else $diaini = substr($row_TablaComment['Fecha'], 9, 1);
										$anio = date("Y",strtotime($row_TablaComment['Fecha']));
										$hora = date("H:i A", strtotime($row_TablaComment['Fecha']));
								 if ($usuario_id==$Id_user){
					?>				 
                    <!-- Message to the right -->
                    <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right"><? echo $Nick; ?></span>
                    
					   <span class="direct-chat-timestamp pull-left"><? echo $diaini." ".$mesini." ".$anio." ".$hora;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<? echo $imagen; ?>" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        <? echo $comentario; ?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
					<? } else { ?>	
                    <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><? echo $Nick; ?></span>
                        <span class="direct-chat-timestamp pull-right"><? echo $diaini." ".$mesini." ".$anio." ".$hora;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<? echo $imagen; ?>" alt="message user image">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                        <? echo $comentario; ?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
					<? 		} 
						}
					}?>
                    <!-- /.direct-chat-msg -->