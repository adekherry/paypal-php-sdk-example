<?php

use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Exception\PayPalConnectionException;

require_once __DIR__ . '/bootstrap.php';

/**
 * Payment method
 */
$payer = new Payer();
$payer->setPaymentMethod( 'paypal' );

/**
 * Pricing
 */
$amount = new Amount();
$amount->setTotal( '5.00' );
$amount->setCurrency( 'USD' );

$transaction = new Transaction();
$transaction->setAmount( $amount );

/**
 * Redirect URL & payment
 */
$baseurl = $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$baseurl = str_replace( basename( $_SERVER['PHP_SELF'] ), '', $baseurl );

$redirectUrls = new RedirectUrls();
$redirectUrls->setReturnUrl( $baseurl . 'payment-execute.php?success=true' )
	->setCancelUrl( $baseurl . 'payment-execute.php?success=false' );

$payment = new Payment();
$payment->setIntent( 'sale' )
    ->setPayer( $payer )
    ->setTransactions( array( $transaction ) )
    ->setRedirectUrls( $redirectUrls );

/**
 * Create payment
 */
try {
    $payment->create( $apiContext );

    jlog( $payment );
}
catch ( PayPalConnectionException $ex ) {
    // This will print the detailed information on the exception.
    echo $ex->getData();
}

