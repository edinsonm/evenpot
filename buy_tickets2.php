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
$Id_evento = $_GET['Id_evento'];
$TablaEveInfo = consultar_eventoinfo($Id_evento);
$row_EventoInfo = mysql_fetch_assoc($TablaEveInfo);

$TablaEveVisto = consultar_visto();
$Rows_TablaEveVisto = mysql_num_rows($TablaEveVisto);

$TablaFirstLast = consultar_first_last($Id_evento);

$Id_datexevento=$_POST['Date_ticket'];

//actualizar_visto($Id_evento);
$Id_edit='1';

$TablaDates = consultar_fechasev($Id_evento);

if (isset($_POST['button']) && ($_POST['button'] == "Adquirir")) {
	$validate=0;
	if($_POST["Date_ticket"]==0)
	{$msg=$msg."<br>"."Seleccione una fecha";
	$validate=1;}
	
	if($_POST["Tipo_ticket"]=="0")
	{$msg=$msg."<br>"."Seleccione un tipo de entrada";
	$validate=1;}
	/*if(($_POST["optionpay"]=="")&&($_POST["Tipo_ticket"]>0))
	echo $_POST["Tipo_ticket"];
	{$msg=$msg."<br>"."Seleccione un medio de pago";
	$validate=1;}
	*/
	if (($validate==0)&&(($_POST["optionpay"]=="1")||($_POST["optionpay"]=="2")))
	{ 
	header('Location:pay_method.php?Id_evento='.$Id_evento.'&Date_ticket='.$_POST['Date_ticket'].'&Tipo_ticket='.$_POST['Tipo_ticket'].'&optionpay='.$_POST['optionpay'].'');
	 }
	/*else if (($validate==0)&&($_POST["optionpay"]=="1"))
	{ 
		header('Location:checkout.php?Id_evento='.$Id_evento.'&Date_ticket='.$_POST['Date_ticket'].'&Tipo_ticket='.$_POST['Tipo_ticket'].'');
		
    } 
	else if ($validate==0)
	{
		header('Location:pay_method.php?Id_evento='.$Id_evento.'&Date_ticket='.$_POST['Date_ticket'].'&Tipo_ticket='.$_POST['Tipo_ticket'].'');
	
		}*/
	//else $msg=$msg."<br>"."Seleccione un medio de pago";	
	}

	$TablaTkts = consultar_listtickets($Id_datexevento);
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
<script type="text/javascript" src="js/fg.menu.js"></script>

<link rel="stylesheet" href="css/levalidate.css" type="text/css" media="screen">
<script type="text/javascript" src="js/fg.menu.js"></script>
    
    <link type="text/css" href="css/fg.menu.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="css/ui.all.css" media="screen" rel="stylesheet" />
    
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
   <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('#Tipo_ticket').on('change', function() {
        var qString = 'sub=' +$(this).val();
       $.post('query.php', qString, processResponse);
      // $('#resultsGoHere').html(qString);
	});

    function processResponse(data) {
		if (data>0){
		//$('#resultsGoHere').html(data);
		$("#paymethodform").css("display", "block");
		jQuery("#pse").attr('checked', false);
		}
		else
		$("#paymethodform").css("display", "none");
		jQuery("#pse").attr('checked', true);
    }

});
</script>

