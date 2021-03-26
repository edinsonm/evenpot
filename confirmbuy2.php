<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('model_dates.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();
if(isset($_SESSION['Id_user']))
{ 
$Id_user=$_SESSION['Id_user'];
}
if ($_POST['Id_evento'])
$Id_evento = $_POST['Id_evento'];

else{
    echo "<script type='text/javascript'>";
    echo "window.history.back(-1)";
    echo "</script>";
}

$TablaEveInfo = consultar_eventoinfo($Id_evento);
$row_EventoInfo = mysql_fetch_assoc($TablaEveInfo);

$TablaEveVisto = consultar_visto();
$Rows_TablaEveVisto = mysql_num_rows($TablaEveVisto);

$TablaFirstLast = consultar_first_last($Id_evento);

$Id_datexevento=$_POST['Date_ticket'];

//actualizar_visto($Id_evento);
$Id_edit='1';

$TablaAforoxeve = consultar_aforoxevento($Id_evento);
$Rows_TablaAforoxEve = mysql_num_rows($TablaAforoxeve);

if($_SESSION["Id_user"]){ 
$TablaFollxEve = consultar_FollxEve($Id_user, $Id_evento);
$Rows_FollxEve = mysql_fetch_assoc($TablaFollxEve);
}
//	$TablaTkts = consultar_listtickets($Id_datexevento);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<title><?php echo $row_EventoInfo['Nombre'];?> | Evenpot</title>
<?php include ('meta.php'); ?>
<!-- for Facebook -->
<meta property="fb:app_id" content="1401397840112695"/>          
<meta property="og:title" content="<?php echo $row_EventoInfo['Nombre']; ?>"/>
<meta property="og:type" content="article"/>
<meta property="og:url" content="http://www.evenpot.com/evento.php?Id_evento=<? echo $Id_evento;?>"/>
<meta property="og:image" content="http://www.evenpot.com/<? echo $row_EventoInfo['Imagen']; ?>"/>
<meta property="og:image:width" content="300" />
<meta property="og:image:height" content="300" />
<meta property="og:description" content=""/>
<meta property="og:site_name" content="Evenpot"/>      
<meta property="og:type" content="evenpotog:event"/>
<meta property="og:street-address" content="<?php echo $row_EventoInfo['Lugar']; ?>"/>
<meta property="og:locality" content="<?php echo $row_EventoInfo['Ciudad']; ?>"/>
<meta property="og:locale" content="es_ES">
        
<!-- for Twitter -->          
<meta name="twitter:site" content="@evenpot">
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?php echo $row_EventoInfo['Nombre']; ?>" />
<meta name="twitter:description" content="" />
<meta name="twitter:image" content="http://www.evenpot.com/<? echo $row_EventoInfo['Imagen']; ?>" />

		<meta name="generator" content="Bootply" />
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="css/styles.css" rel="stylesheet">
	    <!-- PAGE LEVEL STYLES -->
		<link rel="stylesheet" href="admin/assets/plugins/datepicker/css/datepicker.css" />
   

<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationSelect.js" type="text/javascript"></script> 
<script type="text/javascript">  
function validar(){
 var cont = 0;
   for(i = 0; i < document.form1.elements.length; i++)
   {
	   if(document.form1.elements[i].type == "select-one")
       {
             if(document.form1.elements[i].value != 0)
             {
			    cont++;
             }
       }
   }  
			if(cont<=0)
            {
                alert("Seleccione una cantidad de entradas");
				
                return false;
            }else{
                return true;
            }

}  
</script>

<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css">
<link href="SpryAssets/SpryValidationSelect.css" rel="stylesheet" type="text/css" /> 
</head>
<body>

 <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v2.3&appId=1401397840112695";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


 <div class="well"> 
 <?php include ('head2.php'); ?>
</div>
<!-- /header container-->

<div class="container">
<section>
  <!--Bootstrap 3 Scaffolding-->
  
<div class="container">
<div class="row">
    <div class="col-lg-12">
    	  <div class="well well-sm"> 
          <h2 class="margin-bot" itemprop="name" align="center">
		 <b> <?php echo $row_EventoInfo['Nombre']; ?></b></h2>
        </div> 
      </div> 
</div>

<div class="row">

  	<div class="hidden-xs col-lg-3">
    
    <div class="panel panel-success">
        <div class="panel-heading">
         <h3 class="panel-title"><b>Lo mas visto</b></h3>
        </div>
        <?php $n=2; ?> 
                         
									 <?php while ($row_TablaEveVis = mysql_fetch_assoc($TablaEveVisto)){ 
									
									 $TablaFindEveType = find_event_type($row_TablaEveVis['Categoria']);
									 $row_TablaFindEveType = mysql_fetch_assoc($TablaFindEveType);
									  
									 $NomCat=ucwords(strtolower($row_TablaFindEveType['Nom_type']));
									 if ($n%2==0) { ?>
                                    <a href="evento.php?Id_evento=<?php echo $row_TablaEveVis['Id_evento'];?>" class="list-group-item"><?php echo $row_TablaEveVis['Nombre']; ?>
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
                                    <?php } ?>
                                     <?php if ($n%2==1) {?>
									<a href="evento.php?Id_evento=<?php echo $row_TablaEveVis['Id_evento'];?>" class="list-group-item"><?php echo $row_TablaEveVis['Nombre']; ?>
          <span class="glyphicon glyphicon-chevron-right"></span>
        </a>
                                     <?php } $n++;}?>	
      </div>
      
      <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- LeftBlockResp -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-7885079545307944"
     data-ad-slot="2209141513"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
       
    </div>
    
    <div class="col-lg-6"><div class="well">
   
   <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><b>Entradas</b></h3>
  </div> 	
   <table class="table">
  <!--DWLayoutTable-->
<thead>
<tr class="success" align="center">
<th scope="col"><strong>
<div align="left">Fecha</div></strong></th>
     <th><strong>
     <div align="left">Hora</div></strong></th>
     <th><strong>
     <div align="left">Tipo</div></strong></th>
     <th><strong>
     <div align="left">Precio</div></strong></th>
      <th><strong>
     <div align="left">Entradas</div></strong></th>
      <th><strong>
     <div align="left">Subtotal</div></strong></th>
</tr>
</thead>
<tbody>
<?php 
$Totalbuy = 0;
$Indarray=0;

if ($_POST['Arrlist']){
$Arrlist = unserialize(stripslashes($_POST['Arrlist']));
$Alistval = unserialize(stripslashes($_POST['Alistval']));
$j = count($Arrlist);
}
else {
$j = 0;
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = "\$" . $nombre_campo . "='" . $valor . "';"; 
   if (($valor>0)&&(substr($nombre_campo, 0, 6)=='Aforev')) {
   eval($asignacion);
   $Id_aforoxevento = substr($nombre_campo, 6, 7);
   $Arrlist[] = $Id_aforoxevento;
   $Alistval[] = $valor;
   $j++;
   }
}
}
   for($k=0; $k<$j; $k++){
   $TablaAforoxeve = confirmtickets($Arrlist[$k]);     
   $row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve);  
   if ($row_TablaAforoxeve["Capacidad"]==0) { ?>
<tr class="danger">
<? } else { ?>
<tr class="info">
<? } ?>

