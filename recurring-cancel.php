<?php

use PayPal\Api\Agreement;
use PayPal\Api\AgreementStateDescriptor;

require_once __DIR__ . '/bootstrap.php';

$agreementStateDescriptor = new AgreementStateDescriptor();
$agreementStateDescriptor->setNote('Canceling the agreement');

/** @var Agreement $createdAgreement */
$createdAgreement = 'I-C30Axxxxxxxxxx';

try {

    $agreement = Agreement::get( $createdAgreement, $apiContext );
    $agreement->cancel( $agreementStateDescriptor, $apiContext );

} catch ( Exception $ex ) {
    exit( 1 );
}