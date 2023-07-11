<?php

namespace Website\page;

require_once '../admin/setuppage.php';
use Website\admin; // one way to do it
$SetupPage = new admin\SetupPage($_SERVER['HTTP_HOST']);

//error_log('POST storage is ----- ' . isset($_POST['cartItems']) . "-----");

// Set Session Storage
if (isset($_POST['cartItems']))
{
    session_start();
    $_SESSION['cartItems'] = $_POST['cartItems'];
    session_write_close();
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
        <div class="row">
            <h2 style="color: red;">
                Attention: Only shipping to the contiguous lower 48 States.
            </h2>
        </div>
        
        <div class="row">
            <h2>
                We utilize Stripe for our checkout so your transaction is safe and secure!
            </h2>
        </div>
        
        <div class="row" style="margin-bottom: 20px">
            <div class="col-9">
            </div>

            <div class="col-3">
                <input id="checkout-button" class="btn btn-primary sm"
                type="button" style="white-space: normal; float: right; width: 150px;"
                value="Proceed To Checkout" name="checkout-button">
            </div>
        </div>

        <div class="row">
            <div class="col-10" style="float: right;">
                <h2>
                    TOTAL:
                </h2>
            </div>
            <div class="col-2" >
                <h2 id="idSumTotal" style="float: right;">
                    ~value~
                </h2>
            </div>
        </div>

        <div id="idMainRow">
        </div>

        <hr class="featurette-divider">
    </div>


        <?php

            // Sets the Footer
            echo $SetupPage->webpage_footer;

            // Sets Scripts
            echo $SetupPage->webpage_scripts;

        ?>

        <script>

        <?php
        /* StoreJsonInventory first to build local localStorage
         * BuildCart second to apply html
         * DisplayTotal third to show proper amount
         */?>
        addLoadEvent(StoreJsonInventory);
        addLoadEvent(BuildCart);
        addLoadEvent(DisplayTotal);
        addLoadEvent(SetEventListeners);
        addLoadEvent(EnsureCartNotEmpty)
        
        $("#checkout-button").on("click", function(e){
            let item = localStorage.getItem("cart");
            $.ajax({
                "url": "https://www.colespottery.com/page/cart.php",
                "method": "POST",
                "data": {
                     "cartItems": item
                 },
                "async": false,
                "success": function(){
                    window.location.href = "https://www.colespottery.com/page/checkout.php";
                }
            });
        });
        
        function EnsureCartNotEmpty()
        {
            try
            {            
                var cart = JSON.parse(localStorage.getItem("cart"));
                if (cart === null)
                {
                    $("#checkout-button").remove();
                }
                else if (!(Object.keys(cart).length > 0))
                {
                    $("#checkout-button").remove();
                }
            }
            catch (exception)
            {
                $("#checkout-button").remove();
            }
        }

        <?php
        // TODO: --2-- can probably remove the onclicks and do what i did with UpdateCartQty function.
         ?>
        function RemoveFromCart(idAsKey){
            $("#cartItem" + idAsKey).remove();
            let cart = JSON.parse(localStorage.getItem("cart"));
            delete cart[idAsKey];
            localStorage.setItem("cart", JSON.stringify(cart));
            DisplayTotal();
            EnsureCartNotEmpty();
        }

        function UpdateCartQty(idAsKey){
            idAsKey = idAsKey.target.id.split("qty")[1];
            let cart = JSON.parse(localStorage.getItem("cart"));
            if (cart[idAsKey]["DesiredQty"] !== $("#qty" + idAsKey).val())
            {
                cart[idAsKey]["DesiredQty"] = $("#qty" + idAsKey).val();
            }
            //localStorage.removeItem("cart");
            localStorage.setItem("cart", JSON.stringify(cart));
        }

        function SetEventListeners(){
            $(".cartQty").on("click", DisplayTotal);
            $(".cartQty").on("keyup", DisplayTotal);
            $(".cartQty").blur(DisplayTotal);

            $(".cartQty").on("click", UpdateCartQty);
            $(".cartQty").on("keyup", UpdateCartQty);
            $(".cartQty").blur(UpdateCartQty);

            $(".removeFromCart").on("click", DisplayTotal);
            $(".removeFromCart").on("keyup", DisplayTotal);
            $(".removeFromCart").blur(DisplayTotal);
        }

        function DisplayTotal(){
            let sum = 0;
            let cartTotal = $(".cartQty");
            let inventory = JSON.parse(localStorage.getItem("allInventory"));
            for (let i = 0; i < cartTotal.length; ++i){
                let multiple = cartTotal[i].value;
                let id = cartTotal[i].id.split("qty")[1];
                sum += inventory[id]["Price"] * multiple;
            }
            $("#idSumTotal").text("$" + sum + ".00");
        }

        function BuildCart(){
            let inventory = JSON.parse(localStorage.getItem("allInventory"));
            let cart = JSON.parse(localStorage.getItem("cart"));
            let html = '';
             // Updates cart with most recent info
            for (let i in cart){
                try{
                    let tempCart = inventory[cart[i]["Id"]];

                    html += '<div id="cartItem' + tempCart["Id"];
                    html += '"class="row product-listing cart-list">';
                    html += '<div class="col-3 cart-invs">';
                    // Image 1
                    html += '<img class="invItems firImg"';
                    html += 'src="../design/images/skus/';
                    html += tempCart["Type"] + '_'  + tempCart["Sku"] + '1.jpg"';
                    html += ' description="' + tempCart["Colors"] + ' ' + tempCart["Type"] + '">';

                    //Image 2
                    html += '<img class="invItems secImg"';
                    html += 'src="../design/images/skus/';
                    html += tempCart["Type"] + '_'  + tempCart["Sku"] + '2.jpg"';
                    html += ' description="' + tempCart["Colors"] + ' ' + tempCart["Type"] + '">';

                    html += '</div>';
                    html += '<div class="col-9 cart-descriptions">';


                    //Type
                    html += '<div class="col-3">';
                    html += '<h2 class="cart-invText">' + tempCart["DisplayName"];
                    html += ': ' + tempCart["Colors"] + '</h2>';
                    html += '<span>' + tempCart["Description"] + ' </span>';
                    html += '</div>';
                    /*
                    //Color
                    html += '<h2 class="cart-invText"> Color: ';
                    html += tempCart["Colors"] + '</h2>';
                    */

                    // Price
                    html += '<div class="cart-invText col-2">';
                    html += '<h2 class="invText">Price: </h2>';
                    html += '<span style="color:#00b91d; font-size: 30px;"> $';
                    html += tempCart["Price"] + '</span>';
                    html += '</div>';

                    /*
                    if (tempCart["Volume"] != ""){
                        html += '<h2 class="cart-invText">Sizing: ';
                        html += tempCart["Volume"] + '</h2>';
                    }
                    */

                    // Quantity
                    html += '<div class="col-3">';
                    html += '<input class="cartQty" type="number" id="qty';
                    html += tempCart['Id'] + '" name="' + tempCart["Sku"];
                    html += tempCart["Type"] + 'quantity" min="1" max="';
                    html += tempCart["Qty"] + '"';
                    html += 'value=' + cart[tempCart["Id"]]["DesiredQty"] + '>';
                    html += '</div>';

                    // Remove from Cart
                    html += '<div class="col-4"><input ';
                    html += 'class="btn btn-danger sm removeFromCart" type="button"';
                    html += 'value="Remove from cart" name="removefromcart"';
                    html += 'onclick="RemoveFromCart('  + tempCart['Id'] + ')">';
                    html += '</div>';

                    // Closing Tags
                    html += '</div>';
                    html += '</div>';
                }
                catch (e){
                    delete cart[cart[i]["Id"]];
                }
            }
            localStorage.setItem("cart", JSON.stringify(cart));
            $("#idMainRow").append(html);
        }

        </script>

    </body>
</html>
