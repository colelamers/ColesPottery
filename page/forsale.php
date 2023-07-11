<?php

require_once '../admin/setuppage.php';
$SetupPage = new Website\admin\SetupPage($_SERVER['HTTP_HOST']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">

  <!-- CSS IMPORTS -->
  <!-- TODO: need a php script to just loop through all CSS and a function to
  decide what ones not to-->
  <link href="../design/css/bootstrap.css" rel="stylesheet">
  <link href="../design/css/colespottery.css" rel="stylesheet">
  <link href="../design/css/carousel.css" rel="stylesheet">

</head>

<body>

<?php
echo $SetupPage->webpage_header;
?>

  <!-- FEATURETTES -->
  <div class="container marketing">
    <hr class="featurette-divider">
    <div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">
          Artisinal 1-of-a-kind pieces.
          <span class="text-muted">
            Show off your high-brow taste.
          </span>
        </h2>
        <p class="lead">
          Current invetories are fired at Cone 6 white or magnesium infused
          clay to create attractive effects that will sure to be a talking
          piece for a long time.
        </p>
      </div>
      <div class="col-md-5">
        <img class="img_featurette" src="../design/images/rotenauge.jpg">
      </div>
    </div>
    <hr class="featurette-divider">

    <div class="row featurette">
      <div class="col-md-7 order-md-2">
        <h2 class="featurette-heading">
          Food, microwave, and dishwasher safe.
          <span class="text-muted">
            Help take starving out of the artist!
          </span>
        </h2>
        <p class="lead">
          All glazes are made with food safe glazes that are safe to
          eat and drink from.
        </p>
      </div>
      <div class="col-md-5 order-md-1">
        <img class="img_featurette" src="../design/images/hnhset.jpg">
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
          See
          <a style="color: blue;" href="./contact.php">
              Contact Page
          </a>
          for info related to orders, shipping, and commissions.
        </p>
      </div>
      <div class="col-md-5">
        <img class="img_featurette" src="../design/images/blaueschale.jpg">
      </div>
    </div>
    <hr class="featurette-divider">
  </div>

  <?php
  echo $SetupPage->webpage_footer;
  ?>

<!-- SCRIPT LINKING -->
<script src="../design/js/bootstrap.js">
</script>


</body>
</html>
