<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/common.php';

/**
 * PayPal Business Account
 * 
 * https://github.com/paypal/PayPal-PHP-SDK/wiki/Making-First-Call
 *
 * This is sample data
 * 
 */
define( 'CLIENT_ID', 'AYSq3RDGsmBLJE-otTkBtM-jBRd1TCQwFf9RGfwddNXWz0uFU9ztymylOhRS' );
define( 'CLIENT_SECRET', 'EGnHDxD_qRPdaLdZz8iCr8N7_MzF-YHPTkjs6NKYQvQSBngp4PTTVWkPZRbL' );

/**
 * Authentication the credential info
 */
$apiContext = new ApiContext(
    new OAuthTokenCredential(
        CLIENT_ID,
        CLIENT_SECRET
    )
);
