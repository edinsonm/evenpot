<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('model_dates.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
if ($_GET['Id_evento'])
$Id_evento = $_GET['Id_evento'];

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

$Id_datexevento=381; //$_POST['Date_ticket'];

//actualizar_visto($Id_evento);
$Id_edit='1';

$TablaAforoxeve = consultar_aforoxevento($Id_evento);
$Rows_TablaAforoxEve = mysql_num_rows($TablaAforoxeve);

//	$TablaTkts = consultar_listtickets($Id_datexevento);
if (isset($_POST["MM_insert2"]) && (($_POST["MM_insert2"] == "form2")))
{
	$Fecha=date("Y-n-j").date(" H:i:s");
	$insertSQL = sprintf("Insert into user (Nombre, Apellidos, Correo, Telefono, Fecha) VALUES (%s, %s, %s, %s, %s)",
	   					GetSQLValueString($_POST['Nombre'], "text"), 
						GetSQLValueString($_POST['Apellidos'], "text"), 
						GetSQLValueString($_POST['Correo'], "text"), 
						GetSQLValueString($_POST['Telefono'], "text"),
						GetSQLValueString($Fecha, "text"));
						$Result1 = mysql_query($insertSQL, $dbsel) or die(mysql_error());
		
	 $LoginRS__query=sprintf("SELECT Id_user, Nombre, Apellidos FROM user WHERE Correo='%s'", $_POST['$Correo']);
   	  $LoginRS = mysql_query($LoginRS__query, $dbsel) or die(mysql_error());
	  $row = mysql_fetch_array($LoginRS);
	  
		$_SESSION['Id_user']= $row['Id_user'];
		$_SESSION["k_name"] = $Nombre;
		$_SESSION["k_apel"] = $Apellidos;
	}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php include ('meta.php'); ?>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="layout.css" rel="stylesheet" type="text/css" />
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/jquery-1.4.2.js" type="text/javascript"></script>
<script src="js/cufon-yui.js" type="text/javascript"></script>
<script src="js/cufon-replace.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_400.font.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_600.font.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_650.font.js" type="text/javascript"></script>
<script src="js/Myriad_Pro_700.font.js" type="text/javascript"></script>
<script type="text/javascript" src="js/fg.menu.js"></script>

<link rel="stylesheet" href="css/levalidate.css" type="text/css" media="screen">
<script type="text/javascript" src="js/fg.menu.js"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<link type="text/css" href="css/fg.menu.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="css/ui.all.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="css/tables.css" media="screen" rel="stylesheet" />
    
    <script type="text/javascript">
function calcular_total() {
	importe_total = 0
	$(".subtot").each(
		function(index, value) {
		importe_total = $(".precio").val();
		}
	);
	$("#total").val(importe_total);
}
 </script>
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

<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
</head>
<body id="page2">
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
				<?php include ('leftblock.php'); ?>
                 <? $TablaEveType = consultar_event_type(); ?>
					<div class="col-2">
					  <div class="indent_1">
      <div class="menu-1">
								<div class="menu-1-left">
									<div class="menu-1-right">
										<ul>
												<?php while ($row_TablaEveType = mysql_fetch_assoc($TablaEveType)){ ?>
<li><a href="view_eventos.php?Categoria=<? echo $row_TablaEveType['Id_event_type'];  if ($Categoria==$row_TablaEveType['Id_event_type']) echo "class='active'"; ?>">
<? echo $row_TablaEveType['Nom_type']; ?></a></li>
											
                                             <? } ?>
										</ul>
									</div>
								</div>
							</div>
                            <div class="wrapper">
							  <div class="col-1 pad-right2">
									<div class="box_1">
										<div class="bottom-right">
										<div class="bottom-left">
										<div class="top-right">
										<div class="top-left">
											<div class="indent1">
												<h2 class="margin-bot"><?php echo $row_EventoInfo['Nombre']; ?></h2>

<?php  $row_TablaTkts = mysql_fetch_assoc($TablaTkts);?>

 <table summary="Submitted table designs">
  <!--DWLayoutTable-->
<thead>
<tr>
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
<?php 
$Totalbuy = 0;
$Indarray=0;

   $TablaAforoxeve = confirmtickets(549);     
   $row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve);  
   if ($row_TablaAforoxeve["Capacidad"]==0) { ?>
<tr class="even">
<? } else { ?>
<tr>

<td nowrap="nowrap">
<div class="field_container">
<span id="spryselect1">
 <h1>  <?php echo $row_TablaAforoxeve["Fecha"];?></h1>
</span>
</div>
 </td>
    <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <h1>  <?php echo $row_TablaAforoxeve["Hora"];?></h1>
</span>
</div>

    </td>
    <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <h1><?php echo $row_TablaAforoxeve["Nombre_tkt"];?></h1>
</span>
</div>
    </td>
    <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <h1>  <?php echo "$".number_format($row_TablaAforoxeve["Valor_tkt"]);?></h1>
</span>
</div>
    </td>
    
<td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <h1>
 <? if ($row_TablaAforoxeve["Capacidad"]==0) { echo "Agotado"; } else {
  echo 1;
  } ?>
 </h1>
</span>
</div>
    </td>
     <td nowrap="nowrap"> 
    <div class="field_container">
<span id="spryselect1">
 <h1>  
 <?php echo "$".number_format($row_TablaAforoxeve["Valor_tkt"]*$Alistval[$k]);
 $Totalbuy=($row_TablaAforoxeve["Valor_tkt"]*$Alistval[$k])+$Totalbuy; ?> 
 </h1>
</span>
</div>
    </td>
</tr>
   <? } ?>
<tr>
<td></td><td></td><td></td><td></td><td><b>Total:</b></td><td><b><? echo "$".number_format($Totalbuy); ?></b></td>
</tr>