<td nowrap="nowrap">
<div class="field_container">
<span id="spryselect1">
<?php echo date("d-m-Y",strtotime($row_TablaAforoxeve['Fecha'])); ?>
</span>
</div>
 </td>
    <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
  <?php echo $row_TablaAforoxeve["Hora"];?>
</span>
</div>

    </td>
    <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <?php echo $row_TablaAforoxeve["Nombre_tkt"];?>
</span>
</div>
    </td>
    <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <?php echo "$".number_format($row_TablaAforoxeve["Valor_tkt"]);?>
</span>
</div>
    </td>
    
<td nowrap="nowrap" align="center"> 
    <div class="field_container">
<span id="spryselect1">
 
 <? if ($row_TablaAforoxeve["Capacidad"]==0) { echo "Agotado"; } else {
  echo $Alistval[$k];
  } ?>

</span>
</div>
    </td>
     <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
  
 <?php echo "$".number_format($row_TablaAforoxeve["Valor_tkt"]*$Alistval[$k]);
 $Totalbuy=($row_TablaAforoxeve["Valor_tkt"]*$Alistval[$k])+$Totalbuy; ?> 

</span>
</div>
    </td>
</tr>
   <? } ?>
<tr>
<td></td><td></td><td></td><td></td><td><b>Total:</b></td><td><b><? echo "$".number_format($Totalbuy); ?></b></td>
</tr>
</tbody>
</table>
	</div>
    
<?	
$TablaReg = consultar_controlreg($Id_evento);
$Rows_TablaReg = mysql_fetch_assoc($TablaReg);
?>

