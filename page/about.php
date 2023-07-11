<?php

namespace Website\page;


require_once '../admin/setuppage.php';
use Website\admin; // one way to do it
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

    <!--HEADER-->
    <?php
    echo $SetupPage->webpage_header;
     ?>

      <!-- FEATURETTES -->
      <div class="container marketing">
        <hr class="featurette-divider">
        <div class="row featurette">
          <div class="col-md-6">
            <h2 class="featurette-heading">
              About
              <span class="text-muted">
                ColesPottery
              </span>
            </h2>
            <p class="lead">
              I've been inspired to pursue pottery making since taking a few
              classes in high school. I've been throwing for a total of five
              years since then. It's only recently that I decided to build a
              simple website to put them up for sale to be sold online.
            </p>
            <p class="lead">
                All pieces are thrown and fired in Green Bay, WI. They're made
                with food, microwave, and dishwasher safe glazes. I do not make
                the glazes though but just use what is available at the studio.
            </p>
            <p class="lead">
                Please inquire about commissions if interested through the
                contact page. All shipments are done with UPS or USPS with
                standard ground shipping. Items do not including shipping in the
                price.
            </p>
          </div>
          <div class="col-md-4" style="padding: 5%;">
              <div style="background-color: #333333; width: 400px; height:
              400px; border-radius:30%;">
                  <img style="width: 350px; margin: 25px; border-radius: 30%;"
                  src="../design/images/ubermich.jpg">
              </div>
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

    </body>
</html>
