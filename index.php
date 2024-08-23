<?php
include 'config/connection.php';
if (isset($_SESSION['user_id'])) {
    header('location: home');
}
$callHeader = 1;
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="social-media">
    <meta name="keywords" content="rabuh">
    <title><?php echo $site_name ?></title>
    <link rel="icon" href="<?php echo $icon ?>">

    <!-- bootstrap.min css -->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
    <!-- Animate Css -->
    <link rel="stylesheet" href="plugins/animate-css/animate.css">
    <!-- Icon Font css -->
    <link rel="stylesheet" href="plugins/fontawesome/css/all.css">
    <link rel="stylesheet" href="plugins/fonts/Pe-icon-7-stroke.css">
    <!-- Themify icon Css -->
    <link rel="stylesheet" href="plugins/themify/css/themify-icons.css">
    <!-- Slick Carousel CSS -->
    <link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
    <link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">

</head>


<body id="top-header">

    <?php
    include 'header.php'
    ?>



    <!-- HERO
    ================================================== -->
    <section class="banner-area py-7">
        <!-- Content -->
        <div class="container">
            <div class="row  align-items-center">
                <div class="col-md-12 col-lg-7 text-center text-lg-left">
                    <div class="main-banner">
                        <!-- Heading -->
                        <h1 class="display-4 mb-4 font-weight-normal">
                            Divided by Lockdown united by Internet
                        </h1>

                        <!-- Subheading -->
                        <p class="lead mb-4">
                            Lets have fun with friends via
                            <a href="./">
                                <?php echo $site_name ?>
                            </a>
                        </p>

                        <!-- Button -->
                        <p class="mb-0">
                            <a href="signup.php" target="_blank" class="btn btn-primary btn-circled">
                                Register Now
                            </a>
                        </p>
                    </div>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="banner-img-block">
                        <img src="images/banner-img-5.png" alt="banner-img" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="section bg-grey" id="feature">
        <div class="container">
            <div class="row justy-content-center">
                <div class="col-md-4">
                    <div class="text-center feature-block">
                        <div class="img-icon-block mb-4">
                            <i class="ti-comment-alt"></i>
                        </div>
                        <h4 class="mb-2">Chat</h4>
                        <p>Chat with your friends.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-center feature-block">
                        <div class="img-icon-block mb-4">
                            <i class="ti-share"></i>
                        </div>
                        <h4 class="mb-2">Ask Doubts</h4>
                        <p>Ask doubts to the community</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="text-center feature-block">
                        <div class="img-icon-block mb-4">
                            <i class="ti-gallery"></i>
                        </div>
                        <h4 class="mb-2">Post Content</h4>
                        <p>Share what's in your mind</p>
                    </div>
                </div>


            </div>
        </div>
    </section>



    <?php //include 'price.php' 
    ?>


    <?php include 'footer.php' ?>


    <!--  Page Scroll to Top  -->

    <a class="scroll-to-top js-scroll-trigger" href="#top-header">
        <i class="fa fa-angle-up"></i>
    </a>

    <!-- Main jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="plugins/bootstrap/js/popper.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/form/contact.js"></script>
    <script src="js/theme.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>