<script type="text/javascript">
$(document).ready(function() {
 $('#Date_ticket').on('change', function() {
	    //$.post('queryvalue.php', {Date_ticket:$(this).val()}, processResponse);
		var id = jQuery("#Date_ticket").find(':selected').val();
		//var id = $("#Date_ticket").val(); 
		//jQuery("#Tipo_ticket").load('queryvalue.php?Date_ticket='+id);
		$.get("queryvalue.php",{Date_ticket:id})
					.done(function(data){
						$("#Tipo_ticket").html(data);
						})
    });
});	
</script> 

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
						  <div class="gallery">
								<div class="wrapper"><a href="#"><img src="images/page1-img1.jpg" alt="" /></a>
								<a href="#"><img src="images/page1-img2.jpg" alt="" /></a>
								<a href="#"><img src="images/page1-img3.jpg" alt="" /></a>
								<a href="#"><img src="images/page1-img4.jpg" alt="" /></a></div>
							</div>
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
<?php if($_SESSION["Id_user"]){  ?>
<?php  $row_TablaTkts = mysql_fetch_assoc($TablaTkts);
$TablaSumTkts = consultar_sumtickets($Id_evento);
$row_TablaSumTkts = mysql_fetch_assoc($TablaSumTkts);
if ($row_TablaSumTkts['Capacidad']>0){
?>
<form method="post" enctype="multipart/form-data" name="form1" action="<?php echo $editFormAction; ?>"  class="le-validate" id="example5">
                                                 <table class="editor" border="0" cellspacing="0">
												  <!--DWLayoutTable-->
												<thead>
												<tr>
												<th class="label"><!--DWLayoutEmptyCell-->&nbsp;</th>
												<th><strong><div align="left">
													  <h4>Fecha</h4></div></strong></th>
												 <th class="label"><strong><div align="left">
												   <h4>Tipo</h4></div></strong></th>
												  <th class="label"><!--DWLayoutEmptyCell-->&nbsp;</th>
												
												</tr>
												</thead>
												<tfoot>
												</tfoot>
												<tbody>												
												
												<tr>
												<td class="label">
												<strong><div align="right">
												  <h4>&nbsp;</h4></div></strong>
												</td>
												<td><strong><div align="right">
												  <span id="spryselect1">
												 <h1> 
                                                   
                                                   <label for="radio"></label>
                                                   <select name="Date_ticket" id="Date_ticket" autocomplete="off" onChange="">
                                                
                                                <option value="0" selected="selected">Escoga Fecha:</option>
                                                <?php while ($row_TablaDates = mysql_fetch_assoc($TablaDates)){ 
												if($row_TablaDates["CapacidadD"]>0){?>
                                                 <option value=<?php echo $row_TablaDates["Id_datexevento"]; if ($row_TablaDates["Id_datexevento"]==$_POST['Date_ticket'])  {echo " selected='SELECTED'";} ?>>
												
                                                 <?php echo $row_TablaDates["Fecha"]." - Hora:".$row_TablaDates["Hora"];?></option>";  $row_listtickets = mysql_fetch_assoc($Table_listtickets);
  												 <? } }?>
												</select>
                                                 </h1>
												</span></div></strong></td>
												 <td class="label">
												<strong><div align="right">
												  <span id="spryselect1">
												 <h1> 
                                                 <select name="Tipo_ticket" id="Tipo_ticket" autocomplete="off">
                                                
                                                <option value="0" selected="selected">Escoja Entrada:</option>
                                            <? if ($row_TablaTkts["Id_ticketxevent"]){?>    
                                                <option value=<?php echo $row_TablaTkts["Id_ticketxevent"];?>>
												 <?php echo $row_TablaTkts["Nombre_tkt"]." - $".$row_TablaTkts["Valor_tkt"];?></option>";
  												
                                                <?php while ($row_TablaTkts = mysql_fetch_assoc($TablaTkts)){ ?>
                                                 <option value=<?php echo $row_TablaTkts["Id_ticketxevent"];?>>
												 <?php echo $row_TablaTkts["Nombre_tkt"]." - $".$row_TablaTkts["Valor_tkt"];?></option>";
  												 <? } }?>
												</select>
                                                 </h1>
												</span></div></strong>
												</td>
												<td class="label">
												  
												  </td>
												</tr>
												</tbody>	
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
<div id="paymethodform" style="display: none;">                                      
<table>
<tr>
<td>
<input name="optionpay" type="radio" id="pse" value="1" />
</td>
<td>
<img src="images/logo_pagopse.png" alt="" width="189" height="47" />
<!--<img src="https://www.paypalobjects.com/webstatic/en_US/btn/btn_pponly_142x27.png" alt="PAYPAL" /> !-->
</td>
</tr>
<tr>
<td>
<input name="optionpay" type="radio" id="pol" value="2"/>
</td>
<td>
<img src="images/Logo POL.png" alt="" width="189" height="39" />
</td>
</tr>
</table>
</div>
<? echo $msg; ?> <br>
  <input name="Id_evento" type="hidden" value="<? echo $Id_evento;?>" />
                       <input type="hidden" name="MM_insert2" value="form2">  
    <input type="submit" name="button" id="button" value="Adquirir" />
                                   
                                             
 </form>
<?php  } else 
echo "
<strong><div align='left'>
<h4>No hay entradas disponibles para este evento</h4></div></strong>";
?>
 <? } else echo "
<strong><div align='left'>
<h4>Debe ingresar para adquirir entradas <a href=".'login.php'.">Login</a></h4></div></strong>";
 $_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; ?>
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
												<h3>Horario</h3>
                                                 <?php while ($row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast)){ $i++;?>
												<dl class="box-list1">
													 <? if($i==1){ ?> <dt>Inicio:</dt> <? } 
													 else {?> <dt>Fin:</dt> <? } ?>
                                                    <dd><h3 class="topic_title datawrap"><?php echo $row_TablaFirstLast['Fecha']; ?></h3></dd>
                                                    <h5><?php echo $row_TablaFirstLast['Hora']; ?></h5>
												</dl>
											<? } ?>			
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
                                                    <dd><h3 class="topic_title datawrap"><a href="login.php" >Entradas</a></h3></dd>
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
                            	<!--footer -->
	<?php include ('footer.php'); ?>
	<!--footer end-->
   <script type="text/javascript"> Cufon.now(); </script>
</body>

</html>