<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_activity.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('model_dates.php'); ?>
<?php include_once('model_ticketmail.php'); ?>
<?php require_once('connections/OpenGraph.php'); ?>
<?php
session_start();
$_SESSION['verifica']=1;
if(isset($_SESSION['Id_user']))
{
$Id_user=$_SESSION['Id_user'];
$TablaUs = consultar_user($Id_user);
$row_TablaUs = mysql_fetch_assoc($TablaUs);
}
else $Id_user=session_id();

if ($_POST['Id_evento'])
$Id_evento = $_POST['Id_evento'];
else{
	echo "<script type='text/javascript'>";
	echo "location.href ='http://www.evenpot.com'";
    echo "</script>";
}

$VE_Tkt = $_POST['Met_tkt'];

$TablaEveInfo = consultar_eventoinfo($Id_evento);
$row_EventoInfo = mysql_fetch_assoc($TablaEveInfo);

$TablaEveVisto = consultar_visto();
$Rows_TablaEveVisto = mysql_num_rows($TablaEveVisto);

$TablaFirstLast = consultar_first_last($Id_evento);

$Id_datexevento=$_POST['Date_ticket'];

//actualizar_visto($Id_evento);
$Id_edit='1';

$tticket=0;
if($row_EventoInfo['Map']==0)
{
foreach($_POST as $nombre_campo => $valor){
   if (($valor>0)&&(substr($nombre_campo, 0, 6)=='Aforev')) {
   $tticket++;
   }
}
}
else {$Id_aforotkt=$_POST['Aforev'];
$tticket++; }

if($_SESSION["Id_user"]){
$TablaFollxEve = consultar_FollxEve($Id_user, $Id_evento);
$Rows_FollxEve = mysql_num_rows($TablaFollxEve);
}

$TablaAforoxeve = consultar_aforoxevento($Id_evento);
$Rows_TablaAforoxEve = mysql_num_rows($TablaAforoxeve);

//	$TablaTkts = consultar_listtickets($Id_datexevento);
$TablaFirstLast = consultar_first_last($Id_evento);
$Rows_TablaFirstLast = ($TablaFirstLast);
$row_TablaFirstLast = mysql_fetch_assoc($TablaFirstLast);

$queryChk = "Select * from controlreg where Id_evento ='$Id_evento' and Canal=0";
$Rows_TablaReg = mysql_fetch_assoc(mysql_query($queryChk));

$queryPar = "Select Terms from parametro where Id ='1'";
$Rows_TablaPar = mysql_fetch_assoc(mysql_query($queryPar));

if (isset($_POST["MM_insert2"]) && ($_POST["MM_insert2"] == "form2")) 
{
$Avoid_vent=0;
$Id_evento=$_POST['Id_evento'];
$PayMethod=$_POST['PayMethod'];

$TablaEveInfo = consultar_eventoinfo($Id_evento);
$row_EventoInfo = mysql_fetch_assoc($TablaEveInfo);

$TablaEveDate = consultar_date($Id_evento);
$row_EventoDate = mysql_fetch_assoc($TablaEveDate);

if(isset($_SESSION['Id_user']))
{
$Id_user=$_SESSION['Id_user']; 
} else {
//Se comprueba si el usuario ya existe pero no hizo login
$Email = $_POST['Correo'];
$checkuser = mysql_query('SELECT Id_user FROM user WHERE Correo=\''.$Email.'\'');
$username_exist = mysql_num_rows($checkuser);
if ($username_exist>0) {
	$row_GetUser = mysql_fetch_assoc($checkuser);
	$Id_usertmp=$row_GetUser['Id_user'];
	$queryupd = "UPDATE seats SET Merchant='$Id_usertmp' WHERE Merchant='$Id_user' AND Id_event='$Id_evento'";
	$resultbtk = mysql_query($queryupd);
	$Id_user = $row_GetUser['Id_user'];
}
else $Id_user=0;
}

if (($row_EventoInfo['Map']==1) && ($Id_user==0))
{
	$Id_user=session_id();
}

mysql_select_db($database_dbsel, $dbsel);

$Email = $_POST['Correo'];
$Nom_asistente = $_POST['Nombre']." ".$_POST['Apellido'];
$Telefono = $_POST['Telefono'];
$Mth_send=$_POST['Mth_send'];
$Type_ID = $_POST['Type_ID'];
$Num_ID = $_POST['Num_ID'];
$Genero = $_POST['Genero'];
$Birthday = $_POST['Birthday'];
$Entidad = $_POST['Entidad'];
$Cargo = $_POST['Cargo'];
$Pais = $_POST['Pais'];
$Direccion = $_POST['Direccion'];
$Ciudad = $_POST['Ciudad'];
$Extra2 = $_POST['Extra2'];

if(isset($_SESSION['Id_user']))
{
$TablaUser = consultar_user($Id_user);
$row_User = mysql_fetch_assoc($TablaUser);
$Birthday = str_replace("/", "-", $_POST['Birthday']);
$Birthday = date("Y-m-d", strtotime($Birthday)); 
update_usering($Id_user, $_POST['Type_ID'], $_POST['Num_ID'], $Telefono, $_POST['Entidad'], $_POST['Cargo'], $_POST['Pais'], $_POST['Ciudad'],
$_POST['Genero'], $Birthday);
}

if($row_EventoInfo['Map']==0) {
$Alistval = unserialize(stripslashes($_POST['Alistval']));
$Arrlist = unserialize(stripslashes($_POST['Arrlist']));
$j = count($Arrlist);

if ($_POST['Arrlist']){
//$OptionPay = $_POST['optionpay'];
$refVenta=$Id_user.time();
//$refVenta=$Id_user."asd4";
$_SESSION['refVenta'] = $refVenta;
$Fecha=date("Y-n-j").date(" H:i:s");

$Arrlist = unserialize(stripslashes($_POST['Arrlist']));
$Alistval = unserialize(stripslashes($_POST['Alistval']));
$j = count($Arrlist);

$TotalTkts = 0;
$ValorTkt = 0;
$Var_service = $_POST['Var_service'];
$Var_met = $_POST['Var_met'];

for($k=0; $k<$j; $k++){
   $TablaAforoxeve = confirmtickets($Arrlist[$k]);     
   $row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve);
   if ($row_TablaAforoxeve["Capacidad"] >= $row_TablaAforoxeve["Cantidad"]) {
	  $Alistval[$k] = 0;
	  }
   else  {
   $TotalTkts = $Alistval[$k] + $TotalTkts; 
   $ValorTkt = ($row_TablaAforoxeve["Valor_tkt"]*$Alistval[$k])+$ValorTkt;
   
   begin_ticket($Id_evento, $Id_user, $Arrlist[$k], $Alistval[$k], $refVenta, '', '', $Mth_send, $Email, $Nom_asistente, $Telefono, $Type_ID, $Num_ID, $Genero, $Birthday, 
   $Entidad, $Cargo, $Pais, $Ciudad, $Direccion, $Id_evento, $Extra2, $Fecha);
	}
   }
} else { ?>
		<script>
				alert("No se recibieron los parametros de la compra");
		</script>
<? }
}
else {
	$refVenta=$Id_user.time();
	$_SESSION['refVenta'] = $refVenta;
	$Fecha=date("Y-n-j").date(" H:i:s");
	$TotalTkts = 0;
	$ValorTkt = 0;
	$Id_aforev = $_POST['Id_aforotkt'];
	$Var_service = $_POST['Var_service'];
	$Var_met = $_POST['Var_met'];
	$querybtk = "SELECT * FROM seats where Merchant='$Id_user' and Id_event='$Id_evento' and State='B'";
	$resultbtk = mysql_query($querybtk);
	
	while ($row_btk = mysql_fetch_assoc($resultbtk)){
	$TotalTkts = 1; 
	$TablaAforoxeve = confirmtickets($Id_aforev);     
    $row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve);
	$Row = $row_btk['Row'];
	$Seat = $row_btk['Seat'];
	$ValorTkt = ($row_TablaAforoxeve["Valor_tkt"])+$ValorTkt;
	begin_ticket($Id_evento, $Id_user, $Id_aforev, $TotalTkts, $refVenta, $Row, $Seat, $Mth_send, $Email, $Nom_asistente, $Telefono, $Type_ID, $Num_ID, $Genero, $Birthday, 
    $Entidad, $Cargo, $Pais, $Ciudad, $Direccion, $Id_evento, $Extra2, $Fecha);
	}
}

