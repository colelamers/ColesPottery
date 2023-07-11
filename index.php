<?php

namespace Website;
require_once 'admin/setuppage.php';
$SetupPage = new admin\SetupPage($_SERVER['HTTP_HOST']);

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

    <!-- HOME_WELCOME BUBBLE -->
    <div class="back_img_padding">
      <div class="back_img fadeIn">
        <div class="container-fluid">
          <div class="row">
            <div class="row-lg home_welcome" style="font-size: 16px;">
              <div id="home_welcome_text" class="col md-4">
                Handcrafted pottery and ceramicware made in
              </div>
              <div id="home_welcome_text">
                <span style="color:green">
                  Green
                </span>
                <span style="color:gold">
                  Bay,
                </span>
                Wisconsin!
              </div>
              <div>
               <a href="mailto:colelamers@gmail.com" style="color:lightblue;">
                 Feel free to contact me at colelamers@gmail.com
               </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- SHOWCASE -->
    <div class="background_for_container fadeIn">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-3">
            <img src="./design/images/schwarz.jpg">
          </div>
          <div class="col-lg-3">
            <img src="./design/images/celstialtea.jpg">
          </div>
          <div class="col-lg-3">
            <img src="./design/images/diemeer.jpg">
          </div>
          <div class="col-lg-3">
            <img src="./design/images/hnhfolded.jpg">
          </div>
        </div>
      </div>
    </div>

    <!-- FEATURETTES -->
    <div class="container marketing">
      <hr class="featurette-divider">
      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">
            Artisinal 1-of-a-kind pieces.
            <span class="text-muted">
              Patronize like an Italian renaissance Aristocrat!
            </span>
          </h2>
        </div>
        <div class="col-md-5">
          <img class="img_featurette" src="./design/images/rotenauge.jpg">
        </div>
      </div>
      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7 order-md-2">
          <h2 class="featurette-heading">
            Food, microwave, and dishwasher safe.
            <span class="text-muted">
              Help take starving out of the artist.
            </span>
          </h2>
        </div>
        <div class="col-md-5 order-md-1">
          <img class="img_featurette" src="./design/images/hnhset.jpg">
        </div>
      </div>
      <hr class="featurette-divider">

      <div class="row featurette">
        <div class="col-md-7">
          <h2 class="featurette-heading">
            Made with Care, Creativity...
            <span class="text-muted">
              and Cole!
            </span>
          </h2>
          <p class="lead">
            Feel free to ask questions related to orders, shipping, 
            or possible commissions.
          </p>
        </div>
        <div class="col-md-5">
          <img class="img_featurette" src="./design/images/blaueschale.jpg">
        </div>
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
    // Specifically for domain email list. Needs 2 form vars for some reason.
    // So i just kept it but pass the same value in the first one to the seoncd.
     ?>

    addLoadEvent(CheckEmail)
    $("#idEmail1").on("keydown", CheckEmail);

    function CheckEmail()
    {
        if ($("#idEmail1").val().length < 6)
        {
            $("#idEmailSubmit").prop("disabled", true);
        }
        else
        {
            $("#idEmailSubmit").prop("disabled", false);
        }
    }// function checkemail

    $('#idEmailSubmit').on("click", function()
    {
        $('#idEmail2').val($('#idEmail1').val());
    });

    </script>
  </body>

</html>
