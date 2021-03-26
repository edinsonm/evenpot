<?php require_once('connections/dbsel.php'); ?>
<?php require_once('model_eventos.php'); ?>
<?php include('connections/comun.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();
if(isset($_SESSION['Id_user']))
{ 
$Id_user=$_SESSION['Id_user'];
}
else {
	$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	header('Location: login.php');
	}

$TablaEve = consultar_eventos();
$Rows_TablaRegEve = mysql_num_rows($TablaEve);

$Id_event=$_GET['Id_evento'];

$TablaEveVisto = consultar_visto();
$Rows_TablaEveVisto = mysql_num_rows($TablaEveVisto);

$validate=0;	
if (isset($_POST["MM_insert2"]) && ($_POST["MM_insert2"] == "form2")) 
	{
	$Fbpage = $_POST["Fbpage"];
	$Twitter= $_POST["Twitter"];
	$Dcomprar = $_POST["Dcomprar"];
	$Adicionalf = $_POST["Adicionalf"];
	
	if($_POST['Video']){
	$pagina = $_POST['Video'];
	// Obtener los metatags
	if(OpenGraph::fetch($pagina)){
	$graph = OpenGraph::fetch($pagina);
	//var_dump($graph->keys());
	//var_dump($graph->schema);
	}
	foreach ($graph as $key => $value) {

	if ($key=="image"){ 
	$fotoruta=$value;   
	}
	if ($key=="video"){ 
	$idyt=$value;
	$posyt1=strpos($idyt, "v/");
	$idyt=substr($idyt,$posyt1 + 2, '20');   
	$posyt2=strpos($idyt, "?");
	$idyt = substr($idyt,'0', $posyt2);
	}
	
$param='<object width="500" height="305">
<param name="movie" value="http://www.youtube.com/v/'.$idyt.'?version=3&amp;hl=es_ES">
</param><param name="allowFullScreen" value="true">
</param><param name="allowscriptaccess" value="always">
</param><embed src="http://www.youtube.com/v/'.$idyt.'?version=3&amp;hl=es_ES" type="application/x-shockwave-flash" width="500" height="305" allowscriptaccess="always" allowfullscreen="true">
</embed></object>';
}
}

		//fin if(OpenGraph::fetch($pagina))
		if($_FILES['Foto']['name']){
		    $Foto = $_FILES['Foto']['name'];
			$ruta = "eventimg/".$Foto;
			$directorio="eventimg/";
			$tmp_imagen01 = $_FILES['Foto']['tmp_name'];		
			
			if($Foto!=""){
			move_uploaded_file($tmp_imagen01,$directorio.$Foto);
			}
						
			//$file=$tmp_imagen01;
			$file=$ruta;
			$file_info = getimagesize($ruta);
			$newwidth = 480;
			$newheight = 300;
			
			$ext = explode(".", $Foto);
			
			$ext = strtolower($ext[count($ext) - 1]);
			if ($ext == "jpeg") $ext = "jpg";
		switch ($ext) {
        case "jpg":
                $img = imagecreatefromjpeg($file);
        break;
        case "png":
                $img = imagecreatefrompng($file);
        break;
        case "gif":
                $img = imagecreatefromgif($file);
        break;
}
	
	$imgresize = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($imgresize, $img, 0, 0, 0, 0, $newwidth, $newheight, $file_info[0], $file_info[1]);
	$fotonorm=$directorio.mt_rand().$Foto;
	imagejpeg($imgresize, $fotonorm, 80);	
	
	$newwidth=177;
	$newheight=109;
	$imgresize = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($imgresize, $img, 0, 0, 0, 0, $newwidth, $newheight, $file_info[0], $file_info[1]);
	$File = explode("/", $fotonorm);
	$File = strtolower($File[count($File) - 1]);
	$fotoruta=$directorio."thumb".$File;

	imagejpeg($imgresize, $fotoruta, 100);	
		
	unlink($directorio.$Foto);	
	}
	else $fotonorm=$_POST["Imgprinc"];
		
	$last_Update=date("Y-n-j").date(" H:i:s");
		
	$query = "UPDATE evento SET Imagen='$fotonorm', Video='$param', Video_url='$pagina',  Fbpage='$Fbpage',  Twitter='$Twitter', lst_update='$last_Update', Dcomprar='$Dcomprar', Adicionalf='$Adicionalf' where Id_evento='$Id_event'";
	$result = mysql_query($query);
	if (!$result)
	$msg=$msg."<br>"."No se pudo actualizar el evento";
	else $msg=$msg."Actualizado correctamente";
	}
$Table_eventinfo = consultar_eventoinfo($Id_event);
$row_eventinfo = mysql_fetch_assoc($Table_eventinfo);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php include ('meta.php'); ?>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/cufon-yui.js" type="text/javascript"></script>
<script src="js/cufon-replace.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_400.font.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_600.font.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_650.font.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_700.font.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/levalidate.css" type="text/css" media="screen">
<script type="text/javascript" src="js/fg.menu.js"></script>
    
    <link type="text/css" href="css/fg.menu.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="css/ui.all.css" media="screen" rel="stylesheet" />
    
   <link href="css/menu.css" rel="stylesheet" type="text/css" />
   <script src="js/menujquery.js" type="text/javascript"></script> 
    <script type="text/javascript">    
    $(function(){
    	// BUTTONS
    	$('.fg-button').hover(
    		function(){ $(this).removeClass('ui-state-default').addClass('ui-state-focus'); },
    		function(){ $(this).removeClass('ui-state-focus').addClass('ui-state-default'); }
    	);
    	
    	// MENUS    	
		$('#flat').menu({ 
			content: $('#flat').next().html(), // grab content from this page
			showSpeed: 400 
		});
		
		$('#hierarchy').menu({
			content: $('#hierarchy').next().html(),
			crumbDefaultText: ' '
		});
		
		$('#hierarchybreadcrumb').menu({
			content: $('#hierarchybreadcrumb').next().html(),
			backLink: false
		});
		
		// or from an external source
		$.get('menuContent.html', function(data){ // grab content from another page
			$('#flyout').menu({ content: data, flyOut: true });
		});
    });
    </script>
    <!-- theme switcher button -->
    <script type="text/javascript"> $(function(){ $('<div style="position: absolute; top: 20px; right: 300px;" />').appendTo('body').themeswitcher(); }); </script>
</head>
<body id="page1">
	<!--header -->
	<div id="header">
		<div class="header-bg">
        <?php include ('head.php'); ?>
		</div>
	</div>
	<!--header end-->
	<div id="content">
		<div class="main">
			<div class="indent-top">
				<div class="wrapper">
					<?php include ('leftblockadmin.php'); ?>
					<div class="col-2">
					  <div class="indent_1">
						  
						  <div class="box_1">
								<div class="bottom-right">
								<div class="bottom-left">
								<div class="top-right">
								<div class="top-left">
									<div class="indent">
										<h2>Comprar plan</h2>
                                        <form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>"  class="le-validate" id="example5">
        
          <table class="editor" border="0" cellspacing="0">
  <!--DWLayoutTable-->
<tbody>
<tr>
    <td><img src="images/Plan1.png" alt="" width="318" height="230" /></td>
</tr>
<tr>
<td colspan="2" width="280"><div class="field_container">
 <div align="left"><br><b>Titulo:</b></div>
<input name="Titulo" type="text" id="Video" value="<?php echo $row_eventinfo['Titulo'];?>" size="60" maxlength="100"/>
</td>
</tr>
<tr>
  <td colspan="2" width="280"><div class="field_container">
  <div align="left"><b>Imagen promocional (180 ancho x 100 alto): </b></div>
    <? if($row_eventinfo['Imagen']) {?>
<img src="<?php echo $row_eventinfo['Imagen']; ?>" alt="<?php echo $row_eventinfo['Nombre']; ?>"  width="180" height="100" border="0">	
      <? } ?>                                          
    <input name="Foto" type="file" id="Foto" />
    </div></td>
</tr>


<tr>
<td>
</td>

<td>
<?php echo $msg;?>
</td>
</tr>
       
</table>
<div class="UIButton_Blue"></div>
<div align="right"><span class="UIButton UIButton_Green UIFormButton"> 
 <input value="Modificar" class="UIButton_Text" type="submit"> 
  <input type="hidden" name="MM_insert2" value="form2"> 
<input type="hidden" name="Imgprinc" value="<?php echo $row_eventinfo['Imagen']; ?>">
  </span></div>
	
</span>
         
        <?php  	
		$fecha=date("Y-n-j").date(" H:i:s");
		echo "<input type='hidden' name='Fecha' value='$fecha'>"; 
		?>
         
        
 </form>
										
                                        	
											</div>
										</div>	
									</div>
								</div>
								</div>
								</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		
	
	<!--footer -->
	<?php include ('footer.php'); ?>
	<!--footer end-->
   <script type="text/javascript"> Cufon.now(); </script>
</body>

</html>