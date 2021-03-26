<?php require_once('connections/dbsel.php'); ?>
<?php include('connections/comun.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_levels.php'); ?>
<?php include_once('model_ticketmail.php'); ?>
<?php

$asignacion = "";
foreach($_POST as $nombre_campo => $valor){ 
   $asignacion = $nombre_campo."=".$valor.";".$asignacion; 
}
$query = "UPDATE parametro SET Test = '$asignacion' where ID=1";
$result = mysql_query($query);

$MerchantId = $_POST['merchant_id'];
$CodigoRta_POL = $_POST['response_code_pol'];
$Riesgo_POL = $_POST['risk'];
$Estado_POL = $_POST['state_pol'];
$Ref_venta = $_POST['reference_sale'];
$Referencia_POL = $_POST['reference_pol'];
$Firma_POL = $_POST['sign'];
$MedioPago_POL = $_POST['payment_method'];
$TipoPago_POL = $_POST['payment_method_type'];
$Cuotas_POL = $_POST['installments_number'];
$Valor_POL = $_POST['value'];
$Valor_POL = number_format($Valor_POL, 1, '.', '');
$IVA_POL = $_POST['tax'];
$Commision_POL = $_POST['commision_pol'];
$Email_POL = $_POST['email_buyer'];
$FecTran_POL = $_POST['transaction_date'];
$Moneda_POL = $_POST['currency'];
$Cus_POL = $_POST['cus'];
$BancoPSE_POL = $_POST['pse_bank'];
$Idioma_POL = $_POST['lng'];
$Descripcion = $_POST['description'];
$LapResponseCode = $_POST['response_message_pol']; 
//Medio de pago con el cual se hizo el pago por ejemplo VISA.
$Franchise = $_POST['payment_method_name'].substr($_POST['cc_number'], -6);  
//Tipo de medio de pago con el que se realiza por ejemplo CREDIT_CARD.
$LapPaymentMethodType = $_POST['lapPaymentMethodType']; 
$LapTransactionState = $_POST['lapTransactionState'];
$CC_Holder = $_POST['cc_holder'];
//	Descripción del estado de la transacción.
$Mensaje_POL = $_POST['response_message_pol']; 
$Extra1_POL = $_POST['extra1'];
$Extra2_POL = $_POST['extra2'];
//Código de autorización de la venta.
$CodAut_POL = $_POST['authorization_code']; 
//Identificador generado por PSE.
$CicloPSE_POL = $_POST['pseCycle']; 
$PSE_Ref1 = $_POST['pseReference1'];
$PSE_Ref2 = $_POST['pseReference2'];
$PSE_Ref3 = $_POST['pseReference3'];
//Identificador de la transacción.
$TransactionId = $_POST['transaction_id']; 
//Código de seguimiento de la venta en el sitio del comercio.
$TrazabilityCode = $_POST['transaction_bank_id']; 
$IP_User = $_POST['ip']; 

$llave_encripcion = "11e2c92d515";
//$llave_encripcion = "4Vj8eK4rloUd272L48hsrarnUA"; 
$firma_cadena = "$llave_encripcion~$MerchantId~$Ref_venta~$Valor_POL~$Moneda_POL~$Estado_POL";
$firmacreada = md5($firma_cadena);//firma que generaron ustedes