if ($TotalTkts>0) {
if ($ValorTkt>0)  {
$merchantId = "29462";
$accountId = "33506";
$llave_encripcion = "11e2c92d515"; 
$prueba = "0"; //1 variable para poder utilizar tarjetas de crédito de prueba

//$merchantId = "508029";
//$accountId = "512321";
//$llave_encripcion = "4Vj8eK4rloUd272L48hsrarnUA"; 
//$prueba = "1"; //1 variable para poder utilizar tarjetas de crédito de prueba
$moneda = "COP"; //la moneda con la que se realiza la compra
$TaxIVA = 0;
$taxReturn=0;
$Total_pay = $ValorTkt + $Var_met + $Var_service;
$valor = number_format($Total_pay, 1, '.', '');

$llave = "$llave_encripcion~$merchantId~$refVenta~$valor~$moneda"; //concatenación para realizar la firma
$signature = md5($llave); //creación de la firma con la cadena previamente hecha

$Status_User='0';
$Fecha=date("Y-n-j").date(" H:i:s");
$Nom_event = $row_EventoInfo['Nombre'];
begin_pago($Id_user, $Status_User, $Email, $refVenta, $valor, $Nom_event, $PayMethod, $Fecha);
//echo $merchantId."-".$refVenta."-".$valor."-".$moneda."-".$signature;

if ($PayMethod=="Payu") { 
//Sandox https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu
//Prod https://gateway.payulatam.com/ppp-web-gateway
?>
 <form name="formpayu" method="post" action="https://gateway.payulatam.com/ppp-web-gateway">
  <input name="merchantId"    type="hidden"  value="<? echo $merchantId; ?>" >
  <input name="accountId"     type="hidden"  value="<? echo $accountId; ?>" >
  <input name="description"   type="hidden"  value="<? echo $Nom_event; ?>"  >
  <input name="referenceCode" type="hidden"  value="<? echo $refVenta; ?>" >
  <input name="amount"        type="hidden"  value="<? echo $valor; ?>" >
  <input name="tax"           type="hidden"  value="<? echo $TaxIVA; ?>">
  <input name="taxReturnBase" type="hidden"  value="<? echo $taxReturn; ?>">
  <input name="extra1"        type="hidden"  value="<? echo $Id_evento; ?>"  >
  <input name="currency"      type="hidden"  value="<? echo $moneda; ?>" >
  <input name="signature"     type="hidden"  value="<? echo $signature; ?>"  >
  <input name="test"          type="hidden"  value="<? echo $prueba; ?>" >
  <input name="buyerEmail"    type="hidden"  value="<? echo $Email; ?>" >
  <input name="responseUrl"   type="hidden"  value="https://www.evenpot.com/response_pol.php?Channel=py" >
  <input name="confirmationUrl"    type="hidden"  value="https://www.evenpot.com/confirm_pol.php" >
</form>

<script type="text/javascript">
function submitform(){
  document.forms["formpayu"].submit();
}
 submitform();
</script>
<? }
else if ($PayMethod=="Paypal") {
	//URL Paypal Modo pruebas.
	$notify_url="http://www.evenpot.com/payments.php";
	$paypal_url = 'https://www.paypal.com/cgi-bin/webscr?notify_url=urlencode($notify_url)';
	//$paypal_url = 'https://www.paypal.com/cgi-bin/webscr?notify_url=urlencode($notify_url)';
	//$paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
	//URL Paypal para Recibir pagos 
	//$paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
	//Correo electronico del comercio. 
     $merchant_email = 'ventas@evenpot.com';
	//Pon aqui la URL para redireccionar cuando el pago es completado
	$querydiv="SELECT * FROM divisa ORDER BY Id DESC LIMIT 1";
	$row_divisa = mysql_fetch_assoc(mysql_query($querydiv));
	$rate= $row_divisa['Tasa'];
	$valor=(($valor / $rate)*1.04);
?>
<form name="formpaypal" id="paypal_form" action="<? echo $paypal_url; ?>" method="post">
	<input type="hidden" name="cmd" value="_xclick">
	<input type="hidden" name="image_url" value="http://www.evenpot.com/images/Logo_evenpot.gif">
	<input type="hidden" name="business" value="<?php echo $merchant_email; ?>">
	<input type="hidden" name="lc" value="US">
	<input type="hidden" name="rm" value="2">
	<input type="hidden" name="item_name" value="<?php echo $Nom_event; ?>">
	<input type="hidden" name="item_number" value="<?php echo $refVenta; ?>">
	<input type="hidden" name="amount" value="<?php echo $valor; ?>">
	<input type="hidden" name="currency_code" value="USD">
	<input type="hidden" name="no_note" value="0">
	<input type="hidden" name="no_shipping" value="0">
	<input type="hidden" name="custom"  value="<? echo $Id_evento; ?>"  >
	<input type="hidden" name="payer_email"  value="<? echo $Email; ?>" >
	<input type="hidden" name="business" value="ventas@evenpot.com">
	<input type="hidden" name="return" value="https://www.evenpot.com/response_pol.php?Channel=pp">
	<input type="hidden" name="cancel_return" value="https://www.evenpot.com/payment-cancelled.html">
	<input type="hidden" name="notify_url" value="https://www.evenpot.com/payments.php">
	<input type="hidden" name="button_subtype" value="services">
</form>

<script type="text/javascript">
function submitformpp(){
  document.forms["formpaypal"].submit();
}
 submitformpp();
</script>				
<? }
//$successGoTo ="https://gateway.payulatam.com/ppp-web-gateway?accountId=33506&merchantId=29462&referenceCode=$refVenta&description=".$row_EventoInfo['Nombre']."&amount=$valor&tax=0&taxReturnBase=0&signature=$llavecod&currency="COP"&buyerFullName="Ed"&buyerEmail=$Email&tiposMediosDePago=2&extra1=".$Id_evento."&confirmationUrl=http://www.evenpot.com/confirm_pol.php&responseUrl=http://www.evenpot.com/response_pol.php";
//$successGoTo ="https://gateway.pagosonline.net/apps/gateway/index.html?merchantId=29462&referenceCode=$refVenta&description=".$row_EventoInfo['Nombre']."&amount=$valor&iva=0&taxReturnBase=0&signature=$llavecod&currency="COP"&buyerFullName="Ed"&buyerEmail=$Email&tiposMediosDePago=2&extra1=".$Id_evento."&confirmationUrl=http://www.evenpot.com/confirm_pol.php&responseUrl=http://www.evenpot.com/response_pol.php";
else if ($PayMethod=="BankTransfer") {
	$msg="<h4>Muchas gracias por tu interes en ".$row_EventoInfo['Nombre']."</h4>
	<h5>Hemos enviado un correo con las intrucciones de pago</h5>";
	$query = "INSERT INTO transacxevento (Id_evento, Id_user, Descripcion, Email_POL, Mensaje_POL, Transfer_status, Valor_POL, Channel_buy, RefVenta_POL, Datetime) 
	VALUES ($Id_evento, $Id_user, '$Nom_event', '$Email', 'Transfer Pending', 'Pending', '$Total_pay ', 'Web', '$refVenta', '$Fecha')";
	//echo $query;
	//$user_count = $db_handle->numRows($query);
	mysql_query($query);
	notifica_transfer($refVenta);
} 
}else {
for($k=0; $k<$j; $k++){
sub_aforo($Arrlist[$k], $Alistval[$k]);
	}
	if (($Id_user!=0)&&(!$_SESSION['Id_user'])){
	$successGoTo = "success_buy.php?Id_evento=$Id_evento&Email=$Email";
	}
	else $successGoTo = "success_buy.php?Id_evento=$Id_evento";
	}
header(sprintf("Location: %s", $successGoTo));
}
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
  <title>Confirmar Compra | evenpot</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">  
   <?php //include ('meta.php'); ?>
   
    <!-- CSS -->
   <link href="/css2/base.css" rel="stylesheet">
   <!-- CSS -->
    
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
	<link rel='stylesheet' href='/css/jAlert.css'>
</head>
<body>

<!--[if lte IE 8]>
    <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a>.</p>
<![endif]-->

     <!-- Header================================================== -->
    <header class="sticky"> 
	<?php include ('head_menu.php'); ?>
	</header>
	
    <div id="position">
            <div class="container">
                        <ul>
                        <li><a href="#">Inicio</a></li>
                        <li><a href="#">Eventos</a></li>
                        <li><?php echo $row_EventoInfo['Nombre']; ?></li>
                        </ul>
            </div>
    </div><!-- End Position -->
