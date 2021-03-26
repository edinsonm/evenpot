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

$TablaAforoxeve = consultar_aforoxevento($Id_evento);
$Rows_TablaAforoxEve = mysql_num_rows($TablaAforoxeve);
$TablaTkts = consultar_listtickets($Id_datexevento);
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
    
    <link type="text/css" href="css/fg.menu.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="css/ui.all.css" media="screen" rel="stylesheet" />
    <link type="text/css" href="css/tables.css" media="screen" rel="stylesheet" />  

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

<!-- Facebook Conversion Code for Registro entrada -->
<script>(function() {
var _fbq = window._fbq || (window._fbq = []);
if (!_fbq.loaded) {
var fbds = document.createElement('script');
fbds.async = true;
fbds.src = '//connect.facebook.net/en_US/fbds.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(fbds, s);
_fbq.loaded = true;
}
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6027924119651', {'value':'0.01','currency':'USD'}]);
</script>
<noscript><img height="1" width="1" alt="" style="display:none" src="https://www.facebook.com/tr?ev=6027924119651&amp;cd[value]=0.01&amp;cd[currency]=USD&amp;noscript=1" /></noscript>
 
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
					<!--		<div class="gallery">
								<div class="wrapper"><a href="#"><img src="images/page1-img1.jpg" alt="" /></a>
								<a href="#"><img src="images/page1-img2.jpg" alt="" /></a>
								<a href="#"><img src="images/page1-img3.jpg" alt="" /></a>
								<a href="#"><img src="images/page1-img4.jpg" alt="" /></a></div>
							</div> !-->
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
<form method="post" enctype="multipart/form-data" name="form1" onsubmit="return validar()" action="confirmbuy.php"  class="le-validate" id="example5">

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
</tr>
</thead>
<?php $i=0; ?>
 <?php while ($row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve)){ ?>
<?php if ($row_TablaAforoxeve["Capacidad"]==0) { ?>
<tr class="even">
<? } else { ?>
<tr>
<? } ?>

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
 <? if ($row_TablaAforoxeve["Capacidad"]==0) { echo "Agotado"; } else {?>
  <select name="Aforev<? echo  $row_TablaAforoxeve["Id_aforoxevento"] ?>" id="Aforoev">
 <option value="0">0</option>
 <? for ($i=1; $i<=$row_TablaAforoxeve["Ticket_order"]; $i++){ ?> 
   <option value="<? echo $i; ?>"><? echo $i; ?></option>
   <? } ?>
 </select> 
 <? } ?>
 </h1>
</span>
</div>
    </td>
</tr>
   <? } ?>
</table>
<input type="submit" name="button" id="button" value="Adquirir" />
                <!-- INFO: The post URL "checkout.PHP" is invoked when clicked on "Pay with PayPal" button.-->
<? echo $msg; ?> <br>
  <input name="Id_evento" type="hidden" value="<? echo $Id_evento;?>" />
    <input type="hidden" name="MM_insert2" value="form2">  
    </form>
<?php  } else 
echo "
<strong><div align='left'>
<h4>No hay entradas creadas para este evento</h4></div></strong>";
?>
 <? } else echo "
<strong><div align='left'>
<h4>Debe ingresar para adquirir entradas <a href=".'login.php'.">Login</a> o <a href=".'register.php'.">Registrarse</a></h4></div></strong>";
 $_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]; ?>                          

											</div>
										</div>
										</div>
										</div>
										</div>
									</div>
                                    <br>
                                 
										
                                      
					
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
	 <?php if($_SESSION["Id_user"]){  	 ?>	
	 <?  if($Rows_FollxEve['Follow']!=1){ ?>
   										<dd><h4 class="topic_title datawrap"><a href="follow.php?Id_evento=<? echo $Id_evento; ?>&Follow=1">Seguir evento</a></h4></dd>
                                          <? } else { ?>
   										<dd><h4 class="topic_title datawrap"><a href="follow.php?Id_evento=<? echo $Id_evento; ?>&Id_user=<? echo $Id_user; ?>&Follow=0">Dejar de seguir</a></h4></dd>
                                        <? } ?>
<? } 
else {
	$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];	?>
    <dd><h4 class="topic_title datawrap"><a href="login.php">Seguir evento</a></h4></dd>
</div> <? }?>
									  <div class="indent">
												
                        <?php $i=0;
						$row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast); ?>
												<dl class="box-list1">
													  <dt>Inicio:</dt>  
													<dd><h3 class="topic_title datawrap" itemprop="startDate"><?php echo date("d-m-Y",strtotime($row_TablaFirstLast['Fecha'])); ?></h3></dd>
                                                    <h5><?php echo $row_TablaFirstLast['Hora']; ?></h5>
                                                     <dt>Fin:</dt>
                                                    <dd><h3 class="topic_title datawrap"><?php echo date("d-m-Y",strtotime($row_TablaFirstLast['Fecha_fin'])); ?></h3></dd>
                                                    <h5><?php echo $row_TablaFirstLast['Hora_fin']; ?></h5>
												</dl>
											
								  <dl class="box-list2">
													<dt>Ciudad:</dt>	
                                                    <dd><h3 class="topic_title datawrap" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><?php echo $row_EventoInfo['Ciudad']; ?></h3></dd>
												</dl>
												<dl class="box-list1">
													<dt>Lugar:</dt>	
                                                    <dd><h3 class="topic_title datawrap" itemprop="location" itemscope itemtype="http://schema.org/Place"><?php echo $row_EventoInfo['Lugar']; ?></h3></dd>
												</dl>
               <? if ($row_EventoInfo['Type_org']==1) { ?>
			   <?php if($_SESSION["Id_user"]){  	 ?>
               										<dl class="box-list2">
													<dt>Adquirir:</dt>	
                                                    <dd><h3 class="topic_title datawrap"><a href="buy_tickets.php?Id_evento=<?php echo $Id_evento; ?>" >Entradas</a></h3></dd>
												</dl>
                                                <? } else {
												$_SESSION['redirect_to']= "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];	?>
                                                
                                                <dl class="box-list2">
													<dt>Adquirir:</dt>	
                                                    <dd><h3 class="topic_title datawrap"><a href="login.php" >Entradas</a></h3></dd>
                                                    </dl>
                                                    <? } ?>
                                                    <? } ?>
											</div>
										</div>
										</div>
										</div>
										</div>
										</div>
										</div>
									</div>
                                    <? 
									if ($first=substr($row_EventoInfo['Twitter'],0,1)=='@')
									$Twitter = substr($row_EventoInfo['Twitter'],1);
									else $Twitter = $row_EventoInfo['Twitter'];
									?>
                                    <? if($row_EventoInfo['Twitter']){?>
                                    <a class="twitter-timeline" href="https://twitter.com/search?q=<? echo $Twitter; ?>"  data-screen-name="<? echo $Twitter; ?>" data-widget-id="594656154313175040">Tweets sobre <? echo $Twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<? } ?>
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