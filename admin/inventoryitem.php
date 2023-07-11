<?php

namespace Website\admin;

require_once 'commonfunctions.php';
use \Website\admin\CommonFunctions;

// Startup
CommonFunctions::InitializeErrorLog();

/**
* I don't know that i'm using this anymore
*/
class InventoryItem
{
    public $Id = '';
    public $DisplayName = '';
    public $Colors = '';
    public $Sku = '';
    public $Type = '';
    public $Qty = 0;
    public $Price = 100000.00;
    public $Volume = '';
    public $Description = '';
}

class InventoryItemFuncs
{
    public static function GetAllInventoryItemsJson()
    {
        // Prevents code from displaying on page load and only retrieved on POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $sqlParams = array(); // empty
            // Build Query
            $sqlQuery = 'SELECT * from inventories';
            $results = CommonFunctions::GetSqlResults($sqlQuery, $sqlParams);

            $inventoryItems = array();
            foreach ($results as $row)
            {
                // Create Object List from SQL
                $dbItem = new InventoryItem;
                $dbItem->Id = $row['id'];
                $dbItem->DisplayName = $row['DisplayName'];
                $dbItem->Colors = $row['Colors'];
                $dbItem->Sku = $row['Sku'];
                $dbItem->Type = $row['Type'];
                $dbItem->Qty = $row['Qty'];
                $dbItem->Price = floatval($row['Price']);
                $dbItem->Volume = $row['Volume'];
                $dbItem->Description = $row['Description'];

                $inventoryItems[$dbItem->Id] = $dbItem;
            }

            return json_encode( $inventoryItems );
        }
    } // function BuildHTMLAjax

    //SortItemsAjaxQuery();

    // Builds html data server side instead
    public static function BuildHtml()
    {
        // TODO: --1-- eventually fix these sql queries to contain "?"
        // Prevents code from displaying on page load and only retrieved on POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $sqlQuery = 'SELECT * from inventories';
            
            // Retrieve Params
            $sqlParams = array();
            
            if ($_POST['filterBy'] != '')
            {
                //$filterArray = array('@filterBy' => $_POST['filterBy']);
                //$sqlParams = array_merge($sqlParams, $filterArray);
                $sqlQuery .= ' where DisplayName = ?';
                $sqlParams[] = $_POST['filterBy'];
            }
            
            $sqlQuery .= ' order by';
            
            /* 
             * The reason for the switch case is because you can't dynamically 
             * add in Params. They always have quotes.
             */ 
            switch ($_POST['sortBy'])
            {
                case "Type":
                    $sqlQuery .= ' Type';
                    break;
                case "Colors":
                    $sqlQuery .= ' Colors';
                    break;
                case "Volume":
                    $sqlQuery .= ' Volume';
                    break;
                default:
                    $sqlQuery .= ' Price';
                    
                    // If sortBy val contains 'desc', then order by desc, else asc.
                    if (strpos($_POST['sortBy'], 'desc') > 0)
                    {
                        $sqlQuery .= ' desc';
                    }
                    else
                    {
                        $sqlQuery .= ' asc';
                    }
                    break;
            }
            
            $results = CommonFunctions::GetSqlBoundParams($sqlQuery, $sqlParams);
            //$results = CommonFunctions::GetSqlResults($sqlQuery, $sqlParams);
            return json_encode($results);
        }
    } // function BuildHtml
} // class 

/*
// GetAllInventoryItemsJson is used in ColesPottery.js. Needed for localstorage
if (isset($_POST['GetAllInventoryItemsJson'])
&& $_POST['GetAllInventoryItemsJson'] === 'true')
{
    GetAllInventoryItemsJson();
}
else
{
    BuildHtml();
}
*/

/*
Old code for building the html server side.
        $html = '<div class="row product-listing">';

        // Each Object
        foreach ($results as $item){
            $html .= '<div class="col-4 prod-list"><div class="invs">';
            // Image 1
            $html .= '<img class="invItems firImg" src="../design/images/skus/';
            $html .= $item["Sku"] . '_' . $item["Type"] . '_001.jpg">';

            //Image 2
            $html .= '<img class="invItems secImg"';
            $html .= 'src="../design/images/skus/';
            $html .= $item["Sku"] . '_' . $item["Type"] . '_002.jpg">';
            $html .= '</div>';

            //Type
            $html .= '<div class="descriptions"><h2 class="invText">';
            $html .= 'Type: ' . $item["DisplayName"] . '</h2>';
            // Qty
            $html .= '<h2 class="invText">Quantity: ';
            $html .= $item["Qty"] . '</h2>';
            //Color
            $html .= '<h2 class="invText"> Color: ' . $item["Colors"];
            $html .= '</h2>';
            // Price
            $html .= '<div style="display: block ruby;">';
            $html .= '<h2 class="invText">Price: </h2><span style="color:';
            $html .= '#00b91d;"> $' . $item["Price"] . '</span></div>';

            if ($item["Volume"] != ""){
                $html .= '<h2 class="invText">Sizing: ' . $item["Volume"];
                $html .= '</h2>';
            }

            //Description
            $html .= '<h2 class="invText">Description:';
            $html .= $item["Description"] . '</h2>';

            // Add To Cart
            $html .= '<div><input class="btn btn-warning" type="button"';
            $html .= 'value="add to cart" name="addtocart"';
            $html .= 'onclick="ConstructCart(' . $item['id'] . ')">';

            // Quantity
            if ($item["Qty"] != 0){
                $html .= '<input type="number" id="qty' . $item['id'];
                $html .= '" name="' . $item["Sku"] . $item["Type"];
                $html .= 'quantity" min="1" max="' . $item["Qty"];
                $html .= '" style="width: 30%;">';
            }

            $html .=  '</div></div></div>';
        }
        $html .= "</div>";
        echo $html;
        */

    /*
    // For Ajax Retrieval from csv file
    $inventoryItems = file('./admin/inventories/inventory.csv',
    FILE_IGNORE_NEW_LINES);

    $listOfInventoryObjects = array();
    // Iterate through items; build object list
    for ($i = 0; $i < sizeof($inventoryItems); ++$i)
    {
        $item = preg_split('/[,]/', $inventoryItems[$i]);
        $csvObject = new InventoryItem;

        $csvObject->Sku = $$item[0];
        $csvObject->Type = $$item[1];
        $csvObject->Qty = $$item[2];
        $csvObject->Price = floatval($$item[3]);
        $csvObject->Volume = $$item[4];
        $csvObject->Description = $$item[5];

        $listOfInventoryObjects[] = $csvObject;
    }
    */
