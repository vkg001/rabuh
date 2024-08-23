<?php
require 'config/connection.php';
require 'config/google/configLogin.php' ;
if (isset($_SESSION['user_id'])) {
    header('location: home.php');
}

$showAlert = 0;
if (isset($_SESSION['log'])) {
    $showAlert = 1;
    unset($_SESSION['log']);
}

$showFirstLoginMsg = 0;
if (isset($_GET['social'])) {
    if ($_GET['social'] == 'already') {
        $showFirstLoginMsg = 1;
    } else if ($_GET['social'] == 'create') {
        $showFirstLoginMsg = 2;
    }
}

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
    <link rel="stylesheet" href="./assets/app_styles/icons.min.css">
    <link rel="stylesheet" href="./assets/plugins/sweet-alerts/sweetalert2.min.css">

</head>


<body id="top-header">
    <div id="page-loader">
        <div class="loader-icon fa fa-spin colored-border"></div>
    </div>
    <?php include 'header2.php' ?>


    <section class="page-banner-area page-contact">
        <div class="overlay"></div>
        <!-- Content -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-12 text-center">
                    <div class="page-banner-content">
                        <h1 class="display-4 font-weight-bold">Login</h1>
                        <p>Get connected with your classmates</p>
                    </div>
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </section>


    <section class="section" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-sm-3"></div>
                <div class="col-sm-6 mb-6">
                    <div class="form-group">
                        <label class="h6 small d-block text-uppercase">
                            Your email address
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group ">
                            <input class="form-control" name="email" id="email" required="" placeholder="vikas@gmail.com" type="email">
                        </div>
                    </div>
                </div>
                <div class="col-sm-3"></div>
                <div class="col-sm-3"></div>
                <div class="col-sm-6 mb-6">
                    <div class="form-group">
                        <label class="h6 small d-block text-uppercase">
                            Password
                            <span class="text-danger">*</span>
                        </label>

                        <div class="input-group">
                            <input class="form-control" name="" id="password" required="" placeholder="Password" type="password">
                        </div>
                    </div>
                </div>
                <div class="col-sm-1 my-auto" style="cursor: pointer;" id="showPass">
                    Show
                </div>
                <div class="col-sm-1 my-auto" style="cursor: pointer; display: none;" id="hidePass">
                    Hide
                </div>


            </div>
            <div class="row text-right">
                <div class="col-sm-9">
                    <a href="forgotPass">Forgot Password</a>
                </div>
                <div class="col-sm-3"></div>
            </div>


            <div class="row text-center mb-4">
                <div class="col-sm-12 text-danger" id="response" style="display: none;">

                </div>
            </div>


            <div class="row text-center">
                <div class="col-sm-12">
                    <div class="">
                        <input name="submit" id="submit" type="button" class="btn btn-primary btn-circled" value="Login">
                    </div>
                </div>
            </div>


            <div class="row text-center mt-3">
                <div class="col-sm-12">
                    <a class="btn btn-lg btn-google btn-block text-uppercase btn-outline" href="<?php echo $google_client->createAuthUrl(); ?>">
                        <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Login Using Google
                    </a>
                </div>
                <div class="col-sm-12">
                    <a href="comingsoon" class="btn btn-lg btn-google btn-block text-uppercase btn-outline">
                        <i class="fab fa-facebook-f text-primary"></i> Login using Facebook
                    </a>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-sm-12 text-center">
                    Don't have an account ? <a href="signup"> &nbsp; Create account</a>
                </div>
            </div>

        </div>
    </section>
    <?php include 'footer.php' ?>



    <!--  Page Scroll to Top  -->

    <a class="scroll-to-top js-scroll-trigger" href="#top-header">
        <i class="fa fa-angle-up"></i>
    </a>





    <!-- 
    Essential Scripts
    =====================================-->


    <!-- Main jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="plugins/bootstrap/js/popper.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <!-- Map Js -->
    <script src="plugins/google-map/gmap3.min.js"></script>

    <script src="js/form/contact.js"></script>
    <script src="js/theme.js"></script>
    <script src="./assets/plugins/sweet-alerts/sweetalert2.min.js"></script>
    <script>
        $(document).ready(function() {
            var showAlert = '<?php echo $showAlert; ?>';
            if (showAlert == 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'You have been logged out successfully.',
                    showConfirmButton: false,
                    timer: 3000
                })
            }


            var flag = '<?php echo $showFirstLoginMsg; ?>';
            if (flag == '1') {
                Swal.fire({
                    customClass: {
                        confirmButton: 'btn btn-white btn-circled',
                    },
                    buttonsStyling: false,
                    icon: 'info',
                    title: 'An existing account found.<br>Login here.',
                    showConfirmButton: true,
                    timer: 6000
                })
            }

        })

        $(document).on('click', '#submit', function() {
            // alert("call");
            $("#response").hide();

            var btn = $("#submit");
            var email = $("#email").val();
            var password = $("#password").val();
            $.ajax({
                url: "signup_helper.php",
                method: 'post',
                data: {
                    loginWith: email,
                    password: password
                },
                beforeSend: function() {
                    btn.val("Logging in");
                    btn.attr("readonly", true);
                },
                success: function(response) {
                    // alert(response);
                    switch (response) {
                        case '1':
                            window.location.replace('home.php');
                            break;
                        case '2':
                            $("#response").html("Invalid email");
                            break;
                        case '3':
                            $("#response").html("Account not found");
                            break;
                        default:
                            $("#response").html("Incorrect password");
                    }
                    $("#response").show();
                    btn.val("Login");
                    btn.attr("readonly", false);
                }
            })
        })


        $(document).on('click', '#showPass', function() {
            $("#password").attr('type', 'text');
            $(this).hide();
            $("#hidePass").show();
        })

        $(document).on('click', '#hidePass', function() {
            $("#password").attr('type', 'password');
            $(this).hide();
            $("#showPass").show();
        })
    </script>

</body>

</html>