<? if($_SESSION["Id_user"]) {
	$result = mysql_query('SELECT * FROM user WHERE Id_user=\''.$_SESSION['Id_user'].'\''); 
	$row = mysql_fetch_array($result);
}
?>
<form method="post" enctype="multipart/form-data" name="form1" action="pay_method.php"  class="le-validate" id="example5">
   <div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><b>Datos de compra</b></h3>
  </div> 
   <table class="table">
  <tr>
    <td><b>Nombre:&nbsp;</b></td>
    <td>
	<? if($_SESSION["Id_user"]) 
	echo $_SESSION["k_name"]; 
	else {?>
     <span id="sprytextfield1">
  <label for="text1"></label>
  <input type="text" name="Nombre" id="text1" tabindex="1">
  <span class="textfieldRequiredMsg">Nombre es requerido</span></span>
  </td>
<? } ?>
    </td>
  </tr>
  <tr>
    <td><b>Apellidos:&nbsp;</b></td>
    <td>
	<? if($_SESSION["Id_user"]) 
	echo $_SESSION["k_apel"]; 
		else {?>
    <span id="sprytextfield2">
  <label for="text3"></label>
  <input type="text" name="Apellido" id="text3" tabindex="2">
  <span class="textfieldRequiredMsg">Apellido es requerido</span></span>
<? } ?>
    </td>
  </tr>
  <tr>
    <td><b>Correo:&nbsp;</b></td>
    <td>
	<? if($row["Correo"]) {
    echo $row["Correo"]; }
	else {?>

      <span id="sprytextfield3">
      <label for="Text4"></label>
      <input type="text" name="Correo" id="Text4" tabindex="3">
      <span class="textfieldRequiredMsg">Correo es requerido</span>
	  </span>
	  <? } ?>
	  </td>
  </tr>
  
  <? if(trim($Rows_TablaReg["Identificacion"]) == 1){	?>
			<tr>
				<td>
                    <b>Identificacion:</b>			
				</td>
				<td>
				<span id="spryselect4">
				<select name="Type_ID" id="Type_ID"  tabindex="4">
					  <option >Tipo Identificacion</option>
					  <option value="1" <?php if ($row["Type_ID"]=='1') {echo "SELECTED";} ?>>C.C</option>
					  <option value="2" <?php if ($row["Type_ID"]=='2') {echo "SELECTED";} ?>>C.E</option>
					  <option value="3" <?php if ($row["Type_ID"]=='3') {echo "SELECTED";} ?>>Pasaporte</option>
					  <option value="4" <?php if ($row["Type_ID"]=='4') {echo "SELECTED";} ?>>T.I</option>
					</select>	
					<span class="selectRequiredMsg">Tipo ID es requerido</span>
				</span>
					<br>
					<span id="sprytextfield5">
					<input type="text" name="Num_ID" id="Num_ID" value="<?php echo $row['Num_ID'];?>"  placeholder="Numero Documento" tabindex="5">
					<span class="textfieldRequiredMsg">Documento es requerido</span>
					</span>
				</td>
			</tr>
	<? } ?>
    <? if(trim($Rows_TablaReg["Telefono"]) == 1){	?>
  <tr>
    <td><b>Telefono:&nbsp;</b></td>
    <td>
  <span id="sprytextfield4">
  <input type="text" name="Telefono" value="<? echo $row["Telefono"]; ?>" id="Text6" tabindex="6">
  <span class="textfieldRequiredMsg">Telefono es requerido</span></span></td>
  </tr>
  <? } ?>
  
	<? if(trim($Rows_TablaReg["Entidad"]) == 1){	?>			
  <tr>
    <td><b>Entidad/Institucion:&nbsp;</b></td>
    <td>
  <span id="sprytextfield6">
	<input type="text" name="Entidad" id="Entidad" value="<?php echo $row['Institution'];?>" placeholder="Entidad/Institucion" tabindex="7">
  <span class="textfieldRequiredMsg">Entidad es requerido</span></span></td>
  </tr>
	<? } ?>
	
	<? if(trim($Rows_TablaReg["Cargo"]) == 1){	?>
	 <tr>
    <td><b>Cargo/Profesion:&nbsp;</b></td>
    <td>
  <span id="sprytextfield7">
		<input type="text" name="Cargo" id="Cargo" value="<?php echo $row['Profesion'];?>" placeholder="Cargo/Profesion" tabindex="8">
	 <span class="textfieldRequiredMsg">Cargo es requerido</span></span></td>
  </tr>
	<? } ?>
	
	<? if(trim($Rows_TablaReg["Pais_Ciudad"]) == 1){	?>
			<tr>
				<td><b>Pais:&nbsp;</b></td>
				<td>
				<span id="spryselect2">
				 <select name="Pais"  tabindex="9">
						<option >Pais</option>
						<option value='Colombia' <?php if ($row['Pais']=='Colombia') {echo 'SELECTED';} ?>>Colombia</option>
						<option value='Afganistán' <?php if ($row['Pais']=='Afganistán') {echo 'SELECTED';} ?>>Afganistán</option>
						<option value='Albania' <?php if ($row['Pais']=='Albania') {echo 'SELECTED';} ?>>Albania</option>
						<option value='Alemania' <?php if ($row['Pais']=='Alemania') {echo 'SELECTED';} ?>>Alemania</option>
						<option value='Andorra' <?php if ($row['Pais']=='Andorra') {echo 'SELECTED';} ?>>Andorra</option>
						<option value='Angola' <?php if ($row['Pais']=='Angola') {echo 'SELECTED';} ?>>Angola</option>
						<option value='Anguilla' <?php if ($row['Pais']=='Anguilla') {echo 'SELECTED';} ?>>Anguilla</option>
						<option value='Antártida' <?php if ($row['Pais']=='Antártida') {echo 'SELECTED';} ?>>Antártida</option>
						<option value='Antigua y Barbuda' <?php if ($row['Pais']=='Antigua y Barbuda') {echo 'SELECTED';} ?>>Antigua y Barbuda</option>
						<option value='Antillas Holandesas' <?php if ($row['Pais']=='Antillas Holandesas') {echo 'SELECTED';} ?>>Antillas Holandesas</option>
						<option value='Arabia Saudí' <?php if ($row['Pais']=='Arabia Saudí') {echo 'SELECTED';} ?>>Arabia Saudí</option>
						<option value='Argelia' <?php if ($row['Pais']=='Argelia') {echo 'SELECTED';} ?>>Argelia</option>
						<option value='Argentina' <?php if ($row['Pais']=='Argentina') {echo 'SELECTED';} ?>>Argentina</option>
						<option value='Armenia' <?php if ($row['Pais']=='Armenia') {echo 'SELECTED';} ?>>Armenia</option>
						<option value='Aruba' <?php if ($row['Pais']=='Aruba') {echo 'SELECTED';} ?>>Aruba</option>
						<option value='Australia' <?php if ($row['Pais']=='Australia') {echo 'SELECTED';} ?>>Australia</option>
						<option value='Austria' <?php if ($row['Pais']=='Austria') {echo 'SELECTED';} ?>>Austria</option>
						<option value='Azerbaiyán' <?php if ($row['Pais']=='Azerbaiyán') {echo 'SELECTED';} ?>>Azerbaiyán</option>
						<option value='Bahamas' <?php if ($row['Pais']=='Bahamas') {echo 'SELECTED';} ?>>Bahamas</option>
						<option value='Bahrein' <?php if ($row['Pais']=='Bahrein') {echo 'SELECTED';} ?>>Bahrein</option>
						<option value='Bangladesh' <?php if ($row['Pais']=='Bangladesh') {echo 'SELECTED';} ?>>Bangladesh</option>
						<option value='Barbados' <?php if ($row['Pais']=='Barbados') {echo 'SELECTED';} ?>>Barbados</option>
						<option value='Bélgica' <?php if ($row['Pais']=='Bélgica') {echo 'SELECTED';} ?>>Bélgica</option>
						<option value='Belice' <?php if ($row['Pais']=='Belice') {echo 'SELECTED';} ?>>Belice</option>
						<option value='Benin' <?php if ($row['Pais']=='Benin') {echo 'SELECTED';} ?>>Benin</option>
						<option value='Bermudas' <?php if ($row['Pais']=='Bermudas') {echo 'SELECTED';} ?>>Bermudas</option>
						<option value='Bielorrusia' <?php if ($row['Pais']=='Bielorrusia') {echo 'SELECTED';} ?>>Bielorrusia</option>
						<option value='Birmania' <?php if ($row['Pais']=='Birmania') {echo 'SELECTED';} ?>>Birmania</option>
						<option value='Bolivia' <?php if ($row['Pais']=='Bolivia') {echo 'SELECTED';} ?>>Bolivia</option>
						<option value='Bosnia y Herzegovina' <?php if ($row['Pais']=='Bosnia y Herzegovina') {echo 'SELECTED';} ?>>Bosnia y Herzegovina</option>
						<option value='Botswana' <?php if ($row['Pais']=='Botswana') {echo 'SELECTED';} ?>>Botswana</option>
						<option value='Brasil' <?php if ($row['Pais']=='Brasil') {echo 'SELECTED';} ?>>Brasil</option>
						<option value='Brunei' <?php if ($row['Pais']=='Brunei') {echo 'SELECTED';} ?>>Brunei</option>
						<option value='Bulgaria' <?php if ($row['Pais']=='Bulgaria') {echo 'SELECTED';} ?>>Bulgaria</option>
						<option value='Burkina Faso' <?php if ($row['Pais']=='Burkina Faso') {echo 'SELECTED';} ?>>Burkina Faso</option>
						<option value='Burundi' <?php if ($row['Pais']=='Burundi') {echo 'SELECTED';} ?>>Burundi</option>
						<option value='Bután' <?php if ($row['Pais']=='Bután') {echo 'SELECTED';} ?>>Bután</option>
						<option value='Cabo Verde' <?php if ($row['Pais']=='Cabo Verde') {echo 'SELECTED';} ?>>Cabo Verde</option>
						<option value='Camboya' <?php if ($row['Pais']=='Camboya') {echo 'SELECTED';} ?>>Camboya</option>
						<option value='Camerún' <?php if ($row['Pais']=='Camerún') {echo 'SELECTED';} ?>>Camerún</option>
						<option value='Canadá' <?php if ($row['Pais']=='Canadá') {echo 'SELECTED';} ?>>Canadá</option>
						<option value='Chad' <?php if ($row['Pais']=='Chad') {echo 'SELECTED';} ?>>Chad</option>
						<option value='Chile' <?php if ($row['Pais']=='Chile') {echo 'SELECTED';} ?>>Chile</option>
						<option value='China' <?php if ($row['Pais']=='China') {echo 'SELECTED';} ?>>China</option>
						<option value='Chipre' <?php if ($row['Pais']=='Chipre') {echo 'SELECTED';} ?>>Chipre</option>
						<option value='Ciudad del Vaticano' <?php if ($row['Pais']=='Ciudad del Vaticano') {echo 'SELECTED';} ?>>Ciudad del Vaticano</option>
						<option value='Comoras' <?php if ($row['Pais']=='Comoras') {echo 'SELECTED';} ?>>Comoras</option>
						<option value='Congo' <?php if ($row['Pais']=='Congo') {echo 'SELECTED';} ?>>Congo</option>
						<option value='Congo, República Democrática del' <?php if ($row['Pais']=='Congo, República Democrática del') {echo 'SELECTED';} ?>>Congo, República Democrática del</option>
						<option value='Corea' <?php if ($row['Pais']=='Corea') {echo 'SELECTED';} ?>>Corea</option>
						<option value='Corea del Norte' <?php if ($row['Pais']=='Corea del Norte') {echo 'SELECTED';} ?>>Corea del Norte</option>
						<option value='Costa de Marfíl' <?php if ($row['Pais']=='Costa de Marfíl') {echo 'SELECTED';} ?>>Costa de Marfíl</option>
						<option value='Costa Rica' <?php if ($row['Pais']=='Costa Rica') {echo 'SELECTED';} ?>>Costa Rica</option>
						<option value='Croacia' <?php if ($row['Pais']=='Croacia') {echo 'SELECTED';} ?>>Croacia</option>
						<option value='Cuba' <?php if ($row['Pais']=='Cuba') {echo 'SELECTED';} ?>>Cuba</option>
						<option value='Dinamarca' <?php if ($row['Pais']=='Dinamarca') {echo 'SELECTED';} ?>>Dinamarca</option>
						<option value='Djibouti' <?php if ($row['Pais']=='Djibouti') {echo 'SELECTED';} ?>>Djibouti</option>
						<option value='Dominica' <?php if ($row['Pais']=='Dominica') {echo 'SELECTED';} ?>>Dominica</option>
						<option value='Ecuador' <?php if ($row['Pais']=='Ecuador') {echo 'SELECTED';} ?>>Ecuador</option>
						<option value='Egipto' <?php if ($row['Pais']=='Egipto') {echo 'SELECTED';} ?>>Egipto</option>
						<option value='El Salvador' <?php if ($row['Pais']=='El Salvador') {echo 'SELECTED';} ?>>El Salvador</option>
						<option value='Emiratos Árabes Unidos' <?php if ($row['Pais']=='Emiratos Árabes Unidos') {echo 'SELECTED';} ?>>Emiratos Árabes Unidos</option>
						<option value='Eritrea' <?php if ($row['Pais']=='Eritrea') {echo 'SELECTED';} ?>>Eritrea</option>
						<option value='Eslovenia' <?php if ($row['Pais']=='Eslovenia') {echo 'SELECTED';} ?>>Eslovenia</option>
						<option value='España' <?php if ($row['Pais']=='España') {echo 'SELECTED';} ?>>España</option>
						<option value='Estados Unidos' <?php if ($row['Pais']=='Estados Unidos') {echo 'SELECTED';} ?>>Estados Unidos</option>
						<option value='Estonia' <?php if ($row['Pais']=='Estonia') {echo 'SELECTED';} ?>>Estonia</option>
						<option value='Etiopía' <?php if ($row['Pais']=='Etiopía') {echo 'SELECTED';} ?>>Etiopía</option>
						<option value='Fiji' <?php if ($row['Pais']=='Fiji') {echo 'SELECTED';} ?>>Fiji</option>
						<option value='Filipinas' <?php if ($row['Pais']=='Filipinas') {echo 'SELECTED';} ?>>Filipinas</option>
						<option value='Finlandia' <?php if ($row['Pais']=='Finlandia') {echo 'SELECTED';} ?>>Finlandia</option>
						<option value='Francia' <?php if ($row['Pais']=='Francia') {echo 'SELECTED';} ?>>Francia</option>
						<option value='Gabón' <?php if ($row['Pais']=='Gabón') {echo 'SELECTED';} ?>>Gabón</option>
						<option value='Gambia' <?php if ($row['Pais']=='Gambia') {echo 'SELECTED';} ?>>Gambia</option>
						<option value='Georgia' <?php if ($row['Pais']=='Georgia') {echo 'SELECTED';} ?>>Georgia</option>
						<option value='mundo' <?php if ($row['Pais']=='mundo') {echo 'SELECTED';} ?>>mundo</option>
						<option value='Gibraltar' <?php if ($row['Pais']=='Gibraltar') {echo 'SELECTED';} ?>>Gibraltar</option>
						<option value='Granada' <?php if ($row['Pais']=='Granada') {echo 'SELECTED';} ?>>Granada</option>
						<option value='Grecia' <?php if ($row['Pais']=='Grecia') {echo 'SELECTED';} ?>>Grecia</option>
						<option value='Groenlandia' <?php if ($row['Pais']=='Groenlandia') {echo 'SELECTED';} ?>>Groenlandia</option>
						<option value='Guadalupe' <?php if ($row['Pais']=='Guadalupe') {echo 'SELECTED';} ?>>Guadalupe</option>
						<option value='Guatemala' <?php if ($row['Pais']=='Guatemala') {echo 'SELECTED';} ?>>Guatemala</option>
						<option value='Guayana' <?php if ($row['Pais']=='Guayana') {echo 'SELECTED';} ?>>Guayana</option>
						<option value='Guayana Francesa' <?php if ($row['Pais']=='Guayana Francesa') {echo 'SELECTED';} ?>>Guayana Francesa</option>
						<option value='Guinea' <?php if ($row['Pais']=='Guinea') {echo 'SELECTED';} ?>>Guinea</option>
						<option value='Guinea Ecuatorial' <?php if ($row['Pais']=='Guinea Ecuatorial') {echo 'SELECTED';} ?>>Guinea Ecuatorial</option>
						<option value='Guinea-Bissau' <?php if ($row['Pais']=='Guinea-Bissau') {echo 'SELECTED';} ?>>Guinea-Bissau</option>
						<option value='Haití' <?php if ($row['Pais']=='Haití') {echo 'SELECTED';} ?>>Haití</option>
						<option value='Honduras' <?php if ($row['Pais']=='Honduras') {echo 'SELECTED';} ?>>Honduras</option>
						<option value='Hong Kong, ZAE de la RPC' <?php if ($row['Pais']=='Hong Kong, ZAE de la RPC') {echo 'SELECTED';} ?>>Hong Kong, ZAE de la RPC</option>
						<option value='Hungría' <?php if ($row['Pais']=='Hungría') {echo 'SELECTED';} ?>>Hungría</option>
						<option value='India' <?php if ($row['Pais']=='India') {echo 'SELECTED';} ?>>India</option>
						<option value='Indonesia' <?php if ($row['Pais']=='Indonesia') {echo 'SELECTED';} ?>>Indonesia</option>
						<option value='Irak' <?php if ($row['Pais']=='Irak') {echo 'SELECTED';} ?>>Irak</option>
						<option value='Irán' <?php if ($row['Pais']=='Irán') {echo 'SELECTED';} ?>>Irán</option>
						<option value='Irlanda' <?php if ($row['Pais']=='Irlanda') {echo 'SELECTED';} ?>>Irlanda</option>
						<option value='Isla Bouvet' <?php if ($row['Pais']=='Isla Bouvet') {echo 'SELECTED';} ?>>Isla Bouvet</option>
						<option value='Islandia' <?php if ($row['Pais']=='Islandia') {echo 'SELECTED';} ?>>Islandia</option>
						<option value='Islas Caimán' <?php if ($row['Pais']=='Islas Caimán') {echo 'SELECTED';} ?>>Islas Caimán</option>
						<option value='Islas Malvinas' <?php if ($row['Pais']=='Islas Malvinas') {echo 'SELECTED';} ?>>Islas Malvinas</option>
						<option value='Israel' <?php if ($row['Pais']=='Israel') {echo 'SELECTED';} ?>>Israel</option>
						<option value='Italia' <?php if ($row['Pais']=='Italia') {echo 'SELECTED';} ?>>Italia</option>
						<option value='Jamaica' <?php if ($row['Pais']=='Jamaica') {echo 'SELECTED';} ?>>Jamaica</option>
						<option value='Japón' <?php if ($row['Pais']=='Japón') {echo 'SELECTED';} ?>>Japón</option>
						<option value='Jordania' <?php if ($row['Pais']=='Jordania') {echo 'SELECTED';} ?>>Jordania</option>
						<option value='Kazajistán' <?php if ($row['Pais']=='Kazajistán') {echo 'SELECTED';} ?>>Kazajistán</option>
						<option value='Kenia' <?php if ($row['Pais']=='Kenia') {echo 'SELECTED';} ?>>Kenia</option>
						<option value='Kirguizistán' <?php if ($row['Pais']=='Kirguizistán') {echo 'SELECTED';} ?>>Kirguizistán</option>
						<option value='Kiribati' <?php if ($row['Pais']=='Kiribati') {echo 'SELECTED';} ?>>Kiribati</option>
						<option value='Kuwait' <?php if ($row['Pais']=='Kuwait') {echo 'SELECTED';} ?>>Kuwait</option>
						<option value='Laos' <?php if ($row['Pais']=='Laos') {echo 'SELECTED';} ?>>Laos</option>
						<option value='Lesotho' <?php if ($row['Pais']=='Lesotho') {echo 'SELECTED';} ?>>Lesotho</option>
						<option value='Letonia' <?php if ($row['Pais']=='Letonia') {echo 'SELECTED';} ?>>Letonia</option>
						<option value='Líbano' <?php if ($row['Pais']=='Líbano') {echo 'SELECTED';} ?>>Líbano</option>
						<option value='Liberia' <?php if ($row['Pais']=='Liberia') {echo 'SELECTED';} ?>>Liberia</option>
						<option value='Libia' <?php if ($row['Pais']=='Libia') {echo 'SELECTED';} ?>>Libia</option>
						<option value='Liechtenstein' <?php if ($row['Pais']=='Liechtenstein') {echo 'SELECTED';} ?>>Liechtenstein</option>
						<option value='Lituania' <?php if ($row['Pais']=='Lituania') {echo 'SELECTED';} ?>>Lituania</option>
						<option value='Luxemburgo' <?php if ($row['Pais']=='Luxemburgo') {echo 'SELECTED';} ?>>Luxemburgo</option>
						<option value='Macao' <?php if ($row['Pais']=='Macao') {echo 'SELECTED';} ?>>Macao</option>
						<option value='Macedonia, Ex-República Yugoslava de' <?php if ($row['Pais']=='Macedonia, Ex-República Yugoslava de') {echo 'SELECTED';} ?>>Macedonia, Ex-República Yugoslava de</option>
						<option value='Madagascar' <?php if ($row['Pais']=='Madagascar') {echo 'SELECTED';} ?>>Madagascar</option>
						<option value='Malasia' <?php if ($row['Pais']=='Malasia') {echo 'SELECTED';} ?>>Malasia</option>
						<option value='Malawi' <?php if ($row['Pais']=='Malawi') {echo 'SELECTED';} ?>>Malawi</option>
						<option value='Maldivas' <?php if ($row['Pais']=='Maldivas') {echo 'SELECTED';} ?>>Maldivas</option>
						<option value='Malí' <?php if ($row['Pais']=='Malí') {echo 'SELECTED';} ?>>Malí</option>
						<option value='Malta' <?php if ($row['Pais']=='Malta') {echo 'SELECTED';} ?>>Malta</option>
						<option value='Marruecos' <?php if ($row['Pais']=='Marruecos') {echo 'SELECTED';} ?>>Marruecos</option>
						<option value='Martinica' <?php if ($row['Pais']=='Martinica') {echo 'SELECTED';} ?>>Martinica</option>
						<option value='Mauricio' <?php if ($row['Pais']=='Mauricio') {echo 'SELECTED';} ?>>Mauricio</option>
						<option value='Mauritania' <?php if ($row['Pais']=='Mauritania') {echo 'SELECTED';} ?>>Mauritania</option>
						<option value='México' <?php if ($row['Pais']=='México') {echo 'SELECTED';} ?>>México</option>
						<option value='Micronesia' <?php if ($row['Pais']=='Micronesia') {echo 'SELECTED';} ?>>Micronesia</option>
						<option value='Moldavia' <?php if ($row['Pais']=='Moldavia') {echo 'SELECTED';} ?>>Moldavia</option>
						<option value='Mónaco' <?php if ($row['Pais']=='Mónaco') {echo 'SELECTED';} ?>>Mónaco</option>
						<option value='Mongolia' <?php if ($row['Pais']=='Mongolia') {echo 'SELECTED';} ?>>Mongolia</option>
						<option value='Montserrat' <?php if ($row['Pais']=='Montserrat') {echo 'SELECTED';} ?>>Montserrat</option>
						<option value='Mozambique' <?php if ($row['Pais']=='Mozambique') {echo 'SELECTED';} ?>>Mozambique</option>
						<option value='Namibia' <?php if ($row['Pais']=='Namibia') {echo 'SELECTED';} ?>>Namibia</option>
						<option value='Nauru' <?php if ($row['Pais']=='Nauru') {echo 'SELECTED';} ?>>Nauru</option>
						<option value='Nepal' <?php if ($row['Pais']=='Nepal') {echo 'SELECTED';} ?>>Nepal</option>
						<option value='Nicaragua' <?php if ($row['Pais']=='Nicaragua') {echo 'SELECTED';} ?>>Nicaragua</option>
						<option value='Níger' <?php if ($row['Pais']=='Níger') {echo 'SELECTED';} ?>>Níger</option>
						<option value='Nigeria' <?php if ($row['Pais']=='Nigeria') {echo 'SELECTED';} ?>>Nigeria</option>
						<option value='Niue' <?php if ($row['Pais']=='Niue') {echo 'SELECTED';} ?>>Niue</option>
						<option value='Norfolk' <?php if ($row['Pais']=='Norfolk') {echo 'SELECTED';} ?>>Norfolk</option>
						<option value='Noruega' <?php if ($row['Pais']=='Noruega') {echo 'SELECTED';} ?>>Noruega</option>
						<option value='Nueva Caledonia' <?php if ($row['Pais']=='Nueva Caledonia') {echo 'SELECTED';} ?>>Nueva Caledonia</option>
						<option value='Nueva Zelanda' <?php if ($row['Pais']=='Nueva Zelanda') {echo 'SELECTED';} ?>>Nueva Zelanda</option>
						<option value='Omán' <?php if ($row['Pais']=='Omán') {echo 'SELECTED';} ?>>Omán</option>
						<option value='Países Bajos' <?php if ($row['Pais']=='Países Bajos') {echo 'SELECTED';} ?>>Países Bajos</option>
						<option value='Panamá' <?php if ($row['Pais']=='Panamá') {echo 'SELECTED';} ?>>Panamá</option>
						<option value='Papúa Nueva Guinea' <?php if ($row['Pais']=='Papúa Nueva Guinea') {echo 'SELECTED';} ?>>Papúa Nueva Guinea</option>
						<option value='Paquistán' <?php if ($row['Pais']=='Paquistán') {echo 'SELECTED';} ?>>Paquistán</option>
						<option value='Paraguay' <?php if ($row['Pais']=='Paraguay') {echo 'SELECTED';} ?>>Paraguay</option>
						<option value='Peru' <?php if ($row['Pais']=='Peru') {echo 'SELECTED';} ?>>Peru</option>
						<option value='Pitcairn' <?php if ($row['Pais']=='Pitcairn') {echo 'SELECTED';} ?>>Pitcairn</option>
						<option value='Polonia' <?php if ($row['Pais']=='Polonia') {echo 'SELECTED';} ?>>Polonia</option>
						<option value='Portugal' <?php if ($row['Pais']=='Portugal') {echo 'SELECTED';} ?>>Portugal</option>
						<option value='Puerto Rico' <?php if ($row['Pais']=='Puerto Rico') {echo 'SELECTED';} ?>>Puerto Rico</option>
						<option value='Qatar' <?php if ($row['Pais']=='Qatar') {echo 'SELECTED';} ?>>Qatar</option>
						<option value='Reino Unido' <?php if ($row['Pais']=='Reino Unido') {echo 'SELECTED';} ?>>Reino Unido</option>
						<option value='República Centroafricana' <?php if ($row['Pais']=='República Centroafricana') {echo 'SELECTED';} ?>>República Centroafricana</option>
						<option value='República Checa' <?php if ($row['Pais']=='República Checa') {echo 'SELECTED';} ?>>República Checa</option>
						<option value='República de Sudáfrica' <?php if ($row['Pais']=='República de Sudáfrica') {echo 'SELECTED';} ?>>República de Sudáfrica</option>
						<option value='República Dominicana' <?php if ($row['Pais']=='República Dominicana') {echo 'SELECTED';} ?>>República Dominicana</option>
						<option value='República Eslovaca' <?php if ($row['Pais']=='República Eslovaca') {echo 'SELECTED';} ?>>República Eslovaca</option>
						<option value='Ruanda' <?php if ($row['Pais']=='Ruanda') {echo 'SELECTED';} ?>>Ruanda</option>
						<option value='Rumania' <?php if ($row['Pais']=='Rumania') {echo 'SELECTED';} ?>>Rumania</option>
						<option value='Rusia' <?php if ($row['Pais']=='Rusia') {echo 'SELECTED';} ?>>Rusia</option>
						<option value='Samoa' <?php if ($row['Pais']=='Samoa') {echo 'SELECTED';} ?>>Samoa</option>
						<option value='Samoa Americana' <?php if ($row['Pais']=='Samoa Americana') {echo 'SELECTED';} ?>>Samoa Americana</option>
						<option value='San Marino' <?php if ($row['Pais']=='San Marino') {echo 'SELECTED';} ?>>San Marino</option>
						<option value='Santa Lucía' <?php if ($row['Pais']=='Santa Lucía') {echo 'SELECTED';} ?>>Santa Lucía</option>
						<option value='Santo Tomé y Príncipe' <?php if ($row['Pais']=='Santo Tomé y Príncipe') {echo 'SELECTED';} ?>>Santo Tomé y Príncipe</option>
						<option value='Senegal' <?php if ($row['Pais']=='Senegal') {echo 'SELECTED';} ?>>Senegal</option>
						<option value='Seychelles' <?php if ($row['Pais']=='Seychelles') {echo 'SELECTED';} ?>>Seychelles</option>
						<option value='Sierra Leona' <?php if ($row['Pais']=='Sierra Leona') {echo 'SELECTED';} ?>>Sierra Leona</option>
						<option value='Singapur' <?php if ($row['Pais']=='Singapur') {echo 'SELECTED';} ?>>Singapur</option>
						<option value='Siria' <?php if ($row['Pais']=='Siria') {echo 'SELECTED';} ?>>Siria</option>
						<option value='Somalia' <?php if ($row['Pais']=='Somalia') {echo 'SELECTED';} ?>>Somalia</option>
						<option value='Sri Lanka' <?php if ($row['Pais']=='Sri Lanka') {echo 'SELECTED';} ?>>Sri Lanka</option>
						<option value='St. Pierre y Miquelon' <?php if ($row['Pais']=='St. Pierre y Miquelon') {echo 'SELECTED';} ?>>St. Pierre y Miquelon</option>
						<option value='Sudán' <?php if ($row['Pais']=='Sudán') {echo 'SELECTED';} ?>>Sudán</option>
						<option value='Suecia' <?php if ($row['Pais']=='Suecia') {echo 'SELECTED';} ?>>Suecia</option>
						<option value='Suiza' <?php if ($row['Pais']=='Suiza') {echo 'SELECTED';} ?>>Suiza</option>
						<option value='Surinam' <?php if ($row['Pais']=='Surinam') {echo 'SELECTED';} ?>>Surinam</option>
						<option value='Tailandia' <?php if ($row['Pais']=='Tailandia') {echo 'SELECTED';} ?>>Tailandia</option>
						<option value='Taiwán' <?php if ($row['Pais']=='Taiwán') {echo 'SELECTED';} ?>>Taiwán</option>
						<option value='Tanzania' <?php if ($row['Pais']=='Tanzania') {echo 'SELECTED';} ?>>Tanzania</option>
						<option value='Tayikistán' <?php if ($row['Pais']=='Tayikistán') {echo 'SELECTED';} ?>>Tayikistán</option>
						<option value='Togo' <?php if ($row['Pais']=='Togo') {echo 'SELECTED';} ?>>Togo</option>
						<option value='Tonga' <?php if ($row['Pais']=='Tonga') {echo 'SELECTED';} ?>>Tonga</option>
						<option value='Trinidad y Tobago' <?php if ($row['Pais']=='Trinidad y Tobago') {echo 'SELECTED';} ?>>Trinidad y Tobago</option>
						<option value='Túnez' <?php if ($row['Pais']=='Túnez') {echo 'SELECTED';} ?>>Túnez</option>
						<option value='Turkmenistán' <?php if ($row['Pais']=='Turkmenistán') {echo 'SELECTED';} ?>>Turkmenistán</option>
						<option value='Turquía' <?php if ($row['Pais']=='Turquía') {echo 'SELECTED';} ?>>Turquía</option>
						<option value='Tuvalu' <?php if ($row['Pais']=='Tuvalu') {echo 'SELECTED';} ?>>Tuvalu</option>
						<option value='Ucrania' <?php if ($row['Pais']=='Ucrania') {echo 'SELECTED';} ?>>Ucrania</option>
						<option value='Uganda' <?php if ($row['Pais']=='Uganda') {echo 'SELECTED';} ?>>Uganda</option>
						<option value='Uruguay' <?php if ($row['Pais']=='Uruguay') {echo 'SELECTED';} ?>>Uruguay</option>
						<option value='Uzbekistán' <?php if ($row['Pais']=='Uzbekistán') {echo 'SELECTED';} ?>>Uzbekistán</option>
						<option value='Venezuela' <?php if ($row['Pais']=='Venezuela') {echo 'SELECTED';} ?>>Venezuela</option>
						<option value='Vietnam' <?php if ($row['Pais']=='Vietnam') {echo 'SELECTED';} ?>>Vietnam</option>
						<option value='Yemen' <?php if ($row['Pais']=='Yemen') {echo 'SELECTED';} ?>>Yemen</option>
						<option value='Yugoslavia' <?php if ($row['Pais']=='Yugoslavia') {echo 'SELECTED';} ?>>Yugoslavia</option>
						<option value='Zambia' <?php if ($row['Pais']=='Zambia') {echo 'SELECTED';} ?>>Zambia</option>
						<option value='Zimbabue' <?php if ($row['Pais']=='Zimbabue') {echo 'SELECTED';} ?>>Zimbabue</option>
				  </select>
				<span class="selectRequiredMsg">Pais es requerido</span>
				</span></td>
				</tr>
				
				<tr>
					<td><b>Ciudad:&nbsp;</b></td>
					<td>
					<span id="sprytextfield8">
					<input type="text" name="Ciudad" id="Ciudad" value="<?php echo $row['Ciudad_nac'];?>" placeholder="Ciudad" tabindex="10">
					<span class="textfieldRequiredMsg">Ciudad es requerido</span></span>
					</td>
				</tr>
			<? } ?>
			<? if(trim($Rows_TablaReg["Genero"]) == 1){	?>
			<td><b>Genero:&nbsp;</b></td>
					<td>
					<span id="spryselect3">
					<select name="Genero" id="Genero" tabindex="11">
					  <option >Genero</option>
					  <option value="1" <?php if ($row["Gender"]=='1') {echo "SELECTED";} ?>>Masculino</option>
					  <option value="2" <?php if ($row["Gender"]=='2') {echo "SELECTED";} ?>>Femenino</option>
				</select>
				<span class="selectRequiredMsg">Genero es requerido</span></span>
			</td>
			</tr>
			<? } ?>
			<? if(trim($Rows_TablaReg["Fec_nac"]) == 1){	?>
			<td><b>Fecha Nacimiento:&nbsp;</b></td>
					<td>
					<span id="sprytextfield9">
				<div class="form-group">
				<input name="Birthday" type="Birthday" value="<?php echo str_replace("-", "/", date("d-m-Y", strtotime($row['Birthday'])));?>" data-date-format="dd/mm/yyyy" id="dp3" placeholder="Fecha de Nac." tabindex="12"/>
				
			</div>	
			<span class="textfieldRequiredMsg">Fecha es requerido</span></span>
			</td>
			</tr>
			<? } ?>