<br>
<div class="container margin_60">
	<div class="row">
	
	<div class="col-md-8 add_bottom_15" id="single_tour_desc">
    <? if ($msg)  
		echo $msg; 
		else { ?>  
					<h3><b><?php echo $row_EventoInfo['Nombre'];?></b></h3>
					<?php if ($row_EventoInfo['Estado']=='DEL') echo "<h4>(Evento en modo prueba)</h4>";?>
                    <span class="rating">
					<?php $meses = array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
					$mesini = $meses[date('n', strtotime($row_TablaFirstLast['Fecha']))];
					$mesfin = $meses[date('n', strtotime($row_TablaFirstLast['Fecha']))];
					if (($mesfin!=$mesini)||($row_TablaFirstLast['Fecha']==$row_TablaFirstLast['Fecha_fin'])){
					echo date("j",strtotime($row_TablaFirstLast['Fecha']))." de ".$mesini; 
					}
					else echo date("j",strtotime($row_TablaFirstLast['Fecha'])); 
					
					if ($row_TablaFirstLast['Fecha']!=$row_TablaFirstLast['Fecha_fin'])
					{
						echo " al ".date("j",strtotime($row_TablaFirstLast['Fecha_fin']))." de ".$mesfin." del ".date("Y",strtotime($row_TablaFirstLast['Fecha_fin'])); 
					}
					else {echo " del ".date("Y",strtotime($row_TablaFirstLast['Fecha']));}
					echo " entre ".$row_TablaFirstLast['Hora']." y ".$row_TablaFirstLast['Hora_fin'];
					?></span><br>
					 <span class="rating"><?php echo $row_EventoInfo['Lugar']; ?>, <?php echo $row_EventoInfo['Ciudad']; ?>.</span><br><br>
		<? if ($tticket>0) { ?>
		<div class="box_style_1">		
		<div class="table-responsive">
			<table class="table table_summary">
				<thead>
              <tr>
                <th>
                    Fecha/Hora
                </th>
                <th>
                    Ticket
                </th>
                <th>
                    Precio
                </th>
                <th>
                    Cantidad
                </th>
				<th class="text-right">
                    Subtotal
                </th>
            </tr>
            </thead>
            <tbody>
            <?php 
			$Totalbuy = 0;
			$TotalTkts = 0;
			$Indarray=0;
			if($row_EventoInfo['Map']==0)
			{
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
				   $Id_aforoxevento = substr($nombre_campo, 6);
				   $Arrlist[] = $Id_aforoxevento;
				   $Alistval[] = $valor;
				   $j++;
				   }
				}
				}
			
			   for($k=0; $k<$j; $k++){
			   $TablaAforoxeve = confirmtickets($Arrlist[$k]);     
			   $row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve);  
			?>
			<tr class="info">
			<tr>
                <td>
                       <?php echo date("d/m/Y",strtotime($row_TablaAforoxeve['Fecha']))." ".$row_TablaAforoxeve["Hora"];?>
                </td>
                <td>
                    <?php echo $row_TablaAforoxeve["Nombre_tkt"];?>
                </td>
                <td <td class="text-right">
                   <?php echo "$".number_format($row_TablaAforoxeve["Valor_tkt"]);?>
                </td>
                <td class="text-center">
                     <?php if ($row_TablaAforoxeve["Capacidad"] >= $row_TablaAforoxeve["Cantidad"]) { ?>
					 <span class="label label-danger">Cerrado</span>
					 <? 
					 
					 $Alistval[$k] = 0;
					 } else {
					   $numtkts=$Alistval[$k];
					   echo $numtkts;
                     } 
					 }
					 ?>
                </td>
				<td class="text-right">
				 <?php echo "<strong>$".number_format($row_TablaAforoxeve["Valor_tkt"]*$numtkts)."</strong>";
				 $TotalTkts = $numtkts + $TotalTkts; 
				 $Totalbuy = ($row_TablaAforoxeve["Valor_tkt"]*$numtkts)+$Totalbuy; 
				 ?> 
				</td>
            </tr>
			<? }
				else {
			   $TablaAforoxeve = confirmtickets($Id_aforotkt);     
			   $row_TablaAforoxeve = mysql_fetch_assoc($TablaAforoxeve);  
			?>
			<tr>
                <td>
                       <?php echo date("d/m/Y",strtotime($row_TablaAforoxeve['Fecha']))." ".$row_TablaAforoxeve["Hora"];?>
                </td>
                <td>
                    <?php echo $row_TablaAforoxeve["Nombre_tkt"];?>
                </td>
                <td <td class="text-right">
                   <?php echo "$".number_format($row_TablaAforoxeve["Valor_tkt"]);?>
                </td>
                <td class="text-center">
                     <?php if ($row_TablaAforoxeve["Capacidad"] >= $row_TablaAforoxeve["Cantidad"]) { ?>
					 <span class="label label-danger">Cerrado</span>
					 <? $Alistval[$k] = 0;
					 } else { 
					 $querybtk = "SELECT COUNT(*) as tot_tkt FROM seats where Merchant='$Id_user' and Id_event='$Id_evento' and State='B'";
					 $row_btk = mysql_fetch_assoc(mysql_query($querybtk));
					 echo $row_btk['tot_tkt']; 
					 $numtkts=$row_btk['tot_tkt'];
                     } ?>
                </td>
				<td class="text-right">
				 <?php echo "<strong>$".number_format($row_TablaAforoxeve["Valor_tkt"]*$row_btk['tot_tkt'])."</strong>";
				 $TotalTkts = $row_btk['tot_tkt'] + $TotalTkts; 
				 $Totalbuy = ($row_TablaAforoxeve["Valor_tkt"]*$row_btk['tot_tkt'])+$Totalbuy;
				 ?> 
				</td>
            </tr>
			<? } ?>
			<? if ($row_EventoInfo['Charge_chn']!=1) { ?>
			<tr>
                <td>      
                </td>
                <td>
                    Servicio
                </td>
                <td class="text-right">
                   <?php 
				   if ($row_TablaAforoxeve["Servicio"]>0)
						{
							$ServiceVal = ceil($row_TablaAforoxeve["Servicio"]/100)*100;
				} else {
					$ServiceFee = 0.03;
					if(($row_EventoInfo['Box_buy']==1)||($row_EventoInfo['Box_dig']==1)||($row_EventoInfo['Box_taq']==1))
					$ServiceFee = 0.04 + $ServiceFee;
					if(($row_EventoInfo['Call_ret']==1)||($row_EventoInfo['Call_dig']==1)||($row_EventoInfo['Call_dlv']==1))
					$ServiceFee = 0.02 + $ServiceFee;
					$ServiceVal = ceil($row_TablaAforoxeve["Valor_tkt"]*$ServiceFee/100)*100;
					}
					echo "$".number_format($ServiceVal);?>
                </td>
                <td class="text-center">
				<? echo $numtkts; ?>
                </td>
				<td class="text-right">
				 <b><?php $Service = $ServiceVal*$numtkts;
				 echo "$".number_format($Service);?> </b>
				</td>
            </tr>
			<? } ?>
			<? if ((($row_EventoInfo['Charge_tkt']!=1)&&($VE_Tkt=="Ret_Tkt"))||($VE_Tkt=="Tkt_dlv")){ ?>
			<tr>
                <td>      
                </td>
                <td>
                    <?php 
				    $Met_entrega="Envio a Email";
				   if ($VE_Tkt=="Ret_Tkt") {
					$Met_entrega="Retiro en punto"; }
				   if ($VE_Tkt=="Tkt_dlv") {
					$Met_entrega="Entrega a domicilio";	}
					
					echo $Met_entrega;?>
                </td>
                <td class="text-right">
                   <?php 
				   $Met_tkt=0;
				   if ($VE_Tkt=="Ret_Tkt")
					   if ($row_EventoInfo['Charge_tkt']==0) 
					$Met_tkt=3000; 
					else $Met_tkt=0;
				   if ($VE_Tkt=="Tkt_dlv") 
					if ($row_EventoInfo['Charge_dom']==0) 
					$Met_tkt=5000;					
					else $Met_tkt=0;
					echo "$".number_format($Met_tkt);?>
                </td>
                <td class="text-center">
				<? if ($VE_Tkt=="Ret_Tkt") {
				echo $TotalTkts; } 
				else echo 1;?>
                </td>
				<td class="text-right">
				<b>
				 <?php 
				 $Tot_Met_tkt=0;
				 if ($VE_Tkt=="Ret_Tkt") {
				$Tot_Met_tkt = $Met_tkt*$TotalTkts; } 
				 else if ($VE_Tkt=="Tkt_dlv") {
				$Tot_Met_tkt = $Met_tkt*1;			} 	 
				  echo "$".number_format($Tot_Met_tkt);?> 
				</b></td>
            </tr>	
			<? } ?>			
			<tr class="total">
			<td colspan="4">
                Total
            </td>
			<td class="text-right">
			<? 
			echo "$".number_format($Totalbuy+$Service+$Tot_Met_tkt); ?>
			</td>
			</tr>
            </tbody>
            </table>
		</div>
		</div>
		<? if ($TotalTkts==0){ ?>
		<div class="box_style_1">
			<h3>Las entradas seleccionadas ya no estan disponibles</h3>
		</div>
		<? } else { ?>
		
			<div class="form_title">
				<h3><strong>1</strong>Datos de compra</h3>
			</div>
			<form method="post" enctype="multipart/form-data" name="form1" id="form1" action="<?php echo $editFormAction; ?>"  class="le-validate" id="example5">
			<div class="step">
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
							<label>Nombres</label>
							<input type="text" class="form-control" id="text1" name="Nombre" tabindex="1" value="<? echo $_SESSION["k_name"]; ?>" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
							<label>Apellidos</label>
							<input type="text" class="form-control" id="text3" name="Apellido" tabindex="2" value="<? echo $_SESSION["k_apel"]; ?>" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
							<label>Correo</label>
							<input type="email" id="Text4" name="Correo" class="form-control" tabindex="3" value="<? echo $row_TablaUs['Correo'];?>" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')" />
						</div>
					</div>
					<? if(trim($Rows_TablaReg["Telefono"]) == 1){	?>
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
							<label>Telefono</label>
							<input type="number" id="telephone_booking" name="Telefono" class="form-control"  tabindex="4" value="<? echo $row_TablaUs['Telefono'];?>" required oninvalid="this.setCustomValidity('Debe escribir un numero')" oninput="setCustomValidity('')" />
						</div>
					</div>
					<? } ?>
				</div>
				<div class="row">
				<? if(trim($Rows_TablaReg["Identificacion"]) == 1){	?>
					<div class="col-md-6">
						<label>Identificacion</label>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<select name="Type_ID" id="Type_ID"  class="form-control" tabindex="5" required oninvalid="this.setCustomValidity('Seleccione una Identificacion')" oninput="setCustomValidity('')">
									  <option value="">Tipo Identificacion</option>
									  <option value="1" <?php if ($row_TablaUs["Type_ID"]=='1') {echo "SELECTED";} ?>>C.C</option>
									  <option value="2" <?php if ($row_TablaUs["Type_ID"]=='2') {echo "SELECTED";} ?>>C.E</option>
									  <option value="3" <?php if ($row_TablaUs["Type_ID"]=='3') {echo "SELECTED";} ?>>Pasaporte</option>
									  <option value="4" <?php if ($row_TablaUs["Type_ID"]=='4') {echo "SELECTED";} ?>>T.I</option>
									  <option value="5" <?php if ($row_TablaUs["Type_ID"]=='5') {echo "SELECTED";} ?>>NIT</option>
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<input type="text" name="Num_ID" id="Num_ID" class="form-control" value="<?php echo $row_TablaUs['Num_ID'];?>"  placeholder="Numero Documento" tabindex="6" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')" />
								</div>
							</div>
						</div>
					</div>
				<? } ?>	
				
					<? if(trim($Rows_TablaReg["Genero"]) == 1){	?>
					<div class="col-md-3">
						<div class="form-group">
							<label>Genero</label>
							<select name="Genero" id="Genero" tabindex="7" class="form-control" required oninvalid="this.setCustomValidity('Seleccione su Genero')" oninput="setCustomValidity('')">
								  <option value="">Genero</option>
								  <option value="1" <?php if ($row_TablaUs["Gender"]=='1') {echo "SELECTED";} ?>>Masculino</option>
								  <option value="2" <?php if ($row_TablaUs["Gender"]=='2') {echo "SELECTED";} ?>>Femenino</option>
							</select>
						</div>
					</div>
					<? } ?>
					<? if(trim($Rows_TablaReg["Fec_nac"]) == 1){	?>
					<div class="col-md-3">
						<div class="form-group">
							<label>Fecha Nacimiento</label>
							<input name="Birthday" type="text" value="<?php echo str_replace("-", "/", date("d-m-Y", strtotime($row_TablaUs['Birthday'])));?>" data-date-format="dd/mm/yyyy" class="date-pick form-control" placeholder="dd/mm/yyyy" tabindex="8" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
					<? } ?>
				</div>	<!--End row -->
								
				<div class="row">
				<? if(trim($Rows_TablaReg["Entidad"]) == 1){	?>	
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
							<label>Entidad/Institucion</label>
							<input type="text" name="Entidad" id="Entidad" value="<?php echo $row_TablaUs['Institution'];?>" class="form-control" placeholder="Entidad/Institucion" tabindex="9" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')" />
						</div>
					</div>
					<? } ?>
					<? if(trim($Rows_TablaReg["Cargo"]) == 1){	?>			
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
							<label>Cargo/Profesion</label>
							<input type="text" name="Cargo" id="Cargo" value="<?php echo $row_TablaUs['Profesion'];?>" class="form-control" placeholder="Cargo/Profesion" tabindex="10" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
					<? } ?>
				</div>
				<? if(trim($Rows_TablaReg["Pais_Ciudad"]) == 1){	?>
				<div class="row">
					<div class="col-md-6 col-sm-6">
						<div class="form-group">
						<label>Escoja Pais</label>
						<select class="form-control"  name="Pais"  tabindex="11" required oninvalid="this.setCustomValidity('Seleccione un Pais')" oninput="setCustomValidity('')">
						<option value="">Pais</option>
						<option value='Colombia' <?php if ($row_TablaUs['Pais']=='Colombia') {echo 'SELECTED';} ?>>Colombia</option>
						<option value='Afganistán' <?php if ($row_TablaUs['Pais']=='Afganistán') {echo 'SELECTED';} ?>>Afganist&aacute;n</option>
						<option value='Albania' <?php if ($row_TablaUs['Pais']=='Albania') {echo 'SELECTED';} ?>>Albania</option>
						<option value='Alemania' <?php if ($row_TablaUs['Pais']=='Alemania') {echo 'SELECTED';} ?>>Alemania</option>
						<option value='Andorra' <?php if ($row_TablaUs['Pais']=='Andorra') {echo 'SELECTED';} ?>>Andorra</option>
						<option value='Angola' <?php if ($row_TablaUs['Pais']=='Angola') {echo 'SELECTED';} ?>>Angola</option>
						<option value='Anguilla' <?php if ($row_TablaUs['Pais']=='Anguilla') {echo 'SELECTED';} ?>>Anguilla</option>
						<option value='Antártida' <?php if ($row_TablaUs['Pais']=='Antártida') {echo 'SELECTED';} ?>>Ant&aacute;rtida</option>
						<option value='Antigua y Barbuda' <?php if ($row_TablaUs['Pais']=='Antigua y Barbuda') {echo 'SELECTED';} ?>>Antigua y Barbuda</option>
						<option value='Antillas Holandesas' <?php if ($row_TablaUs['Pais']=='Antillas Holandesas') {echo 'SELECTED';} ?>>Antillas Holandesas</option>
						<option value='Arabia Saudí' <?php if ($row_TablaUs['Pais']=='Arabia Saudí') {echo 'SELECTED';} ?>>Arabia Saud&iacute;</option>
						<option value='Argelia' <?php if ($row_TablaUs['Pais']=='Argelia') {echo 'SELECTED';} ?>>Argelia</option>
						<option value='Argentina' <?php if ($row_TablaUs['Pais']=='Argentina') {echo 'SELECTED';} ?>>Argentina</option>
						<option value='Armenia' <?php if ($row_TablaUs['Pais']=='Armenia') {echo 'SELECTED';} ?>>Armenia</option>
						<option value='Aruba' <?php if ($row_TablaUs['Pais']=='Aruba') {echo 'SELECTED';} ?>>Aruba</option>
						<option value='Australia' <?php if ($row_TablaUs['Pais']=='Australia') {echo 'SELECTED';} ?>>Australia</option>
						<option value='Austria' <?php if ($row_TablaUs['Pais']=='Austria') {echo 'SELECTED';} ?>>Austria</option>
						<option value='Azerbaiyán' <?php if ($row_TablaUs['Pais']=='Azerbaiyán') {echo 'SELECTED';} ?>>Azerbaiy&aacute;n</option>
						<option value='Bahamas' <?php if ($row_TablaUs['Pais']=='Bahamas') {echo 'SELECTED';} ?>>Bahamas</option>
						<option value='Bahrein' <?php if ($row_TablaUs['Pais']=='Bahrein') {echo 'SELECTED';} ?>>Bahrein</option>
						<option value='Bangladesh' <?php if ($row_TablaUs['Pais']=='Bangladesh') {echo 'SELECTED';} ?>>Bangladesh</option>
						<option value='Barbados' <?php if ($row_TablaUs['Pais']=='Barbados') {echo 'SELECTED';} ?>>Barbados</option>
						<option value='Bélgica' <?php if ($row_TablaUs['Pais']=='Bélgica') {echo 'SELECTED';} ?>>B&eacute;lgica</option>
						<option value='Belice' <?php if ($row_TablaUs['Pais']=='Belice') {echo 'SELECTED';} ?>>Belice</option>
						<option value='Benin' <?php if ($row_TablaUs['Pais']=='Benin') {echo 'SELECTED';} ?>>Benin</option>
						<option value='Bermudas' <?php if ($row_TablaUs['Pais']=='Bermudas') {echo 'SELECTED';} ?>>Bermudas</option>
						<option value='Bielorrusia' <?php if ($row_TablaUs['Pais']=='Bielorrusia') {echo 'SELECTED';} ?>>Bielorrusia</option>
						<option value='Birmania' <?php if ($row_TablaUs['Pais']=='Birmania') {echo 'SELECTED';} ?>>Birmania</option>
						<option value='Bolivia' <?php if ($row_TablaUs['Pais']=='Bolivia') {echo 'SELECTED';} ?>>Bolivia</option>
						<option value='Bosnia y Herzegovina' <?php if ($row_TablaUs['Pais']=='Bosnia y Herzegovina') {echo 'SELECTED';} ?>>Bosnia y Herzegovina</option>
						<option value='Botswana' <?php if ($row_TablaUs['Pais']=='Botswana') {echo 'SELECTED';} ?>>Botswana</option>
						<option value='Brasil' <?php if ($row_TablaUs['Pais']=='Brasil') {echo 'SELECTED';} ?>>Brasil</option>
						<option value='Brunei' <?php if ($row_TablaUs['Pais']=='Brunei') {echo 'SELECTED';} ?>>Brunei</option>
						<option value='Bulgaria' <?php if ($row_TablaUs['Pais']=='Bulgaria') {echo 'SELECTED';} ?>>Bulgaria</option>
						<option value='Burkina Faso' <?php if ($row_TablaUs['Pais']=='Burkina Faso') {echo 'SELECTED';} ?>>Burkina Faso</option>
						<option value='Burundi' <?php if ($row_TablaUs['Pais']=='Burundi') {echo 'SELECTED';} ?>>Burundi</option>
						<option value='Bután' <?php if ($row_TablaUs['Pais']=='Bután') {echo 'SELECTED';} ?>>But&aacute;n</option>
						<option value='Cabo Verde' <?php if ($row_TablaUs['Pais']=='Cabo Verde') {echo 'SELECTED';} ?>>Cabo Verde</option>
						<option value='Camboya' <?php if ($row_TablaUs['Pais']=='Camboya') {echo 'SELECTED';} ?>>Camboya</option>
						<option value='Camerún' <?php if ($row_TablaUs['Pais']=='Camerún') {echo 'SELECTED';} ?>>Camer&uacute;n</option>
						<option value='Canadá' <?php if ($row_TablaUs['Pais']=='Canadá') {echo 'SELECTED';} ?>>Canad&aacute;</option>
						<option value='Chad' <?php if ($row_TablaUs['Pais']=='Chad') {echo 'SELECTED';} ?>>Chad</option>
						<option value='Chile' <?php if ($row_TablaUs['Pais']=='Chile') {echo 'SELECTED';} ?>>Chile</option>
						<option value='China' <?php if ($row_TablaUs['Pais']=='China') {echo 'SELECTED';} ?>>China</option>
						<option value='Chipre' <?php if ($row_TablaUs['Pais']=='Chipre') {echo 'SELECTED';} ?>>Chipre</option>
						<option value='Ciudad del Vaticano' <?php if ($row_TablaUs['Pais']=='Ciudad del Vaticano') {echo 'SELECTED';} ?>>Ciudad del Vaticano</option>
						<option value='Comoras' <?php if ($row_TablaUs['Pais']=='Comoras') {echo 'SELECTED';} ?>>Comoras</option>
						<option value='Congo' <?php if ($row_TablaUs['Pais']=='Congo') {echo 'SELECTED';} ?>>Congo</option>
						<option value='Congo, República Democrática del' <?php if ($row_TablaUs['Pais']=='Congo, República Democrática del') {echo 'SELECTED';} ?>>Congo, Rep&uacute;blica Democr&aacute;tica del</option>
						<option value='Corea' <?php if ($row_TablaUs['Pais']=='Corea') {echo 'SELECTED';} ?>>Corea</option>
						<option value='Corea del Norte' <?php if ($row_TablaUs['Pais']=='Corea del Norte') {echo 'SELECTED';} ?>>Corea del Norte</option>
						<option value='Costa de Marfíl' <?php if ($row_TablaUs['Pais']=='Costa de Marfíl') {echo 'SELECTED';} ?>>Costa de Marf&iacute;l</option>
						<option value='Costa Rica' <?php if ($row_TablaUs['Pais']=='Costa Rica') {echo 'SELECTED';} ?>>Costa Rica</option>
						<option value='Croacia' <?php if ($row_TablaUs['Pais']=='Croacia') {echo 'SELECTED';} ?>>Croacia</option>
						<option value='Cuba' <?php if ($row_TablaUs['Pais']=='Cuba') {echo 'SELECTED';} ?>>Cuba</option>
						<option value='Dinamarca' <?php if ($row_TablaUs['Pais']=='Dinamarca') {echo 'SELECTED';} ?>>Dinamarca</option>
						<option value='Djibouti' <?php if ($row_TablaUs['Pais']=='Djibouti') {echo 'SELECTED';} ?>>Djibouti</option>
						<option value='Dominica' <?php if ($row_TablaUs['Pais']=='Dominica') {echo 'SELECTED';} ?>>Dominica</option>
						<option value='Ecuador' <?php if ($row_TablaUs['Pais']=='Ecuador') {echo 'SELECTED';} ?>>Ecuador</option>
						<option value='Egipto' <?php if ($row_TablaUs['Pais']=='Egipto') {echo 'SELECTED';} ?>>Egipto</option>
						<option value='El Salvador' <?php if ($row_TablaUs['Pais']=='El Salvador') {echo 'SELECTED';} ?>>El Salvador</option>
						<option value='Emiratos Árabes Unidos' <?php if ($row_TablaUs['Pais']=='Emiratos Árabes Unidos') {echo 'SELECTED';} ?>>Emiratos Arabes Unidos</option>
						<option value='Eritrea' <?php if ($row_TablaUs['Pais']=='Eritrea') {echo 'SELECTED';} ?>>Eritrea</option>
						<option value='Eslovenia' <?php if ($row_TablaUs['Pais']=='Eslovenia') {echo 'SELECTED';} ?>>Eslovenia</option>
						<option value='España' <?php if ($row_TablaUs['Pais']=='España') {echo 'SELECTED';} ?>>Espa&ntilde;a</option>
						<option value='Estados Unidos' <?php if ($row_TablaUs['Pais']=='Estados Unidos') {echo 'SELECTED';} ?>>Estados Unidos</option>
						<option value='Estonia' <?php if ($row_TablaUs['Pais']=='Estonia') {echo 'SELECTED';} ?>>Estonia</option>
						<option value='Etiopía' <?php if ($row_TablaUs['Pais']=='Etiopía') {echo 'SELECTED';} ?>>Etiop&iacute;a</option>
						<option value='Fiji' <?php if ($row_TablaUs['Pais']=='Fiji') {echo 'SELECTED';} ?>>Fiji</option>
						<option value='Filipinas' <?php if ($row_TablaUs['Pais']=='Filipinas') {echo 'SELECTED';} ?>>Filipinas</option>
						<option value='Finlandia' <?php if ($row_TablaUs['Pais']=='Finlandia') {echo 'SELECTED';} ?>>Finlandia</option>
						<option value='Francia' <?php if ($row_TablaUs['Pais']=='Francia') {echo 'SELECTED';} ?>>Francia</option>
						<option value='Gabón' <?php if ($row_TablaUs['Pais']=='Gabón') {echo 'SELECTED';} ?>>Gab&oacute;n</option>
						<option value='Gambia' <?php if ($row_TablaUs['Pais']=='Gambia') {echo 'SELECTED';} ?>>Gambia</option>
						<option value='Georgia' <?php if ($row_TablaUs['Pais']=='Georgia') {echo 'SELECTED';} ?>>Georgia</option>
						<option value='mundo' <?php if ($row_TablaUs['Pais']=='mundo') {echo 'SELECTED';} ?>>mundo</option>
						<option value='Gibraltar' <?php if ($row_TablaUs['Pais']=='Gibraltar') {echo 'SELECTED';} ?>>Gibraltar</option>
						<option value='Granada' <?php if ($row_TablaUs['Pais']=='Granada') {echo 'SELECTED';} ?>>Granada</option>
						<option value='Grecia' <?php if ($row_TablaUs['Pais']=='Grecia') {echo 'SELECTED';} ?>>Grecia</option>
						<option value='Groenlandia' <?php if ($row_TablaUs['Pais']=='Groenlandia') {echo 'SELECTED';} ?>>Groenlandia</option>
						<option value='Guadalupe' <?php if ($row_TablaUs['Pais']=='Guadalupe') {echo 'SELECTED';} ?>>Guadalupe</option>
						<option value='Guatemala' <?php if ($row_TablaUs['Pais']=='Guatemala') {echo 'SELECTED';} ?>>Guatemala</option>
						<option value='Guayana' <?php if ($row_TablaUs['Pais']=='Guayana') {echo 'SELECTED';} ?>>Guayana</option>
						<option value='Guayana Francesa' <?php if ($row_TablaUs['Pais']=='Guayana Francesa') {echo 'SELECTED';} ?>>Guayana Francesa</option>
						<option value='Guinea' <?php if ($row_TablaUs['Pais']=='Guinea') {echo 'SELECTED';} ?>>Guinea</option>
						<option value='Guinea Ecuatorial' <?php if ($row_TablaUs['Pais']=='Guinea Ecuatorial') {echo 'SELECTED';} ?>>Guinea Ecuatorial</option>
						<option value='Guinea-Bissau' <?php if ($row_TablaUs['Pais']=='Guinea-Bissau') {echo 'SELECTED';} ?>>Guinea-Bissau</option>
						<option value='Haití' <?php if ($row_TablaUs['Pais']=='Haití') {echo 'SELECTED';} ?>>Hait&iacute;</option>
						<option value='Honduras' <?php if ($row_TablaUs['Pais']=='Honduras') {echo 'SELECTED';} ?>>Honduras</option>
						<option value='Hong Kong, ZAE de la RPC' <?php if ($row_TablaUs['Pais']=='Hong Kong, ZAE de la RPC') {echo 'SELECTED';} ?>>Hong Kong, ZAE de la RPC</option>
						<option value='Hungría' <?php if ($row_TablaUs['Pais']=='Hungría') {echo 'SELECTED';} ?>>Hungr&iacute;a</option>
						<option value='India' <?php if ($row_TablaUs['Pais']=='India') {echo 'SELECTED';} ?>>India</option>
						<option value='Indonesia' <?php if ($row_TablaUs['Pais']=='Indonesia') {echo 'SELECTED';} ?>>Indonesia</option>
						<option value='Irak' <?php if ($row_TablaUs['Pais']=='Irak') {echo 'SELECTED';} ?>>Irak</option>
						<option value='Irán' <?php if ($row_TablaUs['Pais']=='Irán') {echo 'SELECTED';} ?>>Ir&aacute;n</option>
						<option value='Irlanda' <?php if ($row_TablaUs['Pais']=='Irlanda') {echo 'SELECTED';} ?>>Irlanda</option>
						<option value='Isla Bouvet' <?php if ($row_TablaUs['Pais']=='Isla Bouvet') {echo 'SELECTED';} ?>>Isla Bouvet</option>
						<option value='Islandia' <?php if ($row_TablaUs['Pais']=='Islandia') {echo 'SELECTED';} ?>>Islandia</option>
						<option value='Islas Caimán' <?php if ($row_TablaUs['Pais']=='Islas Caimán') {echo 'SELECTED';} ?>>Islas Caim&aacute;n</option>
						<option value='Islas Malvinas' <?php if ($row_TablaUs['Pais']=='Islas Malvinas') {echo 'SELECTED';} ?>>Islas Malvinas</option>
						<option value='Israel' <?php if ($row_TablaUs['Pais']=='Israel') {echo 'SELECTED';} ?>>Israel</option>
						<option value='Italia' <?php if ($row_TablaUs['Pais']=='Italia') {echo 'SELECTED';} ?>>Italia</option>
						<option value='Jamaica' <?php if ($row_TablaUs['Pais']=='Jamaica') {echo 'SELECTED';} ?>>Jamaica</option>
						<option value='Japón' <?php if ($row_TablaUs['Pais']=='Japón') {echo 'SELECTED';} ?>>Jap&oacute;n</option>
						<option value='Jordania' <?php if ($row_TablaUs['Pais']=='Jordania') {echo 'SELECTED';} ?>>Jordania</option>
						<option value='Kazajistán' <?php if ($row_TablaUs['Pais']=='Kazajistán') {echo 'SELECTED';} ?>>Kazajist&aacute;n</option>
						<option value='Kenia' <?php if ($row_TablaUs['Pais']=='Kenia') {echo 'SELECTED';} ?>>Kenia</option>
						<option value='Kirguizistán' <?php if ($row_TablaUs['Pais']=='Kirguizistán') {echo 'SELECTED';} ?>>Kirguizist&aacute;n</option>
						<option value='Kiribati' <?php if ($row_TablaUs['Pais']=='Kiribati') {echo 'SELECTED';} ?>>Kiribati</option>
						<option value='Kuwait' <?php if ($row_TablaUs['Pais']=='Kuwait') {echo 'SELECTED';} ?>>Kuwait</option>
						<option value='Laos' <?php if ($row_TablaUs['Pais']=='Laos') {echo 'SELECTED';} ?>>Laos</option>
						<option value='Lesotho' <?php if ($row_TablaUs['Pais']=='Lesotho') {echo 'SELECTED';} ?>>Lesotho</option>
						<option value='Letonia' <?php if ($row_TablaUs['Pais']=='Letonia') {echo 'SELECTED';} ?>>Letonia</option>
						<option value='Líbano' <?php if ($row_TablaUs['Pais']=='Líbano') {echo 'SELECTED';} ?>>L&iacute;bano</option>
						<option value='Liberia' <?php if ($row_TablaUs['Pais']=='Liberia') {echo 'SELECTED';} ?>>Liberia</option>
						<option value='Libia' <?php if ($row_TablaUs['Pais']=='Libia') {echo 'SELECTED';} ?>>Libia</option>
						<option value='Liechtenstein' <?php if ($row_TablaUs['Pais']=='Liechtenstein') {echo 'SELECTED';} ?>>Liechtenstein</option>
						<option value='Lituania' <?php if ($row_TablaUs['Pais']=='Lituania') {echo 'SELECTED';} ?>>Lituania</option>
						<option value='Luxemburgo' <?php if ($row_TablaUs['Pais']=='Luxemburgo') {echo 'SELECTED';} ?>>Luxemburgo</option>
						<option value='Macao' <?php if ($row_TablaUs['Pais']=='Macao') {echo 'SELECTED';} ?>>Macao</option>
						<option value='Macedonia, Ex-República Yugoslava de' <?php if ($row_TablaUs['Pais']=='Macedonia, Ex-República Yugoslava de') {echo 'SELECTED';} ?>>Macedonia, Ex-Rep&uacute;blica Yugoslava de</option>
						<option value='Madagascar' <?php if ($row_TablaUs['Pais']=='Madagascar') {echo 'SELECTED';} ?>>Madagascar</option>
						<option value='Malasia' <?php if ($row_TablaUs['Pais']=='Malasia') {echo 'SELECTED';} ?>>Malasia</option>
						<option value='Malawi' <?php if ($row_TablaUs['Pais']=='Malawi') {echo 'SELECTED';} ?>>Malawi</option>
						<option value='Maldivas' <?php if ($row_TablaUs['Pais']=='Maldivas') {echo 'SELECTED';} ?>>Maldivas</option>
						<option value='Malí' <?php if ($row_TablaUs['Pais']=='Malí') {echo 'SELECTED';} ?>>Mal&iacute;</option>
						<option value='Malta' <?php if ($row_TablaUs['Pais']=='Malta') {echo 'SELECTED';} ?>>Malta</option>
						<option value='Marruecos' <?php if ($row_TablaUs['Pais']=='Marruecos') {echo 'SELECTED';} ?>>Marruecos</option>
						<option value='Martinica' <?php if ($row_TablaUs['Pais']=='Martinica') {echo 'SELECTED';} ?>>Martinica</option>
						<option value='Mauricio' <?php if ($row_TablaUs['Pais']=='Mauricio') {echo 'SELECTED';} ?>>Mauricio</option>
						<option value='Mauritania' <?php if ($row_TablaUs['Pais']=='Mauritania') {echo 'SELECTED';} ?>>Mauritania</option>
						<option value='México' <?php if ($row_TablaUs['Pais']=='México') {echo 'SELECTED';} ?>>M&eacute;xico</option>
						<option value='Micronesia' <?php if ($row_TablaUs['Pais']=='Micronesia') {echo 'SELECTED';} ?>>Micronesia</option>
						<option value='Moldavia' <?php if ($row_TablaUs['Pais']=='Moldavia') {echo 'SELECTED';} ?>>Moldavia</option>
						<option value='Mónaco' <?php if ($row_TablaUs['Pais']=='Mónaco') {echo 'SELECTED';} ?>>M&oacute;naco</option>
						<option value='Mongolia' <?php if ($row_TablaUs['Pais']=='Mongolia') {echo 'SELECTED';} ?>>Mongolia</option>
						<option value='Montserrat' <?php if ($row_TablaUs['Pais']=='Montserrat') {echo 'SELECTED';} ?>>Montserrat</option>
						<option value='Mozambique' <?php if ($row_TablaUs['Pais']=='Mozambique') {echo 'SELECTED';} ?>>Mozambique</option>
						<option value='Namibia' <?php if ($row_TablaUs['Pais']=='Namibia') {echo 'SELECTED';} ?>>Namibia</option>
						<option value='Nauru' <?php if ($row_TablaUs['Pais']=='Nauru') {echo 'SELECTED';} ?>>Nauru</option>
						<option value='Nepal' <?php if ($row_TablaUs['Pais']=='Nepal') {echo 'SELECTED';} ?>>Nepal</option>
						<option value='Nicaragua' <?php if ($row_TablaUs['Pais']=='Nicaragua') {echo 'SELECTED';} ?>>Nicaragua</option>
						<option value='Níger' <?php if ($row_TablaUs['Pais']=='Níger') {echo 'SELECTED';} ?>>N&iacute;ger</option>
						<option value='Nigeria' <?php if ($row_TablaUs['Pais']=='Nigeria') {echo 'SELECTED';} ?>>Nigeria</option>
						<option value='Niue' <?php if ($row_TablaUs['Pais']=='Niue') {echo 'SELECTED';} ?>>Niue</option>
						<option value='Norfolk' <?php if ($row_TablaUs['Pais']=='Norfolk') {echo 'SELECTED';} ?>>Norfolk</option>
						<option value='Noruega' <?php if ($row_TablaUs['Pais']=='Noruega') {echo 'SELECTED';} ?>>Noruega</option>
						<option value='Nueva Caledonia' <?php if ($row_TablaUs['Pais']=='Nueva Caledonia') {echo 'SELECTED';} ?>>Nueva Caledonia</option>
						<option value='Nueva Zelanda' <?php if ($row_TablaUs['Pais']=='Nueva Zelanda') {echo 'SELECTED';} ?>>Nueva Zelanda</option>
						<option value='Omán' <?php if ($row_TablaUs['Pais']=='Omán') {echo 'SELECTED';} ?>>Om&aacute;n</option>
						<option value='Países Bajos' <?php if ($row_TablaUs['Pais']=='Países Bajos') {echo 'SELECTED';} ?>>Pa&iacute;ses Bajos</option>
						<option value='Panamá' <?php if ($row_TablaUs['Pais']=='Panamá') {echo 'SELECTED';} ?>>Panam&aacute;</option>
						<option value='Papúa Nueva Guinea' <?php if ($row_TablaUs['Pais']=='Papúa Nueva Guinea') {echo 'SELECTED';} ?>>Pap&uacute;a Nueva Guinea</option>
						<option value='Paquistán' <?php if ($row_TablaUs['Pais']=='Paquistán') {echo 'SELECTED';} ?>>Paquist&aacute;n</option>
						<option value='Paraguay' <?php if ($row_TablaUs['Pais']=='Paraguay') {echo 'SELECTED';} ?>>Paraguay</option>
						<option value='Peru' <?php if ($row_TablaUs['Pais']=='Peru') {echo 'SELECTED';} ?>>Peru</option>
						<option value='Pitcairn' <?php if ($row_TablaUs['Pais']=='Pitcairn') {echo 'SELECTED';} ?>>Pitcairn</option>
						<option value='Polonia' <?php if ($row_TablaUs['Pais']=='Polonia') {echo 'SELECTED';} ?>>Polonia</option>
						<option value='Portugal' <?php if ($row_TablaUs['Pais']=='Portugal') {echo 'SELECTED';} ?>>Portugal</option>
						<option value='Puerto Rico' <?php if ($row_TablaUs['Pais']=='Puerto Rico') {echo 'SELECTED';} ?>>Puerto Rico</option>
						<option value='Qatar' <?php if ($row_TablaUs['Pais']=='Qatar') {echo 'SELECTED';} ?>>Qatar</option>
						<option value='Reino Unido' <?php if ($row_TablaUs['Pais']=='Reino Unido') {echo 'SELECTED';} ?>>Reino Unido</option>
						<option value='República Centroafricana' <?php if ($row_TablaUs['Pais']=='República Centroafricana') {echo 'SELECTED';} ?>>Rep&uacute;blica Centroafricana</option>
						<option value='República Checa' <?php if ($row_TablaUs['Pais']=='República Checa') {echo 'SELECTED';} ?>>Rep&uacute;blica Checa</option>
						<option value='República de Sudáfrica' <?php if ($row_TablaUs['Pais']=='República de Sudáfrica') {echo 'SELECTED';} ?>>Rep&uacute;blica de Sud&aacute;frica</option>
						<option value='República Dominicana' <?php if ($row_TablaUs['Pais']=='República Dominicana') {echo 'SELECTED';} ?>>Rep&uacute;blica Dominicana</option>
						<option value='República Eslovaca' <?php if ($row_TablaUs['Pais']=='República Eslovaca') {echo 'SELECTED';} ?>>Rep&uacute;blica Eslovaca</option>
						<option value='Ruanda' <?php if ($row_TablaUs['Pais']=='Ruanda') {echo 'SELECTED';} ?>>Ruanda</option>
						<option value='Rumania' <?php if ($row_TablaUs['Pais']=='Rumania') {echo 'SELECTED';} ?>>Rumania</option>
						<option value='Rusia' <?php if ($row_TablaUs['Pais']=='Rusia') {echo 'SELECTED';} ?>>Rusia</option>
						<option value='Samoa' <?php if ($row_TablaUs['Pais']=='Samoa') {echo 'SELECTED';} ?>>Samoa</option>
						<option value='Samoa Americana' <?php if ($row_TablaUs['Pais']=='Samoa Americana') {echo 'SELECTED';} ?>>Samoa Americana</option>
						<option value='San Marino' <?php if ($row_TablaUs['Pais']=='San Marino') {echo 'SELECTED';} ?>>San Marino</option>
						<option value='Santa Lucía' <?php if ($row_TablaUs['Pais']=='Santa Lucía') {echo 'SELECTED';} ?>>Santa Luc&iacute;a</option>
						<option value='Santo Tomé y Príncipe' <?php if ($row_TablaUs['Pais']=='Santo Tomé y Príncipe') {echo 'SELECTED';} ?>>Santo Tom&eacute; y Pr&iacute;ncipe</option>
						<option value='Senegal' <?php if ($row_TablaUs['Pais']=='Senegal') {echo 'SELECTED';} ?>>Senegal</option>
						<option value='Seychelles' <?php if ($row_TablaUs['Pais']=='Seychelles') {echo 'SELECTED';} ?>>Seychelles</option>
						<option value='Sierra Leona' <?php if ($row_TablaUs['Pais']=='Sierra Leona') {echo 'SELECTED';} ?>>Sierra Leona</option>
						<option value='Singapur' <?php if ($row_TablaUs['Pais']=='Singapur') {echo 'SELECTED';} ?>>Singapur</option>
						<option value='Siria' <?php if ($row_TablaUs['Pais']=='Siria') {echo 'SELECTED';} ?>>Siria</option>
						<option value='Somalia' <?php if ($row_TablaUs['Pais']=='Somalia') {echo 'SELECTED';} ?>>Somalia</option>
						<option value='Sri Lanka' <?php if ($row_TablaUs['Pais']=='Sri Lanka') {echo 'SELECTED';} ?>>Sri Lanka</option>
						<option value='St. Pierre y Miquelon' <?php if ($row_TablaUs['Pais']=='St. Pierre y Miquelon') {echo 'SELECTED';} ?>>St. Pierre y Miquelon</option>
						<option value='Sudán' <?php if ($row_TablaUs['Pais']=='Sudán') {echo 'SELECTED';} ?>>Sud&aacute;n</option>
						<option value='Suecia' <?php if ($row_TablaUs['Pais']=='Suecia') {echo 'SELECTED';} ?>>Suecia</option>
						<option value='Suiza' <?php if ($row_TablaUs['Pais']=='Suiza') {echo 'SELECTED';} ?>>Suiza</option>
						<option value='Surinam' <?php if ($row_TablaUs['Pais']=='Surinam') {echo 'SELECTED';} ?>>Surinam</option>
						<option value='Tailandia' <?php if ($row_TablaUs['Pais']=='Tailandia') {echo 'SELECTED';} ?>>Tailandia</option>
						<option value='Taiwán' <?php if ($row_TablaUs['Pais']=='Taiwán') {echo 'SELECTED';} ?>>Taiw&aacute;n</option>
						<option value='Tanzania' <?php if ($row_TablaUs['Pais']=='Tanzania') {echo 'SELECTED';} ?>>Tanzania</option>
						<option value='Tayikistán' <?php if ($row_TablaUs['Pais']=='Tayikistán') {echo 'SELECTED';} ?>>Tayikist&aacute;n</option>
						<option value='Togo' <?php if ($row_TablaUs['Pais']=='Togo') {echo 'SELECTED';} ?>>Togo</option>
						<option value='Tonga' <?php if ($row_TablaUs['Pais']=='Tonga') {echo 'SELECTED';} ?>>Tonga</option>
						<option value='Trinidad y Tobago' <?php if ($row_TablaUs['Pais']=='Trinidad y Tobago') {echo 'SELECTED';} ?>>Trinidad y Tobago</option>
						<option value='Túnez' <?php if ($row_TablaUs['Pais']=='Túnez') {echo 'SELECTED';} ?>>T&uacute;nez</option>
						<option value='Turkmenistán' <?php if ($row_TablaUs['Pais']=='Turkmenistán') {echo 'SELECTED';} ?>>Turkmenist&aacute;n</option>
						<option value='Turquía' <?php if ($row_TablaUs['Pais']=='Turquía') {echo 'SELECTED';} ?>>Turqu&iacute;a</option>
						<option value='Tuvalu' <?php if ($row_TablaUs['Pais']=='Tuvalu') {echo 'SELECTED';} ?>>Tuvalu</option>
						<option value='Ucrania' <?php if ($row_TablaUs['Pais']=='Ucrania') {echo 'SELECTED';} ?>>Ucrania</option>
						<option value='Uganda' <?php if ($row_TablaUs['Pais']=='Uganda') {echo 'SELECTED';} ?>>Uganda</option>
						<option value='Uruguay' <?php if ($row_TablaUs['Pais']=='Uruguay') {echo 'SELECTED';} ?>>Uruguay</option>
						<option value='Uzbekistán' <?php if ($row_TablaUs['Pais']=='Uzbekistán') {echo 'SELECTED';} ?>>Uzbekist&aacute;n</option>
						<option value='Venezuela' <?php if ($row_TablaUs['Pais']=='Venezuela') {echo 'SELECTED';} ?>>Venezuela</option>
						<option value='Vietnam' <?php if ($row_TablaUs['Pais']=='Vietnam') {echo 'SELECTED';} ?>>Vietnam</option>
						<option value='Yemen' <?php if ($row_TablaUs['Pais']=='Yemen') {echo 'SELECTED';} ?>>Yemen</option>
						<option value='Yugoslavia' <?php if ($row_TablaUs['Pais']=='Yugoslavia') {echo 'SELECTED';} ?>>Yugoslavia</option>
						<option value='Zambia' <?php if ($row_TablaUs['Pais']=='Zambia') {echo 'SELECTED';} ?>>Zambia</option>
						<option value='Zimbabue' <?php if ($row_TablaUs['Pais']=='Zimbabue') {echo 'SELECTED';} ?>>Zimbabue</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Ciudad</label>
							<input type="text" name="Ciudad" id="Ciudad" class="form-control" value="<?php echo $row_TablaUs['Ciudad_nac'];?>" placeholder="Ciudad" tabindex="12" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
				</div>
				<? } 
				if ($VE_Tkt=="Tkt_dlv") { ?>
				<div class="row">
				<div class="col-md-12 col-sm-12">
					<div class="form-group">
						<label>Direccion Entrega</label>
						<input type="text" name="Direccion" id="Direccion" class="form-control" value="<?php echo $_POST['Direccion'];?>" placeholder="Direccion Entrega" tabindex="12" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
					</div>
				</div>
				</div>	
				<?	}
				if(($Rows_TablaReg["Extra1"] != "")|| ($Rows_TablaReg["Extra2"] != "")) { ?>
				<div class="row">
				<? if($Rows_TablaReg["Extra1"] != ""){	?>
				
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo $Rows_TablaReg['Extra1'];?></label>
							<input type="text" name="Extra1" id="Extra1" class="form-control" value="" placeholder="<?php echo $Rows_TablaReg['Extra1'];?>" tabindex="13" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
				<? } ?>
				<? if(trim($Rows_TablaReg["Extra2"]) != ""){	?>
					<div class="col-md-6">
						<div class="form-group">
							<label><?php echo $Rows_TablaReg['Extra2'];?></label>
							<input type="text" name="Extra2" id="Extra2" class="form-control" value="" placeholder="<?php echo $Rows_TablaReg['Extra2'];?>" tabindex="14" required oninvalid="this.setCustomValidity('Debe llenar este campo')" oninput="setCustomValidity('')"/>
						</div>
					</div>
				<? } ?>
				</div>
				<? } ?>
			<?
			if((!$_SESSION['Id_user'])||($row_EventoInfo['Waiver'] != ""))
			{ ?>	
			<div class="row">
				<div class="col-md-1">
					<div class="form-group">
                        <input type="checkbox" name="t_and_c" id="t_and_c" value="1" tabindex="13" class="form-control" required oninvalid="this.setCustomValidity('Acepte terminos y condiciones')" onclick="setCustomValidity('')">
					</div>	
				</div>
				<div class="col-md-7">
					<div class="form-group">
					 Al dar clic, usted esta de acuerdo con los <a href="#" data-toggle="modal" data-target="#t_and_c_m">Terminos & Condiciones</a> establecidos por este sitio, incluidas nuestras Cookies de uso.
				</div>
				</div>
			</div>
			<? } ?>
			</div>
			<? if ($Totalbuy>0){ ?>
			<div class="form_title">
				<h3><strong>2</strong>Metodo de pago</h3>
				<p>
					Pagar con Cuenta de Ahorros o Tarjetas de Credito Nacionales
				</p>
			</div>
			<div class="step">
			<div class="row">
					<div class="col-md-6 col-sm-6">
						<input type="radio" name="PayMethod" value="Payu" class="attrInputs" checked><img src="img/cards.png" width="267" height="43" alt="Cards" class="cards">
					</div>
			</div>
			<hr>
			<? if ($row_EventoInfo['BankTransfer']==1) { ?>
			<h4><input type="radio" name="PayMethod" value="BankTransfer" class="attrInputs">&nbsp;Transferencia Bancaria</h4>
			<p>
				<b>Banco Davivienda Cta. Corriente 4550 6999 6581 Evenpot SAS</b>
			</p>
			<div class="row">
					<div class="col-md-6 col-sm-6">
					<p>
						<b>Enviar soporte a contacto@evenpot.com o al whatsapp numero 320 256 0926 en un maximo de 24 horas.</b>
					</p>
					</div>
			</div>
			<? } ?>
			
			<? if ($row_EventoInfo['Paypal']==1) { ?>
			<h4>Pagar con Paypal</h4>
			<p>
				Paypal, Tarjetas internacionales VISA, MasterCard, American Express, Discover
			</p>
			<div class="row">
					<div class="col-md-6 col-sm-6">
					<p>
						<input type="radio" name="PayMethod" value="Paypal" class="attrInputs"><img src="img/paypal_bt.png" width="207" height="43" alt="Cards" class="cards">
					</p>
					</div>
			</div>
			<? } ?>
			</div>
			
			<? } ?>
			<?php if ($row_EventoInfo['Estado']=='PUB') { ?>
			<input name="Id_evento" type="hidden" value="<? echo $Id_evento;?>" />
			  <input type="hidden" name="Arrlist" value='<?php echo serialize($Arrlist);?>'>  
			  <input type="hidden" name="Alistval" value='<?php echo serialize($Alistval);?>'>  
			  <input type="hidden" name="Mth_send" value="<? echo $VE_Tkt; ?>">
			  <input type="hidden" name="Var_service" value="<? echo $Service; ?>">
			  <input type="hidden" name="Var_met" value="<? echo $Tot_Met_tkt; ?>">  
			  <input type="hidden" name="MM_insert2" value="form2">
			  <input type="hidden" name="Id_aforotkt" value="<? echo $_POST['Aforev'];?>">
			<input type="submit" name="button" id="buttonSbm" value="Confirmar" class="btn_1 green medium" />
			<? } else  { ?>
			<input type="" name="button" id="buttonSbm" value="Evento en modo prueba" class="btn_1 green medium" disabled />
			<? } ?>
			</form>
			<? } 
		} else { ?>
		<div class="box_style_1">
			<h3>No selecciono ninguna entrada</h3>
		</div>
		<? } ?>
	<? } ?>
