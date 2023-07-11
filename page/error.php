<?php

require_once '../admin/setuppage.php';
$SetupPage = new Website\admin\SetupPage($_SERVER['HTTP_HOST']);


    $headers = array(
        'From' => 'ERRORHIT@colespottery.com',
        'Reply-To' => 'cole@colespottery.com',
        'X-Mailer' => 'PHP/' . phpversion()
    );

    $retval = mail(
        "cole@colespottery.com", // to
        "ERROR PAGE HIT", // subject
        "An error has occurred. Please check the log at this time.", // message
        $headers
    ); // metadata stuff
// TODO: --1-- send me the errorlog in my email at exactly this time.
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">

  <?php
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
  <div class="container marketing" style="text-align: center;">
    <hr class="featurette-divider">
    <span style="font-size: 60px;">(╯°□°)╯︵ ┻━┻ <br> Uh Oh! 500 Error.<br>
        Cole has been notified to fix it!</span>
    <hr class="featurette-divider">
  </div>

   <?php
   echo $SetupPage->webpage_scripts;

   echo $SetupPage->webpage_footer;
    ?>


</body>
</html>