</table>
</div>
                           
<div id="paymethodform" <? if ($Totalbuy>0) echo "style='display: block;'"; else echo "style='display: none;'"; ?> > 

</div>
<? echo $msg; ?> 
  <input name="Id_evento" type="hidden" value="<? echo $Id_evento;?>" />
  <input type="hidden" name="Arrlist" value='<?php echo serialize($Arrlist);?>'>  
  <input type="hidden" name="Alistval" value='<?php echo serialize($Alistval);?>'>  
  <input type="hidden" name="MM_insert2" value="form2">  
  <input type="submit" name="button" id="button" value="Confirmar" class="btn btn-warning" />
</form>
    
    </div>
    </div>
   
<div class="col-sm-3">
      <div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">
    Fechas
    </h3>
  </div>

<? if ($Rows_TablaFirstLast>1){
$TxtF1="Primera Fecha";
$TxtF2="Ultima Fecha";
}
else {
$TxtF1="Inicio";
$TxtF2="Fin";
}?>

<?php $i=0;
	$row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast); ?>
    <div class="list-group-item"><dt><? echo $TxtF1;?>:</dt>
         <dd><h4 class="topic_title datawrap" itemprop="startDate"><?php echo date("d-m-Y",strtotime($row_TablaFirstLast['Fecha'])); ?></h4></dd>
         <h5><?php echo $row_TablaFirstLast['Hora']; ?></h5>
        </div>
      