</div>

<aside class="col-md-4">
<div class="hidden-xs panel panel-success">
<img src=" <? if($row_EventoInfo['Imagen']) 
   { echo "/".$row_EventoInfo['Imagen'];
   } else { echo "/eventimg/iconsport.jpg"; } ?>" class="img-responsive">  
</div> 
<div id="recfollow" class="box_style_1 hidden-xs panel panel-success">
	<? 
	if(isset($_SESSION['Id_user'])){
		if($Rows_FollxEve==1) 
	    $Follow="Dejar de seguir"; 
		else {
		$Follow="Seguir evento";
		}
	$classag="btn_full";
	?>
	<button  class="<? echo $classag; ?>" onclick="javascript:add_favorites(<? echo $Id_evento;?>)"><i class=" icon-heart"></i><? echo $Follow; ?></button>
	<?
	} else { ?>
	<button  class="btn_full onLogin"><i class=" icon-heart"></i>Seguir evento</button>
	<?	} 	?>	
	<a class="btn_full_outline" href="#" data-toggle="modal" data-target="#myReview">Contactar organizador</a>
</div>

	  <!-- Modal Review -->
		<div class="modal fade" id="myReview" tabindex="-1" role="dialog" aria-labelledby="myReviewLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myReviewLabel">Escriba su mensaje</h4>
					</div>
					<div class="modal-body">
						<div id="message-review">
						</div>
						<form method="post" action="/review.php" name="review_tour" id="review_tour">
						<input name="tour_name" id="tour_name" type="hidden" value="Paris Arch de Triomphe Tour">	
									<div class="form-group">
										<input name="name_review" id="name_review" type="text" placeholder="Nombre" class="form-control">
									</div>
							<!-- End row -->
								<div class="form-group">
									<input name="email_review" id="email_review" type="email" placeholder="Su correo" class="form-control">
								</div>
							<!-- End row -->
							<hr>

							<!-- End row -->
								<div class="form-group">
									<input name="subject_review" id="subject_review" type="text" placeholder="Asunto" class="form-control">
								</div>
							<div class="form-group">
								<textarea name="review_text" id="review_text" class="form-control" style="height:100px" placeholder="Escriba su mensaje"></textarea>
							</div>
							<input type="hidden" name="Id_evento" id="Id_evento" value="<? echo $Id_evento; ?>">
							<input type="hidden" name="MM_contact" value="contact2">  
							<input type="submit" value="Enviar" class="btn_1" id="submit-review">
						</form>
					</div>
				</div>
			</div>
		</div><!-- End modal review -->	
		  
