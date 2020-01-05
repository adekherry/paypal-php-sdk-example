<?php

/**
 * Further reading : https://developer.paypal.com/docs/api/quickstart/create-billing-plan/
 */

use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use PayPal\Api\Plan;
use PayPal\Api\ShippingAddress;
use PayPal\Exception\PayPalConnectionException;

$createdPlan = require_once __DIR__ . '/recurring-billing-plan.php';

$dateTimeObj= new DateTime();
$dateTimeObj->add( new DateInterval( 'PT1H' ) );

/**
 * Begin agreement
 */
$agreement = new Agreement();
$agreement->setName( 'Base Agreement' )
    ->setDescription( 'Basic Agreement' )
    ->setStartDate( $dateTimeObj->format( DATE_ISO8601 ) );

/**
 * Set plan id
 */
$plan = new Plan();
$plan->setId( $createdPlan->getId() );
$agreement->setPlan( $plan );

/**
 * Payment method
 */
$payer = new Payer();
$payer->setPaymentMethod( 'paypal' );
$agreement->setPayer( $payer );

/**
 * Adding shipping details
 */
$shippingAddress = new ShippingAddress();
$shippingAddress->setLine1( '111 First Street' )
    ->setCity( 'Saratoga' )
    ->setState( 'CA' )
    ->setPostalCode( '95070' )
    ->setCountryCode( 'US' );

$agreement->setShippingAddress( $shippingAddress );

try {

    // Create agreement
    $agreement = $agreement->create( $apiContext );

    // Extract approval URL to redirect user
    $approvalUrl = $agreement->getApprovalLink();

    echo $approvalUrl;

} catch ( PayPalConnectionException $ex ) {
    echo $ex->getCode();
    echo $ex->getData();
    die( $ex );
} catch ( Exception $ex ) {
    die( $ex );
}