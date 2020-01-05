<?php

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;

require_once __DIR__ . '/bootstrap.php';

if ( isset($_GET['success']) && $_GET['success'] == 'true' ) {
    
    // Get payment object by passing paymentId
    $paymentId = $_GET['paymentId'];

    $payment = new Payment();
	$payment = $payment::get( $paymentId, $apiContext );

	// Execute payment with payer ID
	$payerId = $_GET['PayerID'];
	$execution = new PaymentExecution();
	$execution->setPayerId( $payerId );

try {
		// Execute payment
		$result = $payment->execute( $execution, $apiContext );

		jlog( $result );
	} catch ( PayPalConnectionException $ex ) {
		echo $ex->getCode();
		echo $ex->getData();
		die( $ex );
	} catch ( Exception $ex ) {
		die( $ex );
	}
}