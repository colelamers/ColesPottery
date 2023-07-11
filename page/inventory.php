<?php

namespace Website\page;

require_once '../admin/setuppage.php';
$SetupPage = new \Website\admin\SetupPage($_SERVER['HTTP_HOST']);

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

    <?php
    // Add Header
    echo $SetupPage->webpage_header;
    ?>

    <!-- FEATURETTES -->
    <div class="container marketing">
        <hr class="featurette-divider">
        <h1> For Sale </h1>
        <div style="margin-bottom: 10px;">
            <span>
                Email me for inquiries on purchasing:
            </span>
            <a href="mailto:colelamers@gmail.com">colelamers@gmail.com</a>
        </div>
        <div style="padding: 5%;">
            <div>
                <span style="color:darkgreen;font-weight: bold;">
                    $8
                </span>
                <span>
                    for shot glasses
                </span>
            </div>  
            <div>
                <span style="color:darkgreen;font-weight: bold;">
                    $10
                </span>
                <span>
                    for small bowls
                </span>
            </div>    
            <div>
                <span style="color:darkgreen;font-weight: bold;">
                    $40 - $50
                </span>
                <span>
                    for standard mugs depending on size/in demand
                </span>
            </div>
            <div>
                <span style="color:darkgreen;font-weight: bold;">
                    $17/lb
                </span>
                <span>
                    &nbsp;for large and decorative pieces
                </span>
            </div>
        </div>

        <!--<div id="filters" class="row-2">
            <div id="filterSorting" class="row-2">
                
<span>

Sort By: 
</span>

                <select id="idSortBy">
                  <option value="Price asc">
                      Price Low-High
                  </option>
                  <option value="Price desc">
                      Price High-Low
                  </option>
                  <option value="Type">
                      Type
                  </option>
                  <option value="Colors">
                      Color
                  </option>
                  <option value="Volume">
                      Size
                  </option>
                </select>
            </div>
            <div id="filterType" class="row-2">
                
<span>
Filter Pottery By: 
</span>

                <select id="idFilterBy">
                    <option value="">

                    </option>
                    <option value="Large Bowl">
                      Large
                    </option>
                    <option value="Medium Bowl">
                      Medium
                    </option>
                    <option value="Small Bowl">
                      Small
                    </option>
                    <option value="Decorative Bowl">
                      Decorative
                    </option>
                    <option value="Mug">
                      Mugs
                    </option>
                </select>
            </div>
            -->
        <div id="idMainRow" class="row">
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

        addLoadEvent(GetInventoryItemsToBuildHtml);
        addLoadEvent(StoreJsonInventory);

        $("#idSortBy").change(function () {
            $("#idMainRow").children().remove();
            GetInventoryItemsToBuildHtml();
        })

        $("#idFilterBy").change(function () {
            $("#idMainRow").children().remove();
            GetInventoryItemsToBuildHtml();
        });

        // This function retrieves the proper JSON encoded data to build the html
        function GetInventoryItemsToBuildHtml() {
            $.ajax({
                "url": "/page/jsondatabase.js",
                "method": "POST",
                //"dataType": "json",
                "data": {
                    "sortBy": $("#idSortBy").val(),
                    "filterBy": $("#idFilterBy").val()
                },
                "success": function (data) {
                    BuildInventoryHtml(JSON.parse(data));
                },
                "error": function (data) {
                    console.log(data.status);
                }
            });
        }

        function ConstructCart(key) {
            let cart = {};
            let invItems = JSON.parse(localStorage.getItem("allInventory"));
            let desiredQty = parseInt($("#qty" + key).val());

            // A way to catch for not allowing more than the max item.
            if (desiredQty > invItems[key]["Qty"]) {
                desiredQty = invItems[key]["Qty"];
            }

            if (!IsEmptyNullUndefined(desiredQty) && desiredQty > 0) {
                if (!IsEmptyNullUndefined(localStorage.getItem("cart"))) {
                    cart = JSON.parse(localStorage.getItem("cart"));
                    // If cart does not have item
                    if (!cart.hasOwnProperty(key)) {
                        // Add the inventory item and add quantity wanted to object
                        //cart[key] = invItems[key];
                        cart[key] = {};
                        cart[key]["DesiredQty"] = desiredQty;
                        cart[key]["Id"] = key;
                    }
                    else {
                        // If cart has item and quantity has changed
                        if (cart[key]["DesiredQty"] !== desiredQty) {
                            cart[key]["DesiredQty"] = desiredQty;
                            cart[key]["Id"] = key;
                        }
                    }
                } // if local storage not null
                else {
                    //cart[key] = invItems[key];
                    cart[key] = {};
                    cart[key]["DesiredQty"] = desiredQty;
                    cart[key]["Id"] = key;
                }

                let builtCart = JSON.stringify(cart);
                localStorage.setItem("cart", null);
                localStorage.setItem("cart", builtCart);

                window.location = "../page/cart";
            }
        }

        /**
        * Build HTLM data from json info retrieved form the server
        */
        function BuildInventoryHtml(data) {
            let html = '<div class="row product-listing">';

            // Each Object
            for (var item of data) {
                //if (parseInt(item["Qty"]) > 0){
                html += '<div class="col-lg-3 prod-list"><div class="invs">';

                // Image 1
                html += '<img class="invItems firImg" src="../design/images/skus/';
                html += item["Type"] + '_' + item["Sku"] + '1.jpg"';
                html += ' description="' + item["Colors"] + ' ' + item["DisplayName"] + '">';

                /*
                //Image 2
                html += '<img class="invItems secImg"';
                html += 'src="../design/images/skus/';
                html += item["Type"] + '_' + item["Sku"] + '2.jpg"';
                html += ' description="' + item["Colors"] + ' ' + item["DisplayName"] + '">';
                */
                //html += '</div>';
                html += '<div style="background-color: rgb(100, 255, 255);">';

                //Type
                html += '<h2 class="invText">';
                html += item["DisplayName"] + ' - #' + item["Id"] + '</h2>';

                // Qty
                // html += '<h2 class="invText">Qty in Stock: ';
                // html += item["Qty"] + '</h2>';
                //Color
                /* html += '<h2 class="invText"> Color: ' + item["Colors"];
                html += '</h2>';
                */

                // Price
                html += '<div style="display: block ruby;">';
                // html += '<h2 class="invText">Price: </h2>';
                // html += '<span style="color:#00b91d; font-size: 30px;">$';
                // html += item["Price"]; + '</span >';
                html += '</div>';

                if (item["Volume"] != "") {
                    html += '<h2 class="invText">Sizing: ' + item["Volume"];
                    html += '</h2>';
                }

                //Description
                //html += '<h2 class="invText">Description:';
                html += '<h2 class="invText">' + item["Description"] + '</h2>';
                html += '</div>';

                // Add To Cart
                /*
                html += '<div><input class="btn btn-warning" type="button"';
                html += 'value="Add to Cart" name="addtocart"';
                html += 'onclick="ConstructCart(' + item['id'] + ')">';
    
                // Quantity
                if (item["Qty"] != 0){
                    html += '<input type="number" id="qty' + item['id'];
                    html += '" name="' + item["Sku"]  + item["Type"] + 'quantity"';
                    html += ' min="1" max="' + item["Qty"] + '"';
                    html += ' style="width: 30%;" value="1">';
                }
                */
                html += '</div></div>';//</div>';
                // }
            }
            html += "</div>";
            $("#idMainRow").append(html);
        }
    </script>
</body>

</html>