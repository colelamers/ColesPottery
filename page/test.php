<?php

namespace Website\page;

require_once '../admin/init.php';
require_once '../admin/setuppage.php';
require_once '../admin/commonfunctions.php';
require_once '../admin/SkHfV.php';

use Website\admin;


/**
* Purpose of this code is a testing ground for logging data and var_dump stuff.
* using it to test things simply without affecting other aspects of my code.
*
*/


$SetupPage = new admin\SetupPage($_SERVER['HTTP_HOST']);
$stripeKey = admin\SkHfV::getTestKeyServerSide();


// Set API Key
\Stripe\Stripe::setApiKey($stripeKey);
$stripe = new \Stripe\StripeClient($stripeKey);


session_start();

error_log('SessionID: ' . $_GET['sessionID']);
//$_SESSION['sessionID']
$session = $stripe->checkout->sessions->retrieve(
    $_GET['sessionID']
  // this is the session ID  = 'cs_test_a1TucSjxGD3Tjb9XlEQZhZGqXIQjmSfLFXc1rFnSiN87rx0z9HLKGJS7DK',
);

error_log('SessionID: ' . $session->id);
error_log('PaymentIntent: ' . $session->payment_intent);
error_log('PaymentStatus: ' . $session->payment_status);
echo 'now if checking';

if (isset($_SESSION['cartItems']) && $_SESSION['cartItems'] !== '')
{
    echo 'cartitems are set and cartitem is not nothing\n<br>';
}

if (isset($_GET['sessionID']))
{
    echo 'cartitems are set and cartitem is not nothing\n<br>';
}

if ($session->payment_status === 'unpaid')
{
    echo 'payment is unpaid\n';
}
else{
    echo '\n $$$$$$$$$$$$$$$$$$$is it paid what?...<br>';
    echo $session->payment_status;
}


session_write_close();




























?>