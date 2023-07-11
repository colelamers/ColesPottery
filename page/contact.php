<?php

namespace Website\page;
// REQUIRES
require_once '../admin/setuppage.php';
use \Website\admin\SetupPage;

// VARIABLES
$SetupPage = new SetupPage($_SERVER['HTTP_HOST']);

$regex = "/^([A-z0-9.]*@[A-z0-9.]*[.][A-z]{3}\b)/";
$legitimateEmail = '';
/*
// PAGE ACTIONS
try
{
    if (isset($_POST['message']) && isset($_POST['email']))
    {
        $legitimateEmail = preg_match($regex, $_POST['email']);

        // Message contains text
        if ($legitimateEmail == 1)
        {
            error_log('EMAIL: ' . $_POST['email']);
            error_log('MESSAGE: ' . $_POST['message']);

            // Set email send from and send email
            $headers = array(
                'From' => 'contactform@colespottery.com',
                'Reply-To' => $_POST['email'],
                'X-Mailer' => 'PHP/' . phpversion()
            );

            $retval = mail(
                "cole@colespottery.com", // to
                "CONTACTFORM INFO FROM " . $_POST['email'], // subject
                 $_POST['message'], // message
                 $headers
             ); // metadata stuff

            // resets _POST values
            $_POST = array();
            // Sets display message
            if($retval)
            {
               error_log('Message sent successfully...');
            }
            else
            {
                // Set email send from and send email
                $headers = array(
                    'From' => 'error@colespottery.com',
                    'Reply-To' => 'cole@colespottery.com',
                    'X-Mailer' => 'PHP/' . phpversion()
                );

                $retval = mail(
                    "cole@colespottery.com", // to
                    "Error Sending Email in Contact Form", // subject
                    "Debug the error in contact page", // message
                    $headers
                 ); // metadata stuff
               error_log('Message could not be sent...');
            }
        } // if
    } // if
} // try
catch (Exception $ex)
{
    error_log('ERROR: ' . $ex);
}
*/
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
        echo $SetupPage->webpage_header;
         ?>

      <!-- FEATURETTES -->
      
      <div class="container marketing">

        <hr id="idTopDivider" class="featurette-divider">
        <form method="POST">
            <div class="row featurette">
              <div class="row contactRow">
             <span>
                Feel free to contact me at 
               <a href="mailto:cole@colespottery.com">cole@colespottery.com</a>
             </span>
         <!--
                <h1 class="featurette-heading" style="text-decoration: underline;">
                  Contact
                </h1>
                <div id="txInvalidEmail" class="col-3 errorText">
                    Invalid Email
                </div>
                <div id="txInvalidMessage" class="col-3 errorText">
                    Blank Message
                </div>
              </div>

              <div class="row contactRow">
                <div class="col-2">
                  <label for="tbEmail">
                    Email:
                  </label>
                </div>
                <div class="col-7">
                  <input type="text" id="tbEmail" name="email">
                </div>
              </div>
              
              <div class="row contactRow">
                <div class="col-2">
                  <label for="taMessageBox">
                    Message:
                  </label>
                </div>
                <div class="col-7">
                  <textarea value="" id="taMessageBox" name="message"
                  rows="6" cols="40"></textarea>
                </div>
              </div>

              <div id="btnSendParentRow" class="row contactRow">
                <div class="col-2">
                </div>
                <div id="hiddenText" style="display: none;" class="col-4">
                    Message has been sent!
                </div>
                <div class="col-2 align-self-center">
                  <button type="button" onclick="emailAjax()" class="btn btn-warning" id="btnSend"
                  value="Send">
                      Send
                  </button>
                </div>
              </div>
-->
            </div>
        </form>
        <hr class="featurette-divider">
      </div>

      <?php

      // Sets the Footer
      echo $SetupPage->webpage_footer;

      // Sets Scripts
      echo $SetupPage->webpage_scripts;

      ?>

    <script>

    function containsSpam(){
        var spamFilter = [
        '.com', 'http', '://', 'https', 'www', '.net', 
        '.org', '.online', '.biz', '.io', '.shop', '.co.uk',
        '.ru', '.co', '.me', '.info', '.inc', '.xyz',
        '.store', '.cards', '.au', '.co', '.tv'
        ];
        var stopSpam = false;
        
        for (var i = 0; i < spamFilter.length; ++i)
        {
            // Detect Link Spam
            if (!($("#taMessageBox").val().indexOf(spamFilter[i]) >= 0))
            {
                // No Links in description
                $("#btnSend").show();
                $("#hiddenText").hide();
            }
            else
            {
                // Links in description
                $("#hiddenText").show();
                $("#hiddenText").css("color", "red");

                $("#hiddenText").text("Please remove the link/website from your message.");
                $("#btnSend").hide();
                return true;
            }
        }
        
        return stopSpam;
    } // function 

    function emailAjax()
    {
        if (!(containsSpam()))
        {
            $.ajax({
                "url": "../page/contact.php",
                "method": "POST",
                "data": {
                    "email": $("#tbEmail").val(),
                    "message": $("#taMessageBox").val()
                },
                "header":{
                    //Access-Control-Allow-Origin
                },
                "success": function(){
                    $("#btnSend").remove();
                    $("#hiddenText").text("Message has been sent! Thank you for your interest!");
                    $("#hiddenText").show();
                    $("#tbEmail").val("");
                    $("#taMessageBox").val("");
                },
                "error": function(data){
                    console.log(JSON.stringify(data));
                }
            });
        }
    }

    addLoadEvent(Contact);
    $("#tbEmail").on("keyup", Contact);
    $("#tbEmail").on("blur", Contact);
    $("#taMessageBox").on("keyup", Contact);
    $("#taMessageBox").on("blur", Contact);
    $("#btnSend").on("keyup", Contact);


    function Contact()
    {
        $("#txInvalidEmail").hide();
        $("#txInvalidMessage").hide();
        
        // First look for spam in messagebox
        if (!containsSpam())
        {
            // Is email valid
            if (jqAllowSendingEmail($("#tbEmail")))
            {
                // Email is valid
                $("#txInvalidEmail").hide();

                // Does messagebox have text
                if ($("#taMessageBox").val() != "")
                {
                    // Messagebox has text
                    $("#txInvalidMessage").hide();
                    $("#btnSend").prop("disabled", false);
                }
                else
                {
                    // Messagebox does not have text
                    $("#txInvalidMessage").show();
                    $("#btnSend").prop("disabled", true);
                }
            } // if
            else
            {
                // Email is not valid
                $("#txInvalidEmail").show();
                $("#btnSend").prop("disabled", true);

                // Does messagebox have text
                if ($("#taMessageBox").val() != "")
                {
                    // Messagebox has text
                    $("#txInvalidMessage").hide();
                }
                else
                {
                    // Messagebox does not have text
                    $("#txInvalidMessage").show();
                }
            } // else
        } // if stop spam check passes
    } // function contact

    </script>


    </body>
</html>