</aside>
	</div><!--End row -->
</div><!--End container -->

<!-- Modal -->
<div class="modal fade" id="t_and_c_m" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">T&eacute;rminos & Condiciones</h4>
			</div>
			<div class="modal-body">
			<p><strong>T&eacute;rminos y Condiciones de Uso</strong><br />
			<? if ($row_EventoInfo['Waiver'] != "") 
			echo  $row_EventoInfo['Waiver']; 
			?> 
			<? echo  $Rows_TablaPar['Terms']; ?> 
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal" id="agree_tc" OnClick="agree()">De acuerdo</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!--footer -->
	<?php include ('footer.php'); ?>
<!--footer end-->

<div id="toTop"></div><!-- Back to top button -->
<div id="overlay"></div><!-- Mask on input focus -->   

<!-- Common scripts -->

<script src="/js2/jquery-1.11.2.min.js"></script>
<script src="/js2/common_scripts_min.js"></script>
<script src="/js2/functions.js"></script>

<!-- Date and time pickers -->
<script src="/js2/bootstrap-datepicker.js"></script>
<script>
  $('input.date-pick').datepicker('setDate', '<?php echo str_replace("-", "/", date("d-m-Y", strtotime($row_TablaUs['Birthday'])));?>');
</script>

<script src='/js/jAlert.js'></script>
<script src='/js/jAlert-functions.js'></script>

