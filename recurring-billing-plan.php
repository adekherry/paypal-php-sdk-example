<?php

/**
 * Further reading : https://developer.paypal.com/docs/api/quickstart/create-billing-plan/
 */

use PayPal\Api\ChargeModel;
use PayPal\Api\Currency;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\Plan;
use PayPal\Api\Patch;
use PayPal\Api\PatchRequest;
use PayPal\Common\PayPalModel;
use PayPal\Exception\PayPalConnectionException;

require_once __DIR__ . '/bootstrap.php';

/**
 * Create a new billing plan
 */
$plan = new Plan();
$plan->setName( 'T-Shirt of the Day Club Plan' )
    ->setDescription( 'Template creation.' )
    ->setType( 'fixed' );

/**
 * Set billing plan definitions
 * 
 * Kalo mau make infinite cycle, di setType plan pake INFINITE
 */
$paymentDefinition = new PaymentDefinition();
$paymentDefinition->setName( 'Regular Payments' )
    ->setType( 'REGULAR' )
    ->setFrequency( 'Day' )
    ->setFrequencyInterval( '1' )
    ->setCycles( '10' )
    ->setAmount(
        new Currency(
            array(
                'value' => 100,
                'currency' => 'USD'
            )
        )
    );

/**
 * Set charge models
 */
$chargeModel = new ChargeModel();
$chargeModel->setType( 'SHIPPING' )
    ->setAmount(
        new Currency(
            array(
                'value' => 10,
                'currency' => 'USD'
            )
        )
    );

$paymentDefinition->setChargeModels(
    array( $chargeModel )
);

/**
 * Set merchant preferences
 */
$baseurl = $actual_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$baseurl = str_replace( basename( $_SERVER['PHP_SELF'] ), '', $baseurl );

$merchantPreferences = new MerchantPreferences();
$merchantPreferences->setReturnUrl( $baseurl . 'recurring-execute.php?success=true' )
    ->setCancelUrl( $baseurl . 'recurring-execute.php?success=false' )
    ->setAutoBillAmount( 'yes' )
    ->setInitialFailAmountAction( 'CONTINUE' )
    ->setMaxFailAttempts( '0' )
    ->setSetupFee(
        new Currency(
            array(
                'value' => 99, 
                'currency' => 'USD'
            )
        )
    );

$plan->setPaymentDefinitions( 
    array( $paymentDefinition ) 
);
$plan->setMerchantPreferences( $merchantPreferences );

/**
 * create plan
 */
try {
    $createdPlan = $plan->create( $apiContext );

    try {
        $patch = new Patch();
        $value = new PayPalModel( '{"state":"ACTIVE"}' );

        $patch->setOp( 'replace' )
            ->setPath( '/' )
            ->setValue( $value );

        $patchRequest = new PatchRequest();
        $patchRequest->addPatch( $patch );
        $createdPlan->update( $patchRequest, $apiContext );
        $createdPlan = Plan::get( $createdPlan->getId(), $apiContext );

        return $createdPlan;

    } catch ( PayPalConnectionException $ex ) {
        echo $ex->getCode();
        echo $ex->getData();
        die( $ex );
    } catch ( Exception $ex ) {
        die( $ex );
    }
} catch ( PayPalConnectionException $ex ) {
    echo $ex->getCode();
    echo $ex->getData();
    die( $ex );
} catch ( Exception $ex ) {
    die( $ex );
}
