<?php require_once('connections/dbsel.php'); ?>
<?php require_once('model_eventos.php'); ?>
<?php require_once('model_user.php'); ?>
<?php include('connections/comun.php'); ?>
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

$Id_event=$_GET['Id_evento'];

$TablaTeamEveUs = consultar_teameventus($Id_event, $Id_user);
$row_TeamEveUs = mysql_fetch_assoc($TablaTeamEveUs);
$Rows_TeamEveUs = mysql_num_rows($TablaTeamEveUs);

if($Rows_TeamEveUs>0)
{ ?>
	<SCRIPT LANGUAGE="javascript">
     location.href = "assistance.php?Id_evento=<? echo $Id_event; ?>";
	</SCRIPT>
<?	}

$TablaEve = consultar_eventos();
$Rows_TablaRegEve = mysql_num_rows($TablaEve);

$TablaEveType = consultar_event_type();

$TablaEveVisto = consultar_visto();
$Rows_TablaEveVisto = mysql_num_rows($TablaEveVisto);

$validate=0;

if (isset($_POST["MM_insert2"]) && ($_POST["MM_insert2"] == "form2")) 
	{
	if($_POST["Categoria"] == "0")
	{$msg="Escoja una categoria";
	$validate=$validate+1;}
	$Categoria = $_POST["Categoria"];
	
	if($_POST["Nombre"]=="")
	{$msg=$msg."<br>"."Escriba el nombre";
	$validate=$validate+1;}
	$Nombre = $_POST["Nombre"];
		
	if($_POST["Descripcion"]=="")
	{$msg=$msg."<br>"."Escriba la descripcion";
	$validate=$validate+1;}
	$Descripcion = $_POST["Descripcion"];
	
	if(isset($_POST["Pais"])&& ($_POST["Pais"] == "0"))
	{$msg="Escoja un pais";
	$validate=$validate+1;}
	$Pais = $_POST["Pais"];
	
	if($_POST["Ciudad"]=="")
	{$msg=$msg."<br>"."Escriba la ciudad";
	$validate=$validate+1;}
	$Ciudad=$_POST["Ciudad"];
	
	if($_POST["Lugar"]=="")
	{$msg=$msg."<br>"."Escriba el lugar";
	$validate=$validate+1;}
	$Lugar = $_POST["Lugar"];
	
	if($_POST["tipo_p"]=="")
	{$msg=$msg."<br>"."Seleccione si es privado o publico";
	$validate=$validate+1;}
	$tipo_p = $_POST["tipo_p"];
		
	if ($validate==0){
		$last_Update=date("Y-n-j").date(" H:i:s");
		mysql_select_db($database_dbsel, $dbsel);
		$Descripcion = str_replace("'", '"', $Descripcion);

		$query = "UPDATE evento SET Nombre='$Nombre', Descripcion='$Descripcion', Pais='$Pais',  Ciudad='$Ciudad',  Lugar='$Lugar', lst_update='$last_Update', Categoria='$Categoria', Tipo_publicacion='$tipo_p', Id_user='$Id_user' where Id_evento='$Id_event'";

	$result = mysql_query($query);
	if (!$result)
	$msg=$msg."<br>"."No se pudo actualizar el evento";
	else $msg=$msg."Actualizado correctamente";
	}
}
$Table_eventinfo = consultar_eventedit("1", $Id_event);
$row_eventinfo = mysql_fetch_assoc($Table_eventinfo);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php include ('meta.php'); ?>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>

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

<link rel="stylesheet" href="examples/css/main.css">
        <link rel="stylesheet" href="dist/ui/trumbowyg.css">
        <link rel="stylesheet" href="dist/plugins/colors/ui/trumbowyg.colors.css">
        
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
										<h2>Informacion General</h2>
                                        <form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>"  class="le-validate" id="example5">
     
          <table class="editor" border="0" cellspacing="0">
  <!--DWLayoutTable-->
<tbody>
<tr>
<td class="label"><strong><div id="reg_pages_msg" align="right">Categoria:</div></strong></td>
<td width="280"><select name="Categoria" class="select-cat">
  <option value="0">Seleccione</option>
   <?php while ($row_TablaEveType = mysql_fetch_assoc($TablaEveType)){ ?>
  <option value="<? echo $row_TablaEveType['Id_event_type']; ?>" <?php if ($row_eventinfo["Categoria"]==$row_TablaEveType['Id_event_type']) {echo "SELECTED";} ?>><? echo $row_TablaEveType['Nom_type']; ?></option>
     <? } ?>
</select></td></tr>
<td class="label"><strong><div id="reg_pages_msg" align="right">* Nombre:</div></strong></td>
<td width="280">
  <input name="Nombre" type="text" id="Nombre" value="<?php echo $row_eventinfo["Nombre"];?>" size="70" maxlength="100"/>
</td>
</tr>

