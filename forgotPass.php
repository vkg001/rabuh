<?php
require 'config/connection.php';
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="it">
    <meta name="keywords" content="">

    <meta name="author" content="themefisher.com">

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
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
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
                        <h1 class="display-4 font-weight-bold">Reset Password</h1>
                        <p>Reset your password or login via OTP</p>
                    </div>
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </section>


    <section class="section" id="contact">
        <div class="container">
            <div id="emailBlock">
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 mb-6">
                        <div class="form-group">
                            <label class="h6 small d-block text-uppercase">
                                Your email address
                            </label>

                            <div class="input-group ">
                                <input class="form-control" name="email" id="email" required="" placeholder="vikas@gmail.com" type="email">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-sm-12">
                        <div class="">
                            <button type="button" class="btn send-otp-btn btn-primary btn-circled">Send Otp</button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="otpBlock" style="display: none;">
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 mb-6">
                        <div class="form-group">
                            <label class="h6 small d-block text-uppercase">
                                OTP
                            </label>

                            <div class="input-group ">
                                <input class="form-control" name="otp" id="otp" required="" placeholder="Enter OTP" type="number">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-sm-12">
                        <div class="">
                            <button type="button" class="btn btn-primary btn-circled verify-otp-btn">Verify OTP</button>
                        </div>
                    </div>
                </div>
            </div>


            <div id="resetPassBlock" style="display: none;">
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 mb-6">
                        <div class="form-group">
                            <label class="h6 small d-block text-uppercase">
                                New Password
                            </label>

                            <div class="input-group ">
                                <input class="form-control" name="new_password" id="new_password" required="" placeholder="New Password" type="password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6 mb-6">
                        <div class="form-group">
                            <label class="h6 small d-block text-uppercase">
                                Confirm Password
                            </label>

                            <div class="input-group ">
                                <input class="form-control" name="confirm_password" id="confirm_password" required="" placeholder="Confirm Password" type="password">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-sm-12">
                        <div class="">
                            <button type="button" class="reset-password btn btn-primary btn-circled">Set Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php require 'footer.php' ?>



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
    <script src="./assets/plugins/sweet-alerts/sweetalert2.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwIQh7LGryQdDDi-A603lR8NqiF3R_ycA"></script>

    <script src="js/form/contact.js"></script>
    <script src="js/theme.js"></script>
    <script>
        $(document).ready(function() {

            $(".reset-password").click(function() {
                let new_password = $("#new_password").val().trim();
                let confirm_password = $("#confirm_password").val().trim();

                let th = $(this);
                if (new_password.length < 6) {
                    Swal.fire({
                        confirmButtonClass: "btn btn-primary btn-circled",
                        title: "Password is too short",
                        text: "",
                        icon: "error",
                    });
                    return;
                }


                if (new_password != confirm_password) {
                    Swal.fire({
                        confirmButtonClass: "btn btn-primary btn-circled",
                        title: "New Password and Confirm Password didn't match",
                        text: "",
                        icon: "error",
                    });
                    return;
                }

                $.ajax({
                    url: "signup_helper",
                    method: "POST",
                    data: {
                        new_password,
                    },
                    beforeSend: function() {
                        th.html("Updating password...").attr("disabled", true);
                    },
                    success: function(data) {
                        let res;
                        th.html("Set Password").attr("disabled", false);
                        try {
                            res = JSON.parse(data);
                        } catch (error) {
                            console.log(error);
                            console.log(data);
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Something went wrong",
                                text: "",
                                icon: "error",
                            });
                            return;
                        }

                        if (res.status == 200) {
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Password Set",
                                text: "",
                                icon: "success",
                            });
                            setTimeout(() => {
                                location.href = "login";
                            }, 2000);
                        } else {
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Something went wrong",
                                text: res.error,
                                icon: "error",
                            });
                        }
                    },
                    error: function() {
                        alert("error");
                    }
                })
            });


            $(".verify-otp-btn").click(function() {
                let otp = $("#otp").val().trim();
                let th = $(this);
                if (otp.length < 6) {
                    Swal.fire({
                        confirmButtonClass: "btn btn-primary btn-circled",
                        title: "Invalid OTP",
                        text: "",
                        icon: "error",
                    });
                    return;
                }

                $.ajax({
                    url: "signup_helper",
                    method: "POST",
                    data: {
                        verify_otp: otp,
                    },
                    beforeSend: function() {
                        th.html("Verifying..").attr("disabled", true);
                    },
                    success: function(data) {
                        let res;
                        th.html("Verify OTP").attr("disabled", false);
                        try {
                            res = JSON.parse(data);
                        } catch (error) {
                            console.log(error);
                            console.log(data);
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Something went wrong",
                                text: "",
                                icon: "error",
                            });
                            return;
                        }

                        if (res.status == 200) {
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "OTP Verified ",
                                text: "",
                                icon: "success",
                            });
                            $("#otpBlock").hide();
                            $("#resetPassBlock").fadeIn();
                        } else {
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Something went wrong",
                                text: res.error,
                                icon: "error",
                            });
                        }
                    },
                    error: function() {
                        alert("error");
                    }
                })
            });

            $(".send-otp-btn").click(function() {
                let email = $("#email").val().trim();
                let th = $(this);
                if (email.length < 4) {
                    Swal.fire({
                        confirmButtonClass: "btn btn-primary btn-circled",
                        title: "Invalid Email",
                        text: "",
                        icon: "error",
                    });
                    return;
                }

                $.ajax({
                    url: "signup_helper",
                    method: "POST",
                    data: {
                        send_otp: email,
                    },
                    beforeSend: function() {
                        th.html("Sending..").attr("disabled", true);
                    },
                    success: function(data) {
                        let res;
                        th.html("Send OTP").attr("disabled", false);
                        try {
                            res = JSON.parse(data);
                        } catch (error) {
                            console.log(error);
                            console.log(data);
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Something went wrong",
                                text: "",
                                icon: "error",
                            });
                            return;
                        }

                        if (res.status == 200) {
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "OTP has been send to your email " + res.email,
                                text: "",
                                icon: "success",
                            });
                            $("#emailBlock").hide();
                            $("#otpBlock").fadeIn();
                        } else {
                            Swal.fire({
                                confirmButtonClass: "btn btn-primary btn-circled",
                                title: "Error",
                                text: res.error,
                                icon: "error",
                            });
                        }
                    },
                    error: function() {
                        alert("error");
                    }
                })
            });
        })
    </script>

</body>

</html>