<!-- Specific scripts -->
<script language="javascript">
function agree(){
	document.getElementById("t_and_c").checked=true;
}
</script>


<script language="javascript">
function disbutton(){
    document.form1.button.disabled=true;
}
</script>
		
<script language="javascript">
function add_favorites(variable_post){    
       /// Aqui podemos enviarle alguna variable a nuestro script PHP
    //var variable_post = "HOLA";
       /// Invocamos a nuestro script PHP
    $.post("/followev.php", { variable: variable_post }, function(data){
       /// Ponemos la respuesta de nuestro script en el DIV recargado
    $("#recfollow").html(data);
    });         
}
</script>

<? if ($Totalbuy>0){ ?>
<script>
jQuery(function(){
$("#buttonSbm").click(function(){
    var isChecked = jQuery("input[name=PayMethod]:checked").val();
     if(!isChecked){
         alert('Debe seleccionar un metodo de pago');
		 return false;
     }else{
         return true;
     }
});
});
</script>
<? } ?>

<script language="javascript">
	window.addEventListener("load",function() {
  document.getElementById('form1').addEventListener("submit",function(e) {
    if((document.form1.Foto.value=="")&&(document.form1.PayMethod.value=="BankTransfer")){
	e.preventDefault(); // before the code
    /* do what you want with the form */
    // Should be triggered on form submit
	$.fn.jAlert({
	'title': 'Error',
	'content': 'Debe carga el soporte de la transferencia'
	});
	}
	else return true;
  });
});
</script>
		
<script>
		$(function(){
            //for the data-jAlerts
            $.jAlert('attach');
						
			$.alertOnClick('.onLogin', {
			  'title':'Advertencia',
			  'content':
				   'Para seguir este evento debe Registrarse o Iniciar Sesion <div align="center"><a href="/register.php" class="btn btn-success">Registrarse</a> <a href="/login.php" class="btn btn-info">Login</a></div>',
			  
			  'closeOnEsc': false
			});

		});
</script>
		
  </body>
</html>