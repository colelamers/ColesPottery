
/***************************************/
/********COLESPOTTERY JAVASCRIPT********/
/***************************************/
/*
* Code that starts with jq must be invoked with jQuery objects.
* Code that starts with js must be invoked with JavaScript objects
*
*/



// #REGION VARIABLES
loadEvents = [];

// #ENDREGION VARS
// #REGION GLOBAL

/**
* Runs all the functions pushed to the loadEvents
*/
function runLoadEvents()
{
    for (let func in loadEvents)
    {
        try{
            // Run function
            loadEvents[func]();
        }
        catch (e)
        {
            // Which function errored
            let errorLog = "--Exception: " + e + " \n-- Function That Errored:";
            errorLog += loadEvents[func];
            console.log(errorLog); // TODO: --3-- come back to this since we
                                   // may not want it logging in the console
        }
    }
} // function runLoadEvents

function addLoadEvent(func)
{
    var oldonload = window.onload;
    if (typeof window.onload != 'function')
    {
        window.onload = func;
    }
    else
    {
        window.onload = function ()
        {
            oldonload();
            func();
        }
    }
} // function addLoadEvent

/**
* This load event modifies the size of the cart bag depending on screen width
*/
addLoadEvent(function(){
    if (window.innerWidth < 768){
        $("#cartText").hide();
        $("#cartImages").css("width","2.5em");
    }
    else{
        $("#cartText").show();
        $("#cartImages").css("width","3em");
    }

    $(window).resize(function(){
        if ($(".navbar-toggler").is(":visible") === true){
             $("#cartText").hide();
             $("#cartImages").css("width","2.5em");
        }
        else{
            $("#cartText").show();
            $("#cartImages").css("width","3em");
        }
    })
})

/**
* A simple fast way to iterate through and check all the ways a param has not
* been initialized. True means it's null. False means it's not.
*
* @param | nullable | variable | "undefined", {null}, "myvariable", 39 |
* this just tests against the common nullable/undefined types that are often
* checked out
*/
function IsEmptyNullUndefined(variable){
    var nullChecks = ["", NaN, null, "null", undefined, "undefined", false];
    for (i in nullChecks){
        if (nullChecks[i] === variable){
            return true;
        }
        else{
            continue;
        }
    }
    return false;
}

/**
* Retrieves all inventory information and uses that for building in the cart
* @NOTE | must get every time because if someone makes a purchase, the info
* will be changed
*/
function StoreJsonInventory()
{
    $.ajax({
        "url": "/page/retriever",
        "method": "POST",
        //"dataType": "json",
        "data": {
            "GetAllInventoryItemsJson": "true"
        },
        "success": function(data)
        {
            localStorage.setItem("allInventory", data);
        },
        "error": function(data)
        {
            console.log(data.status);
        }
    });
}
    

/*

function loadScript(url)
{
    var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = url;
    head.appendChild(script);
}

*/


/**
* @param string email Email passed in as a string
* NOTE: the key to Passing in strings with a RegExp, is that you need to feed
* it double \\ because a single one eliminates the \ character as a string.
* This gets removed so when you pass in a \\, it retains one as the RegExp
* becomes initialized
* @return bool Results of passing regex pattern
*/
function validateEmail(email)
{
    // Not pattern
    /* let pattern  = "^([^;:\'\"!@#$%^&*_+\\-,/?`~=\\[\\]\\(\\){}<>\\s]*)@(([^;:\'";
    *pattern += "\"!@#$%^&*_+\\-,/?`~=\\[\\]\\(\\){}<>\\s]*)([.][A-z]{3}\\b))";
    */
    let pattern = "^([A-z0-9.]*@[A-z0-9.]*[.][A-z]{3}\\b)";
    let rgx = new RegExp(pattern);
    return rgx.test(email);
} // function validateEmail

/**
* @param object textBoxElement A jQuery object of a selected input element
* @param object enableDisableThisHtmlElement A jQuery object of a selected input
*               element
* @return bool Enables or disables the enableDisableThisHtmlElement element and
*              returns a boolean value true if it's enabled or false if it's
*              disabled
*/
function jqAllowSendingEmail(textBoxElement)
{
    if (!validateEmail(textBoxElement.val()) || textBoxElement.val() == "")
    {
        // Disable
        //enableDisableThisHtmlElement.prop("disabled", true);
        return false;
    } else{
        // Enable
        //enableDisableThisHtmlElement.prop("disabled", false);
        return true;
    }
} // function jqPerformRegexCheck