<tr>
	<td class="label"><strong>
	  <div align="right">* Descripcion:</div></strong></td><td><div class="field_container">
    <textarea name="Descripcion" id="form-content" cols="30" rows="10" required>
                    <?php echo $row_eventinfo['Descripcion'];?>
                    <code></code>
                </textarea>
 
  <script src="bower_components/jquery/dist/jquery.min.js"></script>
        <script src="dist/trumbowyg.js"></script>
        <script src="dist/langs/fr.min.js"></script>
        <script src="dist/plugins/upload/trumbowyg.upload.js"></script>
        <script src="dist/plugins/base64/trumbowyg.base64.js"></script>
        <script src="dist/plugins/colors/trumbowyg.colors.js"></script>
        <script>
            /** Default editor configuration **/
            $('#default-editor')
            .trumbowyg()
            .on('dblclick', function(){
                $(this).trumbowyg();
            })
            .on('tbwfocus tbwblur tbwchange tbwresize tbwpaste tbwclose', function(e){
                console.log(e.type);
            });


            /** Default editor configuration **/
            $('#simple-editor')
            .trumbowyg({
                btns: ['btnGrp-semantic']
            })
            .on('dblclick', function(){
                $(this).trumbowyg();
            });



            /********************************************************
             * Customized button pane + buttons groups + dropdowns
             * Use upload and base64 plugins
             *******************************************************/

            /*
             * Add your own groups of button
             */
             $.trumbowyg.btnsGrps.test = ['bold', 'link'];

            /* Add new words for customs btnsDef just below */
            $.extend(true, $.trumbowyg.langs, {
                fr: {
                    align: 'Alignement',
                    image: 'Image'
                }
            });
            var customizedButtonPaneTbwOptions = {
                lang: 'fr',
                closable: true,
                fixedBtnPane: true,
                btnsDef: {
                    // Customizables dropdowns
                    align: {
                        dropdown: ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                        ico: 'justifyLeft'
                    },
                    image: {
                        dropdown: ['insertImage', 'upload', 'base64'],
                        ico: 'insertImage'
                    }
                },
                btns: ['viewHTML',
                    '|', 'formatting',
                    '|', 'btnGrp-test',
                    '|', 'align',
                    '|', 'btnGrp-lists',
                    '|', 'image',
                    '|', 'foreColor', 'backColor']
            };
            $('#customized-buttonpane')
            .trumbowyg(customizedButtonPaneTbwOptions)
            .on('dblclick', function(){
                $(this).trumbowyg(customizedButtonPaneTbwOptions);
            });

            /** Simple customization with current options **/
            var formTbwOptions = {
                lang: 'es',
                closable: false,
				fullscreenable: false,
                mobile: true,
				tablet: true,
                fixedBtnPane: true,
                fixedFullWidth: true,
                semantic: true,
                resetCss: true,
                removeformatPasted: false,

                autogrow: true,

                btnsDef: {
                    strong: {
                        func: 'bold',
                        key: 'N'
                    }
                }
				
            };
            $('#form-content')
            .trumbowyg(formTbwOptions)
            .on('dblclick', function(){
                $(this).trumbowyg(formTbwOptions);
            });
			</script>                   
	  
	</div></td></tr>
<tr>
  <td class="label"><strong><div id="reg_pages_msg" align="right"> * Pais:</div></strong></td>
  <td width="280">
    <select name="Pais" class="select">
      <option value="0">Seleccione</option>
      <option value="54" <?php if ($row_eventinfo["Pais"]=='54') {echo "SELECTED";} ?>>Argentina</option>
      <option value="55" <?php if ($row_eventinfo["Pais"]=='55') {echo "SELECTED";} ?>>Brasil</option>
      <option value="56" <?php if ($row_eventinfo["Pais"]=='56') {echo "SELECTED";} ?>>Chile</option>
      <option value="57" <?php if ($row_eventinfo["Pais"]=='57') {echo "SELECTED";} ?>>Colombia</option>
      <option value="52" <?php if ($row_eventinfo["Pais"]=='52') {echo "SELECTED";} ?>>Mexico</option>
      <option value="595" <?php if ($row_eventinfo["Pais"]=='595') {echo "SELECTED";} ?>>Paraguay</option>
      <option value="51" <?php if ($row_eventinfo["Pais"]=='51') {echo "SELECTED";} ?>>Peru</option>
      <option value="598" <?php if ($row_eventinfo["Pais"]=='598') {echo "SELECTED";} ?>>Uruguay</option>
      <option value="58" <?php if ($row_eventinfo["Pais"]=='58') {echo "SELECTED";} ?>>Venezuela</option>
      </select>
    <strong>* Ciudad:</strong>
    <input name="Ciudad" type="text" id="Ciudad" value="<?php echo $row_eventinfo['Ciudad'];?>" size="38" maxlength="40"/></td>
</tr>
<tr>
  <td class="label"><strong><div id="reg_pages_msg" align="right">* Lugar:</div></strong></td>
  <td width="280"><div class="field_container"><span id="sprytextfield3">
    
  <div id="textboxsitio" style="display: block;">
    <input name="Lugar" type="text" id="Lugar" value="<?php echo $row_eventinfo['Lugar'];?>" size="40" maxlength="45"/>
    </div>
    </span></div> 
  </td>
</tr>
<tr>
  <td class="label"><strong>
  <div id="reg_pages_msg" align="right"><span style="display: block;">Su evento es:</span></div></strong></td>
  <td width="280"><div class="field_container">
  
    <div id="formula" class="text-cat" style="display: block;">
  <label for="radio3"></label>
  <input type="radio" name="tipo_p" id="tipo_p" value="2" <? if ($row_eventinfo['Tipo_publicacion']=='2') echo "checked='checked'"; ?>/>
  <strong>Publico</strong>(Llega a todo el publico que este en evenpot)</div>
  <div id="formula" class="text-cat" style="display: block;">
    
  <label for="radio2"></label>
  <input name="tipo_p" type="radio" class="custom-radio" id="tipo_p" value="1" <? if ($row_eventinfo['Tipo_publicacion']=='1') echo "checked='checked'"; ?> />
  <strong>Privado</strong>(Solo para compartir con tus amigos) </div>
  </div> 
  </td>
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
  </span></div>
       
     
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
		</div>
	</div>
	<!--footer -->
	<?php include ('footer.php'); ?>
	<!--footer end-->
   <script type="text/javascript"> Cufon.now(); </script>
</body>

</html>