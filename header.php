<?php
$crlObj = curl_init();
curl_setopt($crlObj, CURLOPT_URL, "http://ip-api.com/json");
curl_setopt($crlObj, CURLOPT_RETURNTRANSFER, 1);

$resJson = curl_exec($crlObj);

$infoObj = json_decode($resJson);


$location = "";
if ($infoObj  &&  isset($infoObj->regionName)) {
    $region = $infoObj->regionName;
    $isp = $infoObj->isp;
    $ip = $infoObj->query;

    $location = $region . " | " . $isp . " | " . $ip;
}

// print_r($infoObj);

?>
<!-- LOADER TEMPLATE -->
<div id="page-loader">
    <div class="loader-icon fa fa-spin colored-border"></div>
</div>
<!-- /LOADER TEMPLATE -->


<div class="top-bar bg-dark " id="top-bar">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="top-bar-left text-white">
                    <?php
                    if ($location != "") {
                    ?>
                        <i class="fa fa-map-marker"></i>
                    <?php
                    }
                    ?>
                    <span class="ml-2"><?php echo $location ?></span>
                </div>
            </div>

            <div class="col-lg-4 ml-lg-auto col-md-6">
                <ul class="d-flex list-unstyled header-socials float-lg-right">
                    <li><a href="https://www.facebook.com/profile.php?id=100021800074053" target="_blank"> <i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="https://www.instagram.com/vkg_001/" target="_blank"> <i class="fab fa-instagram"></i></a></li>
                    <li><a href="https://www.linkedin.com/in/vikas-kumar-3a547a218/" target="_blank"> <i class="fab fa-linkedin"></i></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="logo-bar d-none d-md-block d-lg-block bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-2">
                <div class="logo d-none d-lg-block">
                    <!-- Brand -->
                    <a class="navbar-brand js-scroll-trigger" href="./">
                        <h2><?php echo $site_name ?></h2>
                    </a>
                </div>
            </div>

            <div class="col-lg-8 justify-content-end ml-lg-auto d-flex col-12 col-md-12 justify-content-md-center">
                <a href="tel:+919045097609" class="mr-2">
                    <div class="top-info-block d-inline-flex">
                        <div class="icon-block">
                            <i class="ti-mobile"></i>
                        </div>
                        <div class="info-block">
                            <h5 class="font-weight-500">+91-9045097609</h5>
                            <p>Call</p>
                        </div>
                    </div>
                </a>

                <a href="mailto:vkg360.vikas@gmail.com" target="_blank">
                    <div class="top-info-block d-inline-flex">
                        <div class="icon-block">
                            <i class="ti-email"></i>
                        </div>
                        <div class="info-block">
                            <h5 class="font-weight-500">vkg360.vikas@gmail.com</h5>
                            <p>Email Us</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- NAVBAR
    ================================================= -->
<div class="main-navigation" id="mainmenu-area">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary main-nav navbar-togglable rounded-radius">

            <a class="navbar-brand d-lg-none d-block" href="index.php">
                <h4><?php echo $site_name ?></h4>
            </a>
            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-bars"></span>
            </button>

            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <!-- Links -->
                <ul class="navbar-nav ">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php if (isset($_SESSION['user_id'])) echo 'home';
                                                    else echo './'; ?>" id="navbarWelcome" role="button" aria-haspopup="true" aria-expanded="false">
                            Home
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a href="about" class="nav-link js-scroll-trigger">
                            About
                        </a>
                    </li>

                    <li class="nav-item ">
                        <a href="contact" class="nav-link">
                            Contact
                        </a>
                    </li>
                </ul>

                <ul class="ml-lg-auto list-unstyled m-0">
                    <li>
                        <?php if (isset($_SESSION['user_id'])) { ?>
                            <a href='#' id="logout" class="btn btn-white btn-circled">Logout</a>
                        <?php } else { ?>
                            <a href="login.php" class="btn btn-white btn-circled">Login</a>
                        <?php } ?>
                    </li>
                </ul>
            </div> <!-- / .navbar-collapse -->
        </nav>
    </div> <!-- / .container -->
</div>