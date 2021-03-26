<?php
require_once ("paypalfunctions.php");
require_once('connections/dbsel.php');
require_once("model_eventos.php");
require_once("model_tickets.php");
require('fpdf/fpdf.php');
require('phpqrcode/qrlib.php');

$ValorUS=1980;
session_start();  
$verifica = 1;  
$_SESSION["verifica"] = $verifica;  

mysql_select_db($database_dbsel, $dbsel);

$Id_evento=$_GET['Id_evento'];
$TablaEveInfo = consultar_eventoinfo($Id_evento);
$row_EventoInfo = mysql_fetch_assoc($TablaEveInfo);

$TablaEveDate = consultar_date($Id_evento);
$row_EventoDate = mysql_fetch_assoc($TablaEveDate);

$Nombre=$_GET['Nombre'];
$Email=$_GET['Email'];
$Date_ticket=$_GET['Date_ticket'];
$Tipo_ticket=$_GET['Tipo_ticket'];

$TablaTkts = consultar_ticketxevent($Tipo_ticket, $Id_evento);

$row_TablaTkts = mysql_fetch_assoc($TablaTkts);
$ValorTkt = $row_TablaTkts['Valor_tkt'];

$PaymentOption = "PayPal";
if ( $PaymentOption == "PayPal")
{
        // ==================================
        // PayPal Express Checkout Module
        // ==================================

	
	        
        //'------------------------------------
        //' The paymentAmount is the total value of 
        //' the purchase.
        //'
        //' TODO: Enter the total Payment Amount within the quotes.
        //' example : $paymentAmount = "15.00";
        //'------------------------------------

        $paymentAmount =  round(($ValorTkt/$ValorUS), 2);
        
        
        //'------------------------------------
        //' The currencyCodeType  
        //' is set to the selections made on the Integration Assistant 
        //'------------------------------------
        $currencyCodeType = "USD";
        $paymentType = "Sale";

        //'------------------------------------
        //' The returnURL is the location where buyers return to when a
        //' payment has been succesfully authorized.
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $returnURL = "http://www.evenpot.com/orderconfirm.php";

        //'------------------------------------
        //' The cancelURL is the location buyers are sent to when they hit the
        //' cancel button during authorization of payment during the PayPal flow
        //'
        //' This is set to the value entered on the Integration Assistant 
        //'------------------------------------
        $cancelURL = "http://www.evenpot.com/buy_tickets.php?Id_evento=".$Id_evento;

        //'------------------------------------
        //' Calls the SetExpressCheckout API call
        //'
        //' The CallSetExpressCheckout function is defined in the file PayPalFunctions.php,
        //' it is included at the top of this file.
        //'-------------------------------------------------

        
		$items = array();
		$items[] = array('name' => 'Item Name', 'amt' => $paymentAmount, 'qty' => 1);
	
		//::ITEMS::
		
		// to add anothe item, uncomment the lines below and comment the line above 
		// $items[] = array('name' => 'Item Name1', 'amt' => $itemAmount1, 'qty' => 1);
		// $items[] = array('name' => 'Item Name2', 'amt' => $itemAmount2, 'qty' => 1);
		// $paymentAmount = $itemAmount1 + $itemAmount2;
		
		// assign corresponding item amounts to "$itemAmount1" and "$itemAmount2"
		// NOTE : sum of all the item amounts should be equal to payment  amount 

		$resArray = SetExpressCheckoutDG( $paymentAmount, $currencyCodeType, $paymentType, 
												$returnURL, $cancelURL, $items );

        $ack = strtoupper($resArray["ACK"]);
        if($ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING")
        {
                $token = urldecode($resArray["TOKEN"]);
                 RedirectToPayPalDG( $token );
        } 
        else  
        {
                //Display a user friendly Error on the page using any of the following error information returned by PayPal
                $ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
                $ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
                $ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
                $ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
                
                echo "SetExpressCheckout API call failed. ";
                echo "Detailed Error Message: " . $ErrorLongMsg;
                echo "Short Error Message: " . $ErrorShortMsg;
                echo "Error Code: " . $ErrorCode;
                echo "Error Severity Code: " . $ErrorSeverityCode;
        }
}

?>
