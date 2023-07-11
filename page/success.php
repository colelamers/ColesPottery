<?php

namespace Website\page;

require_once '../admin/init.php';
require_once '../admin/setuppage.php';
require_once '../admin/commonfunctions.php';
require_once '../admin/SkHfV.php';

use Website\admin; // one way to do it


$SetupPage = new admin\SetupPage($_SERVER['HTTP_HOST']);
$stripeKey = admin\SkHfV::getTestKeyServerSide();

/*
* The following code detects if the session storage is still existant. If so,
* it processes updates to the database for the bought items. Then it clears out
* the Session Storage so that they need to process another payment in order to
* potentially affect the sql database.
*/
session_start();

// TODO: --3-- comeback at a later date to clean this up and make the logic far more "secure" in terms of error catching. good enough for now.

try
{
    // Set API Key
    \Stripe\Stripe::setApiKey($stripeKey);
    $stripe = new \Stripe\StripeClient($stripeKey);

    // TODO: --3-- try wrapping this in an isset someday. bothers me but whatever.
    $retrievedSessionInfo = $stripe->checkout->sessions->retrieve(
        $_SESSION['sessionID']
    );


    error_log();
    // Verify Cart and SessionID; SessionID must be 'paid' when verified from stripe
    if ((isset($_SESSION['cartItems']) && $_SESSION['cartItems'] !== '')
        && $retrievedSessionInfo->payment_status === 'paid')
    {
        $cart = json_decode($_SESSION['cartItems'], true);
        
        $orderEmailDescription = '';
        $selectParams = array(); // empty
        $i = 0;
        $selectQuery = 'select id, Qty from inventories where id in (';
        foreach ($cart as $item)
        {
            $selectQuery .= ($i !== 0 ? ', ?' : ' ?');
            $i++;
            $selectParams[] = $item['Id'];
            
            // Construct Order Email Description
            $orderEmailDescription .= "OrderID: ";
            $orderEmailDescription .= $retrievedSessionInfo->payment_intent;
            $orderEmailDescription .= " | Item ID: " . $item['Id'];
            $orderEmailDescription .= " | Qty: " . $item['DesiredQty'] . " |";
            $orderEmailDescription .= "\n\n";
        }
        $selectQuery .= ')';
        
        $headers = array(
            'From' => 'ORDERPLACED@colespottery.com',
            'Reply-To' => 'cole@colespottery.com',
            'X-Mailer' => 'PHP/' . phpversion()
        );

        $retval = mail(
            "cole@colespottery.com", // to
            "ORDER PLACED", // subject
            $orderEmailDescription, // message
            $headers
        ); // metadata stuff
        
        
        error_log('success page query being run');
        $inventoryResults = admin\CommonFunctions::GetSqlBoundParams($selectQuery, $selectParams);

        foreach ($inventoryResults as $row)
        {
            // Build Query
            $updateParams = array(); // empty
            $updateQuery = 'update inventories set Qty = ? where id = ?';

            $difference = intval($row['Qty']) - intval($cart[$row['id']]['DesiredQty']);

            error_log('sqlId = ' . $row['id']);
            error_log('sqlQty = ' . $row['Qty']);
            error_log('desiredQty = ' . $cart[$row['id']]['DesiredQty']);
            error_log('difference = ' . $difference);

            if ($difference < 0)
            {
                $difference = 0;
            }
            $updateParams[] = $difference;
            $updateParams[] = $cart[$row['id']]['Id'];

            //error_log($updateQuery);

            $updateResults = admin\CommonFunctions::GetSqlBoundParams($updateQuery, $updateParams);
        }
        unset($_SESSION['cartItems']);
        error_log('cleared session storage = ' . isset($_SESSION['cartItems']));
    } // if the session var still exists
    else
    {
        
        error_log('SessionID: ' . $_SESSION['sessionID']);
        error_log('PaymentStatus: ' . $session->payment_status);
        error_log('cart: session storage = ' . isset($_SESSION['cartItems']));
        error_log('sessionID: session storage = ' . isset($_SESSION['sessionID']));

        header('Location: https://www.colespottery.com/');
        die();
    }
    session_write_close();
} // try
catch (\Throwable $e)
{
    session_write_close();
    error_log('exception occured in checkout: ' . $e);
    header('Location: https://www.colespottery.com/page/error.php');
    die();
}
?>

 <!DOCTYPE html>
 <html lang="en">
     <head>

       <?php

        echo $SetupPage->webpage_meta;
        // Imports CSS
        echo $SetupPage->webpage_css;
            
       ?>

     </head>

     <body>

         <!--HEADER-->
        <?php
        
        echo $SetupPage->webpage_header;
            
        ?>

           <!-- FEATURETTES -->
           <div class="container marketing">
             <hr class="featurette-divider">
             <p id="transactioncode" description="Transaction ID for your records"
             style="font-size:30px; background-color: darkgrey;">
                 Please retain this ID for your records.
                 <br>
                 Transaction ID: <?php echo $retrievedSessionInfo->payment_intent;?>
             </p>
             <p style="font-size:30px;">
               Thank you for your payment and we appreciate your business! We'll get your pottery sent out to you as soon as possible!
               <br>
               If you have any questions, please email
               <a href="mailto:cole@colespottery.com">cole@colespottery.com</a>.
             </p>

             <hr class="featurette-divider">
         </div>


            <?php

                // Sets the Footer
                echo $SetupPage->webpage_footer;

                // Sets Scripts
                echo $SetupPage->webpage_scripts;

            ?>

             <script>
             // Clear Client cart
             addLoadEvent(function(){
                 localStorage.removeItem("cart");
             })
             </script>
        </body>
</html>