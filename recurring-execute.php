<?php

use PayPal\Api\Agreement;
use PayPal\Exception\PayPalConnectionException;

require_once __DIR__ . '/bootstrap.php';

if ( isset($_GET['success']) && $_GET['success'] == 'true' ) {

    $token = $_GET['token'];
    $agreement = new Agreement();

    try {
        // Execute agreement
        $result = $agreement->execute( $token, $apiContext );

        jlog( $result );
    } catch ( PayPalConnectionException $ex ) {
        echo $ex->getCode();
        echo $ex->getData();
        die( $ex );
    } catch ( Exception $ex ) {
        die( $ex );
    }
} else {
    echo 'user canceled agreement';
}