<div class="list-group-item">													
<dt><? echo $TxtF2;?>:</dt>
<dd><h4 class="topic_title datawrap">
<?php 
if ($Rows_TablaFirstLast>1){													
$row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast);
}
echo date("d-m-Y",strtotime($row_TablaFirstLast['Fecha_fin'])); ?></h4></dd>
                                                    <h5><?php echo $row_TablaFirstLast['Hora_fin']; ?></h5>
												</dl>
 </div>

<div class="list-group-item">													
<dt>Lugar:</dt>	
                                                    <dd><h4 class="topic_title datawrap" itemprop="location" itemscope itemtype="http://schema.org/Place"><?php echo $row_EventoInfo['Lugar']; ?></h4></dd>
</dl>
<dd><h5 class="topic_title datawrap" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $row_EventoInfo['Ciudad']; ?></h5></dd>
</div>
<div class="list-group-item">	
									
     <?php if($_SESSION["Id_user"]){  	 ?>	
	 <?  if($Rows_FollxEve['Follow']!=1){ ?>

   										<a class="btn btn-success" href="/follow.php?Id_evento=<? echo $Id_evento; ?>&Follow=1">Seguir evento</a>
                                          <? } else { ?>
   										<a class="btn btn-success" href="/follow.php?Id_evento=<? echo $Id_evento; ?>&Id_user=<? echo $Id_user; ?>&Follow=0">Dejar de seguir</a>

                                        <? } ?>
<? } 
else {
	$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];	?>
    <a class="btn btn-success" href="/login">Seguir evento</a>
 <?  }?>										