</table>
                                        
<div class="alignright"><!--<a href="#" class="link-1">view more</a> !--></div>
											</div>
										</div>
										</div>
										</div>
										</div>
									</div>
                                    <br>
                                    <div class="box_1">
										<div class="bottom-right">
										<div class="bottom-left">
										<div class="top-right">
										<div class="top-left">
										<div class="indent1">
                                       <!-- INFO: The post URL "checkout.PHP" is invoked when clicked on "Pay with PayPal" button.-->
<div id="resultsGoHere"></div>
<? if ($Result1==1){ ?>
	<h2 class="margin-bot">Haz adquirido tu entrada de forma exitosa</h2>
<?	} else {
?>
<form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>"  class="le-validate" id="example5">
 <table border="0">
  <tr>
    <td><b>Nombre:&nbsp;</b></td>
    <td><span id="sprytextfield1">
  <input type="text" name="Nombre" size="20" maxlength="20" id="text1" />
  <span class="textfieldRequiredMsg">Se necesita un nombre.</span></span></td>
  </tr>
  <tr>
    <td><b>Apellidos:&nbsp;</b></td>
    <td><span id="sprytextfield2">
      <label for="text2"></label>
      <input type="text" name="Apellidos" id="text2" />
      <span class="textfieldRequiredMsg">Se necesita un apellido.</span></span></td>
  </tr>
  <tr>
    <td><b>Correo:&nbsp;</b></td>
    <td><span id="sprytextfield3">
      <label for="text3"></label>
      <input type="text" name="Correo" id="text3" />
      <span class="textfieldRequiredMsg">Se necesita un correo.</span></span>
    </td>
  </tr>
  <tr>
    <td><b>Telefono:&nbsp;</b></td>
    <td>
      <span id="sprytextfield4">
      <label for="text4"></label>
      <input type="text" name="Telefono" id="text4" />
      <span class="textfieldRequiredMsg">Se necesita un telefono.</span></span></td>
  </tr>
</table>


<br>

                                
<div id="paymethodform" <? if ($Totalbuy>0) echo "style='display: block;'"; else echo "style='display: none;'"; ?> > 

<table>
<tr>
<td>
<input name="optionpay" type="radio" id="pse" value="1" <? if ($Totalbuy>0) echo "checked"; ?>/>
</td>
<td>
<img src="images/logo_pagopse.png" alt="" width="189" height="47" />
<!--<img src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_pponly_142x27.png" alt="PAYPAL" /> !-->
</td>
</tr>
<tr>
<td>
<input name="optionpay" type="radio" id="pol" value="2" />
</td>
<td>
<img src="images/Logo POL.png" alt="" width="189" height="39" />
</td>
</tr>
</table>
</div>
<? echo $msg; ?> <br>
  <input name="Id_evento" type="hidden" value="<? echo $Id_evento;?>" />
  <input type="hidden" name="Arrlist" value='<?php echo serialize($Arrlist);?>'>  
  <input type="hidden" name="Alistval" value='<?php echo serialize($Alistval);?>'>  
  <input type="hidden" name="MM_insert2" value="form2">  
  <input type="submit" name="button" id="button" value="Confirmar" />
</form>
<? } ?>
										  <div class="alignright"><!--<a href="#" class="link-1">view more</a> !--></div>
                                                
							  </div>
						  </div>
					  </div>
				  </div>
			  </div>
		  </div>
	  </div>
								<div class="col-2">
									<div class="box-menu white-bg">
										<div class="bottom-tail">
										<div class="left-tail">
										<div class="right-tail">
										<div class="bottom-right">
										<div class="bottom-left">
										<div class="top">
                                       
									  <div class="indent">
												 <?php $i=0;
						$row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast); ?>
												<dl class="box-list1">
													  <dt>Inicio:</dt>  
													<dd><h3 class="topic_title datawrap" itemprop="startDate"><?php echo date("d-m-Y",strtotime($row_TablaFirstLast['Fecha'])); ?></h3></dd>
                                                    <h5><?php echo $row_TablaFirstLast['Hora']; ?></h5>
                                                     <dt>Fin:</dt>
                                                    <dd><h3 class="topic_title datawrap"><?php 
if ($Rows_TablaFirstLast>1){													
$row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast);}
echo date("d-m-Y",strtotime($row_TablaFirstLast['Fecha_fin'])); ?></h3></dd>
                                                    <h5><?php echo $row_TablaFirstLast['Hora_fin']; ?></h5>
												</dl>
												
								  <dl class="box-list1">
													<dt>Ciudad:</dt>	
                                                    <dd><h3 class="topic_title datawrap"><?php echo $row_EventoInfo['Ciudad']; ?></h3></dd>
												</dl>
												<dl class="box-list2">
													<dt>Lugar:</dt>	
                                                    <dd><h3 class="topic_title datawrap"><?php echo $row_EventoInfo['Lugar']; ?></h3></dd>
												</dl>
                                       <?php if($_SESSION["Id_user"]){  	 ?>
												<dl class="box-list1">
													<dt>Comprar:</dt>	
                                                    <dd><h3 class="topic_title datawrap"><a href="buy_tickets.php?Id_evento=<?php echo $Id_evento; ?>" >Entradas</a></h3></dd>
												</dl>
                                                <? } else {?>
                                                <dl class="box-list1">
													<dt>Adquirir:</dt>	
                                                    <dd><h3 class="topic_title datawrap"><a href="confirmticket.php?Id_evento=315" >Entradas</a></h3></dd>
                                                    </dl>
                                                    <? } ?>
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
		</div>
                            	<!--footer -->
	<?php include ('footer.php'); ?>
	<!--footer end-->
   <script type="text/javascript">
Cufon.now();
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
   </script>
</body>

</html>