$Fecha=date("Y-n-j").date(" H:i:s");
//comparacion de las firmas para comprobar que los datos si vienen de Pagosonline
if(strtoupper($Firma_POL)==strtoupper($firmacreada)){
$Status_user='2';

confirma_pago($Status_user, $Estado_POL, $Riesgo_POL, $CodigoRta_POL, $Ref_venta, $Referencia_POL, $Firma_POL, $Extra1_POL, $Extra2_POL, $LapPaymentMethodType, 
$Franchise, $CC_Holder, $Cuotas_POL, $Valor_POL, $IVA_POL, $Commision_POL, $Moneda_POL, $FecTran_POL, $CodAut_POL, $LapResponseCode, 
$TransactionId, $TrazabilityCode, $Cus_POL, $BancoPSE_POL, $Email_POL, $Mensaje_POL, $PSE_Ref1, $PSE_Ref2, $PSE_Ref3, $IP_User, $Fecha);

//if (($Estado_POL != 4) && ($CodigoRta_POL != 1)){ //Cuando no es exitoso
if (($Estado_POL == 4) && ($CodigoRta_POL == 1)){
	$TablaUserPago=consultar_userpago($Ref_venta);
	$row_TablaUserPago = mysql_fetch_assoc($TablaUserPago);
	$Id_user = $row_TablaUserPago['Id_user'];
	$Id_evento = $Extra1_POL;

			$TablaAsis = check_asisxevento($Id_user, $Id_evento, $Ref_venta);
			$rows_TablaAsis = mysql_num_rows($TablaAsis);
			
		if ($rows_TablaAsis==0){
			$TablaTmpTkts = consultar_tickettemp($Id_user, $Id_evento, $Ref_venta);
			while ($row_TablaTmpTkts = mysql_fetch_assoc($TablaTmpTkts)){
			$Id_evento = $row_TablaTmpTkts['Id_evento'];
			$Id_aforoxevento = $row_TablaTmpTkts['Id_aforoxevento'];
			$Cant_ticket = $row_TablaTmpTkts['Cant_ticket'];
			$Mail_asistente = $row_TablaTmpTkts['Mail_asistente'];
			$Nom_asistente = $row_TablaTmpTkts['Nom_asistente'];
			$Tel_asistente = $row_TablaTmpTkts['Tel_asistente'];
			$Id_datexevento = $row_TablaTmpTkts['Id_datexevento'];
			$Id_ticketxevento = $row_TablaTmpTkts['Id_ticketxevento'];
			$Creado=date("Y-n-j").date(" H:i:s");
			$Type_ID = $row_TablaTmpTkts['Type_ID'];
			$Num_ID = $row_TablaTmpTkts['Num_ID'];
			$Gender = $row_TablaTmpTkts['Gender'];
			$Birthday = $row_TablaTmpTkts['Birthday'];
			$Institution = $row_TablaTmpTkts['Institution'];
			$Profesion = $row_TablaTmpTkts['Profesion'];
			$Pais = $row_TablaTmpTkts['Pais'];
			$Ciudad = $row_TablaTmpTkts['Ciudad'];
			$Direccion = $row_TablaTmpTkts['Direccion'];
			$Extra1 = $row_TablaTmpTkts['Extra1'];
			$Extra2 = $row_TablaTmpTkts['Extra2'];
			$Row = $row_TablaTmpTkts['Row'];
			$Seat = $row_TablaTmpTkts['Seat'];
			$Mth_send = $row_TablaTmpTkts['Mth_send'];
			
			for($i=0; $i<$Cant_ticket; $i++){
			$code = substr(md5(uniqid(rand(), true)),0, 8);
			
			if (crear_asisxeve($Id_evento, $Id_user, $Id_datexevento, $Id_ticketxevento, $code, $Ref_venta, $Row, $Seat, $Mail_asistente, $Nom_asistente, $Tel_asistente, $Type_ID, $Num_ID, 
			$Gender, $Birthday, $Institution, $Profesion, $Pais, $Ciudad, $Direccion, $Extra1, $Extra2, $Creado)){
			
			$queryseat = "UPDATE seats SET State='S' WHERE Id_event='$Id_evento' AND Row='$Row' AND Seat='$Seat' AND State='B'";
			mysql_query($queryseat);
			
			sub_aforo($Id_aforoxevento, "1");	
					}
				}
			}
			if($Mth_send=='Mail_tkt') {
			send_ticketmail($Ref_venta);
			$y = update_sendmail($Ref_venta);			
			}
			else if ($Mth_send=='Tkt_dlv') {
			send_recibo($Ref_venta);
			}
			if($Id_user!=0){
				$TablaUltTik = consultar_ultmyticket($Id_evento, $Id_user, $code, $Creado);
				$row_TablaUltTik = mysql_fetch_assoc($TablaUltTik);
				$Id_asisxevento = $row_TablaUltTik['Id_asisxevento'];
				
				crear_activity(2, $Id_asisxevento, $Id_user, $Id_evento, $Creado);
				
				$result = mysql_query('UPDATE user SET Events = Events+1 WHERE Id_user=\''.$Id_user.'\'');		
				$query = 'UPDATE user SET Points = Points+5 WHERE Id_user=\''.$Id_user.'\'';			
			}
		}
	}
}
?>