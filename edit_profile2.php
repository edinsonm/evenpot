<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_levels.php'); ?>
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
	
// verificamos si se han enviado ya las variables necesarias.
$validate=0;	
if (isset($_POST["MM_insert2"]) && ($_POST["MM_insert2"] == "form2")) 
	{
	if(!$_POST["Nick"])
	{$msg=$msg."Escoja su nick<br>";
	$validate=$validate+1;}
	
	if($_POST["Nombre"]=="")
	{$msg=$msg."Escriba su nombre<br>";
	$validate=$validate+1;}
	
	if($_POST["Email"]=="")
	{$msg=$msg."Escriba su e-mail<br>";
	$validate=$validate+1;}
	
	if($_POST["Location"]=="")
	{$msg=$msg."Escriba su Ciudad<br>";
	$validate=$validate+1;}
	
	if($_POST["edad"]=="0")
	{$msg=$msg."Escriba su Edad<br>";
	$validate=$validate+1;}
	
	if($_POST["genero"]=="0")
	{$msg=$msg."Escriba su Genero<br>";
	$validate=$validate+1;}
	
	
	$Email = $_POST["Email"];
    $Nick = $_POST["Nick"];
	$Nombre = $_POST["Nombre"];
	$Apellido = $_POST["Apellido"];
	$Location = $_POST["Location"];
	$Edad = $_POST["edad"];
	$Genero = $_POST["genero"];
	$Perfil = $_POST["Perfil"];
	
	if($_FILES['Foto']['name']){
		    $Foto = $_FILES['Foto']['name'];
			$ruta = "images/profile/".$Foto;
			$directorio="images/profile/";
			$tmp_imagen01 = $_FILES['Foto']['tmp_name'];		
			
			if($Foto!=""){
			move_uploaded_file($tmp_imagen01,$directorio.$Foto);
			}
			
			//$file=$tmp_imagen01;
			$file=$ruta;
			$file_info = getimagesize($ruta);
			$newwidth = 102;
			$newheight = 102;
			
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
	
	$newwidth=70;
	$newheight=70;
	$imgresize = imagecreatetruecolor($newwidth, $newheight);
	imagecopyresampled($imgresize, $img, 0, 0, 0, 0, $newwidth, $newheight, $file_info[0], $file_info[1]);
	$File = explode("/", $fotonorm);
	$File = strtolower($File[count($File) - 1]);
	$fotoruta=$directorio."thumb".$File;

	imagejpeg($imgresize, $fotoruta, 100);	
		
	unlink($directorio.$Foto);		
		}
		else $fotonorm=$_POST["Imgprinc"];
		
		if($validate==0)
		{
	// Comprobamos si la cuenta de correo ya existe
	$checkuser = mysql_query('SELECT Correo FROM user WHERE Correo=\''.$Email.'\' and Id_user!=\''.$Id_user.'\'');
    $username_exist = mysql_num_rows($checkuser);
           		if ($username_exist>0) {
				$msg = $msg."La cuenta de correo esta ya en uso<br>"; 
            }
        else{
                $query = "UPDATE user set Nombre='$Nombre', Apellidos='$Apellido', Correo='$Email', Nick='$Nick', Photo_user='$fotonorm', Location='$Location', Gender='$Genero', Age_range='$Edad', Type_user='$Perfil' where Id_user='$Id_user'";
				mysql_query($query) or die(mysql_error());	
							
			?>
        	<SCRIPT LANGUAGE="javascript">
            location.href = "edit_profile.php";
            </SCRIPT>
 		<? }
	} 
}
$TablaUs = consultar_user($Id_user);
$row_TablaUs = mysql_fetch_assoc($TablaUs);
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
           <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/styles.css" rel="stylesheet">
</head>
<body id="page1">
	<!--header -->
	<div id="header">
		<div class="header-bg">
        <?php include ('head2.php'); ?>
		</div>
	</div>
	<!--header end-->
	<div id="content">
		<div class="main">
			<div class="indent-top">
				<div class="wrapper">
					
                 <?php include ('leftblockprofile.php'); ?>
                    
					<div class="col-2">
                    <div class="indent_1">
					  <div class="box_1">
						<div class="bottom-right">
						<div class="bottom-left">
						<div class="top-right">
						<div class="top-left">
							<div class="indent">
								<h2 class="margin-bot">Editar Perfil</h2>
							  <form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>"  class="le-validate" id="example5">
          <noscript>
            <div
id="div3">
           
</div>
            </noscript>
            <table>
            <tr>
            <td>
          <table class="editor" border="0" cellspacing="0">
  <!--DWLayoutTable-->
