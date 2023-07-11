<?php

namespace Website\page;

require_once '../admin/init.php';
require_once '../admin/setuppage.php';
require_once '../admin/commonfunctions.php';
require_once '../admin/SkHfV.php';

use Website\admin; // one way to do it

$SetupPage = new admin\SetupPage($_SERVER['HTTP_HOST']);
$stripeKey = admin\SkHfV::getSkHfV(); //Client/Server Keys differ!

try
{
    // TODO: --1-- need make a verified SESSION ID based on the stripe sessionID (since it's unique). Upon success, check that value, verify it has been set or something, then accept and continue. make sure it matches the newest created one or something because they change everytime they enter the cart. this is how to prevent someone adding items to the cart, then going to success and removing items.
    session_start();

    if (isset($_SESSION['cartItems']))
    {
        $cart = json_decode($_SESSION['cartItems'], true);
        // Build Query
        $sqlParams = array(); // empty
        $i = 0;
        $sqlQuery = 'select * from inventories where id in (';
        foreach ($cart as $item)
        {
            //error_log('---logging cart items from json: ' . $item['Id']);
            $sqlQuery .= ($i !== 0 ? ', ?' : ' ?');
            $i++;
            $sqlParams[] = $item['Id'];
        }
        $sqlQuery .= ')';

        error_log('query running on checkout page');

        // Consult Server for Information
        $results = admin\CommonFunctions::GetSqlBoundParams(
            $sqlQuery, $sqlParams
        );
        
        // Build Stripe Cart
        if (sizeof($results) > 0)
        {
            // Set API Key
            \Stripe\Stripe::setApiKey($stripeKey);
            $stripe = new \Stripe\StripeClient($stripeKey);

            if ($stripeKey !== '')
            {
                error_log('key is not empty. status ok.');
            }
            else
            {
                error_log('KEY IS EMPTY. THIS IS NOT OK!');
            }
            
            // Builds pricing data from localStorage cart data
            $cartLineItems = array();
            foreach ($results as $row)
            {
                $buildArray = 
                [
                    'price_data' =>
                    [
                        'currency' => 'usd',
                        'product_data' =>
                        [
                            'name' => $row['DisplayName'] . 
                            ': ' . $row['Colors'],
                        ],
                        'unit_amount' => 
                        intval($row['Price']) * 100,
                    ],
                    'quantity' => 
                    $cart[$row['id']]['DesiredQty']
                ];
                
                $cartLineItems[] = $buildArray;
            } // Foreach SQL item returned

            $session = \Stripe\Checkout\Session::create(
                [
                    'line_items' => $cartLineItems, // Array of Array Items
                    'mode' => 'payment',
                    'success_url' => 'https://www.colespottery.com/page/success.php',
                    'cancel_url' => 'https://www.colespottery.com/page/cart.php',
                    'payment_method_types' =>
                    [
                        'card'
                    ],
                    'shipping_address_collection' =>
                    [
                        'allowed_countries' =>
                        [
                            'US'
                        ],
                    ],
                    'shipping_options' =>
                    [
                        [
                            'shipping_rate_data' =>
                            [
                                'type' => 'fixed_amount',
                                'fixed_amount' =>
                                [
                                    'amount' => 0,
                                    'currency' => 'usd',
                                ],
                                'display_name' => 'Free shipping',
                                'delivery_estimate' =>
                                [
                                    'minimum' =>
                                    [
                                        'unit' => 'business_day',
                                        'value' => 5,
                                    ],
                                    'maximum' =>
                                    [
                                        'unit' => 'business_day',
                                        'value' => 10,
                                    ],
                                ]
                            ]
                        ],
                    ],
                ] // end of stripe object
            ); // stripe session
            
            $_SESSION['sessionID'] = $session->id;
        } // if size > 0
    } // if session var isset
    else
    {
        header('Location: https://www.colespottery.com/page/cart.php');
        die();
    } // else
    session_write_close();
} // try
catch (\Throwable $e)
{
    // Set email send from and send email
    $headers = array(
        'From' => 'ERROR@colespottery.com',
        'Reply-To' => 'cole@colespottery.com',
        'X-Mailer' => 'PHP/' . phpversion()
    );

    $retval = mail(
        "cole@colespottery.com", // to
        "ERROR IN CHECKOUT!!!", // subject
        "Debug the error in checkout page", // message
        $headers
     ); // metadata stuff
     
    session_write_close();
    error_log('exception occured in checkout: ' . $e);
    header('Location: https://www.colespottery.com/page/error.php');
    die();
}

echo '
<script src="https://js.stripe.com/v3/">
</script>

<script>

function run(){
    try
    {
        //e.preventDefault();
        var stripe = Stripe("' . admin\SkHfV::getSkJs() . '");
        if ("1" === "1"){
            stripe.redirectToCheckout({
                sessionId: "' . $session->id . '",
            });
        }
        else{
            window.location.href = "https://www.colespottery.com";
        }
    }
    catch(ex)
    {
        
    }
}
run();

</script>
';
?>