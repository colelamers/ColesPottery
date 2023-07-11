<?php

namespace Website\admin;
use \DateTime;
/**
*@NOTE When using this file: copy the header and footer you'd like to retain
* and put that in the SetHeader and SetFooter functions. Ensure that the pathing
* to the pages starts with "../" so that it goes back to the home address from
* the current dir. Continue working on this.
*/

/**
* Prevents access to this file and redirects to the homepage
*/
if (($_SERVER['REQUEST_METHOD'] == 'GET'
    || $_SERVER['REQUEST_METHOD'] == 'POST')
&& realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME']))
{
    // TODO: --3-- think of adding this as a function in here so it can be called everywhere.
    // Sending 403 Forbidden
    header('HTTP/1.0 403 Forbidden', TRUE, 403);

    // Redirect to home
    die(header('location: /'));
}


class SetupPage
{
    public $hostname = '';
    public $pages_list = [];
    public $webpage_header = '';
    public $webpage_footer = '';
    public $webpage_scripts = '';
    public $webpage_css = '';
    public $webpage_meta = '';

    public function __construct(string $hostname)
    {
        $this->InitializeErrorLog();

        $this->hostname = $hostname;
        $this->SetHeader();
        $this->SetFooter();
        $this->SetScripts();
        $this->SetCss();
        $this->SetMeta();
        //$this->SetRequires();
        //$this->Redirect_HTTPS();
    } // constructor

    function InitializeErrorLog()
    {
        ini_set('display_errors', 0);// hides errors on screen so they don't show
        ini_set('log_errors', 1);
        $now = new DateTime;
        ini_set('error_log', dirname(__FILE__) . '/logs/' . $now->format("Ymd")
        . 'error.log');
        error_reporting(E_ALL);
    }
    /**
     * Takes the base hostname
     */
    function Redirect_HTTPS($uri = "")
    {
        try
        {
            if(!isset($_SERVER['HTTPS']))
            {
                /*
                * don't know why i had this code. it's been constantly
                * showing up in my error logs though it's annoying me now...
                if ($_SERVER['HTTPS'] != "on")
                {
                }
                */
                error_log('server not https');
                error_log($this->hostname . $uri);

                header('Location: https://' . $this->hostname . $uri);
                die();
            }

            if (substr($this->hostname, 0, 4) !== 'www.')
            {
                header('Location: https://www.' . $this->hostname . $uri);
                //die();
            } 
        }
        catch (\Throwable $e)
        {
            error_log('Error in setting up the page. sup.php, Exception: ' . $e);
        }
    } // function Redirect_HTTPS

    private function SetHeader()
    {
        $this->webpage_header = '
        <!--HEADER-->
          <header>
            <link rel="icon" type="image/x-icon" href="../design/images/favicon.ico">
            <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
              <div id="header_location" class="container-fluid">
                <a class="navbar-brand" href="../">
                  Coles Pottery
                </a>
                <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false"
                aria-label="Toggle navigation" style="margin-right: 20%;">
                <span class="navbar-toggler-icon">
                </span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                  <ul class="navbar-nav me-auto mb-2 mb-md-0">
                  <li class="nav-item">
                    <a class="nav-link" href="../">
                      Home
                    </a>
                  </li>
                    <li class="nav-item">
                      <a class="nav-link" href="../page/inventory.php">
                        Inventory
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="../page/about.php">
                        About
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </nav>
          </header>
        ';
        /* Contact page removed
          <li class="nav-item">
            <a class="nav-link" href="../page/contact">
              Contact
            </a>
          </li>
          
          Cart Removed
          <a id="cart" style="text-decoration: none;" href="/page/cart.php">
            <div id="cartText" style="text-align: center;">
                Cart
            </div>
            <div id="cartImages">
                <img id="cartImgEmpty" src="../design/images/bag.svg">
                <img id="cartImgFilled"
                src="../design/images/bag-heart.svg">
            </div>
          </a>
        */
    }

    private function SetFooter()
    {
        $this->webpage_footer = '
    <!-- colespotteryad -->
    <ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6167570810470313"
     data-ad-slot="7219181717"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
     
        <!-- FOOTER -->
        <footer class="container">
          <div name="footer-text">
            <a href="http://www.ColesPottery.com">
              ColesPottery.com
            </a>
          </div>
        </footer>
        ';
    } // SetFooter;

    private function SetScripts()
    {
        $this->webpage_scripts = '
        <!-- SCRIPTS -->
        <script src="../design/js/bootstrap.js">
        </script>

        <script src="../design/js/colespottery.js">
        </script>

        <script src="../design/js/jquery-3.6.0.js">
        </script>
        ';
        /*
        *
        google doesn't like how I get no traffic and my site isn't perfect so I guess no ads for me...
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6167570810470313" crossorigin="anonymous">
        </script>
        
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-6167570810470313"
             crossorigin="anonymous"></script>

        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
        */
    }

    private function SetCss()
    {
        $this->webpage_css = '
        <!-- CSS -->
        <link href="../design/css/bootstrap.css" rel="stylesheet">
        <link href="../design/css/colespottery.css" rel="stylesheet">
        <link href="../design/css/carousel.css" rel="stylesheet">
        ';
    }

    private function SetMeta()
    {
        $this->webpage_meta = '
        <title>Coles Pottery</title>
        <meta charset="utf-8">
        <meta property="og:site_name" content="Coles Pottery">
        <meta property="og:title" content="Coles Pottery">
        <meta property="og:url" content="http://www.colespottery.com">
        <meta property="og:type" content="website">
        <link rel="canonical" href="http://www.colespottery.com">
        <meta itemprop="name" content="Cole\s Pottery">
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Cole">
        <meta name="description" content="Coles Pottery is American made pottery and ceramicware made in Green Bay Wisconsin.">
        <meta name="keywords" content="Pottery, Ceramics, Ceramicware, Coffee Mug, Hand made, American Made, American, Custom, Mugs, Bowls, ColePottery, ColesPottery, Cole, Cole Pottery Wisconsin, Wisconsin, Wisconsin Pottery, Green Bay Pottery, Cole Green Bay Pottery, Coles Green Bay Pottery">
        <noscript>You need to enable JavaScript to run this app.</noscript>
        ';
        
        //<meta name="viewport" content="width=500px">

    }
} // class SetupPage
