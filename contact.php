<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
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

//actualizar_visto($Id_evento);
$Id_edit='1';


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>Contactenos | Evenpot</title>
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
<body id="page2">
	<!--header -->
	<div id="header"><div class="header-bg">
        <?php include ('head.php'); ?>
		</div></div>
	<!--header end-->
	<div id="content">
		<div class="main">
			<div class="indent-top">
				<div class="wrapper">
					<?php include ('leftblock.php'); ?>
					<div class="col-2">
					  <div class="indent_1">
					    <div class="wrapper">
						  <div class="col-1 pad-right2">
									<div class="box_1">
										<div class="bottom-right">
										<div class="bottom-left">
										<div class="top-right">
										<div class="top-left">
											<div class="indent1">
												<h2 class="margin-bot">Publicidad - Ventas</h2>
												<strong>
                        						<dl class="info">
												<h4>alianzas@evenpot.com</h4>
												</dl>
                   						  	  </strong><br />
                                                <h2 class="margin-bot">Informacion General</h2>
												<strong>
                        						<dl class="info">
												<h4>info@evenpot.com</h4>
												</dl>
                      						  	</strong>
                      						  	<dl class="info">
												<dt>&nbsp;</dt>
												<dd></dd>
												</dl>
                        						
                      <p class="bot1">&nbsp;</p>
												<div class="alignright"><!--<a href="#" class="link-1">view more</a> !--></div>
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