<tbody>
<tr>
<td>
</td>
<td>
<div class="field_container">
    <? if($row_TablaUs['Photo_user']) {?>
	<img src="<?php echo $row_TablaUs['Photo_user']; ?>" alt="<?php echo $row_TablaUs['Nick']; ?>" width="102" height="102"> <? 
	}
	else { ?>
    <img src="images/blank_boy_m.png" width="102" height="102">
    <? } ?>
    <br>
    <input name="Foto" type="file" id="Foto" />
    </div>
</td>
</tr>
<tr>
<td class="label"><strong>
<div id="reg_pages_msg" align="right">* Nick:</div></strong></td>
<td width="280">
<div class="field_container">
<input name="Nick" type="text" id="Nick" value="<?php echo $row_TablaUs['Nick']; ?>" size="30" maxlength="50"/></div></td></tr>

<tr>
<td class="label"><strong>
<div id="reg_pages_msg" align="right">* Nombre:</div></strong></td>
<td width="280">
<div class="field_container">
<input name="Nombre" type="text" id="Nombre" value="<?php echo $row_TablaUs['Nombre']; ?>" size="30" maxlength="50"/></div></td></tr>
<td class="label"><strong>
<div id="reg_pages_msg" align="right">* Apellido:</div></strong></td>
<td width="280"><div class="field_container">
  <input name="Apellido" type="text" id="Apellido" value="<?php echo $row_TablaUs['Apellidos'];?>" size="30" maxlength="50"/>
  </div>
</td>
</tr>

<tr>
	<td class="label"><strong>
	<div align="right"> * e-mail:</div></strong>
    </td>
    <td><div class="field_container">
	 <input name="Email" type="text" id="Email" value="<?php echo $row_TablaUs['Correo'];?>" size="50" maxlength="100"/> 
	</div></td></tr>
<tr>
<td class="label"><strong>
  <div id="reg_pages_msg" align="right">* Ciudad:</div></strong>
</td>
  <td width="280"><div class="field_container">
    <input name="Location" type="text" id="location" value="<?php echo $row_TablaUs['Location'];?>" size="30" maxlength="50"/>
    </div>
  </td>
</tr>
<tr>
<td class="label"><strong>
  <div id="reg_pages_msg" align="right">* Edad:</div></strong>
</td>
  <td width="280"><div class="field_container">
    <label for="select"></label>
    <select name="edad" id="select">
      <option value="0">Escoja su edad</option>
      <? for($i=15; $i<66; $i++){ ?>
      <option value="<? echo $i;?>"  <?php if ($row_TablaUs['Age_range']==$i) {echo "SELECTED";} ?>><? echo $i;?></option>
     <? } ?>
  </select>
  </div>
  </td>
</tr>
<tr>
<td class="label"><strong>
  <div id="reg_pages_msg" align="right">* Genero:</div></strong>
</td>
  <td width="280"><div class="field_container">
    <label for="select2"></label>
    <select name="genero" id="select2">
      <option value="0">Escoja su genero</option>
      <option value="1" <?php if ($row_TablaUs['Gender']==1) {echo "SELECTED";} ?> >Masculino</option>
      <option value="2" <?php if ($row_TablaUs['Gender']==2) {echo "SELECTED";} ?>>Femenino</option>
    </select>
  </div>
  </td>
</tr>

<tr>
<td class="label"><strong>
  <div id="reg_pages_msg" align="right">* Perfil:</div></strong>
</td>
  <td width="280"><div class="field_container">
    <input name="Perfil" type="radio" id="Perfil" value="0" checked="checked" />
    <label for="Perfil"></label>
  Publico  </div>
  <br>
  <div class="field_container">
    <input type="radio" name="Perfil" id="Perfil" value="1" <?php if ($row_TablaUs['Type_user']==1){echo "checked='checked'";}?>/>
    <label for="Perfil"></label>
  Privado  </div>
  </td>
</tr>

<tr>
<td>
</td>
<td>
<div align="right"><span class="UIButton UIButton_Green UIFormButton">
 <input type="hidden" name="Imgprinc" value="<?php echo $row_TablaUs['Photo_user']; ?>">
  <input type="hidden" name="MM_insert2" value="form2">
    <input value="Actualizar" class="UIButton_Text" type="submit">
	
</span></div>
</td>
</tr>

          </table>
          
          </td>
</tr>          
     <tr>
     <td>
    
</tr>
</table>
    
        <?php  	
		$fecha=date("Y-n-j").date(" H:i:s");
		echo "<input type='hidden' name='Fecha' value='$fecha'>"; 
		echo $msg;
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
	</div>
	<!--footer -->
	<?php include ('footer.php'); ?>
	<!--footer end-->
   <script type="text/javascript"> Cufon.now(); </script>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>

</html>