</div>
</div>                                        
                                                    
<div class="hidden-xs panel panel-success">
  
<? 
									if ($first=substr($row_EventoInfo['Twitter'],0,1)=='@')
									$Twitter = substr($row_EventoInfo['Twitter'],1);
									else $Twitter = $row_EventoInfo['Twitter'];
									if($row_EventoInfo['Twitter']){?>
                                    <a class="twitter-timeline" href="https://twitter.com/search?q=<? echo $Twitter; ?>"  data-screen-name="<? echo $Twitter; ?>" data-widget-id="594656154313175040">Tweets sobre <? echo $Twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<? } ?>
</div>
</div>

</div>
</div>
</section>
  
        </div>

<?php include ('footer.php'); ?>          

    <!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9");

var spryselect4 = new Spry.Widget.ValidationSelect("spryselect4");
var spryselect2 = new Spry.Widget.ValidationSelect("spryselect2");
var spryselect3 = new Spry.Widget.ValidationSelect("spryselect3");
    </script>
	

<!-- END GLOBAL SCRIPTS -->
<script src="admin/assets/js/jquery-ui.min.js"></script>
<script src="admin/assets/plugins/uniform/jquery.uniform.min.js"></script>
<script src="admin/assets/plugins/inputlimiter/jquery.inputlimiter.1.3.1.min.js"></script>
<script src="admin/assets/plugins/chosen/chosen.jquery.min.js"></script>
<script src="admin/assets/plugins/colorpicker/js/bootstrap-colorpicker.js"></script>
<script src="admin/assets/plugins/tagsinput/jquery.tagsinput.min.js"></script>
<script src="admin/assets/plugins/validVal/js/jquery.validVal.min.js"></script>
<script src="admin/assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="admin/assets/plugins/datepicker/js/bootstrap-datepicker.js"></script>
<script src="admin/assets/plugins/autosize/jquery.autosize.min.js"></script>
<script src="admin/assets/plugins/jasny/js/bootstrap-inputmask.js"></script>
<script src="admin/assets/js/formsInit.js"></script>
<script>
            $(function () { formInit(); });
        </script>

<script src="admin/assets/plugins/wysihtml5/lib/js/wysihtml5-0.3.0.js"></script>
    <script src="admin/assets/plugins/bootstrap-wysihtml5-hack.js"></script>
       <script src="admin/assets/js/editorInit.js"></script>
    <script>
        $(function () { formWysiwyg(); });
</script>
</body>
</html>