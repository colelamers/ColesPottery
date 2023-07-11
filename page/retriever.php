<?php

namespace Website\page;

require_once '../admin/setuppage.php';
require_once '../admin/inventoryitem.php';
use Website\admin;

$SetupPage = new \Website\admin\SetupPage($_SERVER['HTTP_HOST']);


// GetAllInventoryItemsJson is used in ColesPottery.js. Needed for localstorage
if (isset($_POST['GetAllInventoryItemsJson'])
&& $_POST['GetAllInventoryItemsJson'] === 'true')
{
    echo admin\InventoryItemFuncs::GetAllInventoryItemsJson();
}
else
{
    echo admin\InventoryItemFuncs::BuildHtml();
}

?>