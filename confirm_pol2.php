<?php require_once('connections/dbsel.php'); ?>
<?php include_once('model_tickets.php'); ?>
<?php include_once('model_user.php'); ?>
<?php include_once('model_eventos.php'); ?>
<?php include_once('model_levels.php'); ?>
<?php include_once('model_ticketmail.php'); ?>
<?php
/*
$Id_user = $_SESSION["Id_user"];
$verifica = $_SESSION["verifica"];
$Id_evento = $_GET['Id_evento'];
$Nombre=$_GET['Nombre'];
$Email=$_GET['Email'];
$Id_ticketxevent=$_GET['Tipo_ticket'];
$Date_ticket=$_GET['Date_ticket'];
*/

$query2 = "UPDATE transacxevento set Extra1_POL = '1'";
$result2 = mysql_query($query2);

$MerchantId = $_POST['merchant_id'];
$CodigoRta_POL = $_POST['state_pol'];
$Riesgo_POL = $_POST['risk'];
$Estado_POL = $_POST['polResponseCode'];
$Ref_venta = $_POST['referenceCode'];
$Referencia_POL = $_POST['reference_pol'];
$Firma_POL = $_POST['signature'];
$MedioPago_POL = $_POST['polPaymentMethod'];
$TipoPago_POL = $_POST['polPaymentMethodType'];
$Cuotas_POL = $_POST['installmentsNumber'];
$Valor_POL = $_POST['TX_VALUE'];
$IVA_POL = $_POST['TX_TAX'];
$Email_POL = $_POST['buyerEmail'];
$FecTran_POL = $_POST['processingDate'];
$Moneda_POL = $_POST['currency'];
$Cus_POL = $_POST['cus'];
$BancoPSE_POL = $_POST['pseBank'];
$Idioma_POL = $_POST['lng'];
$Descripcion = $_POST['description'];
$LapResponseCode = $_POST['lapResponseCode']; 
//Medio de pago con el cual se hizo el pago por ejemplo VISA.
$LapPaymentMethod = $_POST['lapPaymentMethod']; 
//Tipo de medio de pago con el que se realiza por ejemplo CREDIT_CARD.
$LapPaymentMethodType = $_POST['lapPaymentMethodType']; 
$LapTransactionState = $_POST['lapTransactionState'];
//	Descripción del estado de la transacción.
$Mensaje_POL = $_POST['message']; 
$Extra1_POL = $_POST['extra1'];
$Extra2_POL = $_POST['extra2'];
//Código de autorización de la venta.
$CodAut_POL = $_POST['authorizationCode']; 
//Identificador generado por PSE.
$CicloPSE_POL = $_POST['pseCycle']; 
$PSE_Ref1 = $_POST['pseReference1'];
$PSE_Ref2 = $_POST['pseReference2'];
$PSE_Ref3 = $_POST['pseReference3'];
//Identificador de la transacción.
$TransactionId = $_POST['transactionId']; 
//Código de seguimiento de la venta en el sitio del comercio.
$TrazabilityCode = $_POST['trazabilityCode']; 

$llave_encripcion = "11e2c92d515";
$firma_cadena = "$llave_encripcion~$MerchantId~$Ref_venta~$Valor_POL~$Moneda_POL~$CodigoRta_POL";
$firmacreada = md5($firma_cadena);//firma que generaron ustedes

$Fecha=date("Y-n-j").date(" H:i:s");

//if(strtoupper($Firma_POL)!=strtoupper($firmacreada)){
//comparacion de las firmas para comprobar que los datos si vienen de Pagosonline

$Status_user='2';
confirma_pago($Status_user, $Estado_POL, $Riesgo_POL, $CodigoRta_POL, $Ref_venta, $Referencia_POL, $Extra1_POL, $Extra2_POL, $LapPaymentMethodType, 
$LapPaymentMethod, $Cuotas_POL, $Valor_POL, $IVA_POL, $Moneda_POL, $FecTran_POL, $CodAut_POL, $LapResponseCode, 
$TransactionId, $TrazabilityCode, $Cus_POL, $BancoPSE_POL, $Email_POL, $Mensaje_POL, $CicloPSE_POL, 
$PSE_Ref1, $PSE_Ref2, $PSE_Ref3, $Fecha);
//}

//if (($Id_user) && ($Estado_POL != 4) && ($CodigoRta_POL != 1)){
if (($Estado_POL == 4) && ($CodigoRta_POL == 1)){

$TablaUserPago=consultar_userpago($Ref_venta);
$row_TablaUserPago = mysql_fetch_assoc($TablaUserPago);
$Id_user = $row_TablaUserPago['Id_user'];
$Id_evento = $Extra1_POL;

			$TablaTmpTkts = consultar_tickettemp($Id_user, $Id_evento, $Ref_venta);
			while ($row_TablaTmpTkts = mysql_fetch_assoc($TablaTmpTkts)){ 
			$Id_evento = $row_TablaTmpTkts['Id_evento'];
			$Id_aforoxevento = $row_TablaTmpTkts['Id_aforoxevento'];
			$Cant_ticket = $row_TablaTmpTkts['Cant_ticket'];
			$Mail_asistente = $row_TablaTmpTkts['Mail_asistente'];
			$Nom_asistente = $row_TablaTmpTkts['Nom_asistente'];
			$Id_datexevento = $row_TablaTmpTkts['Id_datexevento'];
			$Id_ticketxevento = $row_TablaTmpTkts['Id_ticketxevento'];
			$Creado=date("Y-n-j").date(" H:i:s");
			
			for($i=0; $i<$Cant_ticket; $i++){
			$code = substr(md5(uniqid(rand(), true)),0, 8);
			
			if (crear_asisxeve($Id_evento, $Id_user, $Id_datexevento, $Id_ticketxevento, $code, $Ref_venta, $Mail_asistente, $Nom_asistente, $Creado)){
			$TablaUltTik = consultar_ultmyticket($Id_evento, $Id_user, $code, $Creado);
			$row_TablaUltTik = mysql_fetch_assoc($TablaUltTik);
			$Id_asisxevento = $row_TablaUltTik['Id_asisxevento'];
			sub_aforo($Id_aforoxevento, $Cant_ticket);
			
					}
				}
			}
			
			crear_activity(2, $Id_asisxevento, $_SESSION["Id_user"], $Id_evento, $Creado);
			
			$query = 'SELECT l.*, u.Events
FROM user u, level l WHERE u.Id_user=\''.$Id_user.'\' AND u.Points BETWEEN l.Puntos_ini AND l.Puntos_fin';
            $result= mysql_query($query);
   		    $row = mysql_fetch_array($result);
			if($row){
		//	$TablaEveDate = consultar_date($Id_datexevento);
		//	$row_EventoDate = mysql_fetch_assoc($TablaEveDate);
			$query_eve = 'SELECT *
			FROM evento WHERE Id_evento=\''.$Id_evento.'\' ';
            $result_eve= mysql_query($query_eve);
   		    $row_even = mysql_fetch_array($result_eve);
			}
			
			$result = mysql_query('UPDATE user SET Events = Events+1 WHERE Id_user=\''.$Id_user.'\'');
   		    $result= mysql_query($query);			
			$query = 'UPDATE user SET Points = Points+5 WHERE Id_user=\''.$Id_user.'\'';
           	$result= mysql_query($query);

			send_ticketmail($Id_user, $Id_evento, $Ref_venta);			
		}
}
?>