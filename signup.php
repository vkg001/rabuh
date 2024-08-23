<?php
include 'config/connection.php';
if (isset($_SESSION['user_id'])) {
    header('location: home');
}
include 'config/google/config.php';
include('config/facebook/config.php');
include('config/facebook/index.php');

$showFirstLoginMsg = 0;
if (isset($_GET['social'])) {
    if ($_GET['social'] == 'failed') {
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
    <meta name="description" content="it">
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
                        <h1 class="display-4 font-weight-bold">Register</h1>
                        <p>Get connected with your classmates</p>
                    </div>
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </section>


    <section class="section" id="contact">
        <div class="container">


            <div class="row mb-5">
                <div class="col-sm-12 text-center">
                    Already have an account ? <a href="login.php">Login here</a>
                </div>
            </div>

            <div id="registerBlock">

                <div class="row">
                    <div class="col-lg-12">
                        <!-- form message -->
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-success contact__msg" style="display: none" role="alert">
                                    Your have registered successfully.
                                </div>
                            </div>
                        </div>
                        <!-- end message -->


                        <div class="row">
                            <!-- Input -->
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6 mb-6">
                                <div class="form-group">
                                    <label class="h6 small d-block text-uppercase">
                                        Your name
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control" name="name" id="name" required="" placeholder="Vikas Kumar" type="text">
                                    </div>
                                </div>
                                <div class="row mb-3 text-center" id="nameMsgBlock" style="display: none;">
                                    <div class="col-sm-12 text-danger">
                                        Name can't contain any number or special character ie. %,&,*,# etc.
                                    </div>
                                </div>
                            </div>
                            <!-- End Input -->

                            <!-- Input -->
                            <div class="col-sm-3"></div>
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
                                <div class="row mb-3 text-center" id="emailMsgBlock" style="display: none;">
                                    <div class="col-sm-12 text-danger">
                                        Invalid Email
                                    </div>
                                </div>
                            </div>
                            <!-- End Input -->


                            <!-- Input -->
                            <div class="col-sm-3"></div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6 mb-6">
                                <div class="form-group">
                                    <label class="h6 small d-block text-uppercase">
                                        Create Password
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control" name="password" id="password" required="" placeholder="Create Password" type="text">
                                    </div>
                                </div>
                                <div class="row mb-3 text-center" id="passMsgBlock" style="display: none;">
                                    <div class="col-sm-12 text-danger">
                                        Password should be greater than 7 characters
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3"></div>
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6 mb-6">
                                <div class="form-group">
                                    <label class="h6 small d-block text-uppercase">
                                        Re-Enter Password
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control" name="rpassword" id="rpassword" required="" placeholder="Re-Enter Password" type="password">
                                    </div>
                                </div>
                                <div class="row mb-3 text-center" id="rpassMsgBlock" style="display: none;">
                                    <div class="col-sm-12 text-danger">
                                        Password didn't match.
                                    </div>
                                </div>
                            </div>
                            <!-- End Input -->

                            <!-- Input -->

                            <!-- End Input -->

                        </div>

                        <!-- End Contacts Form -->
                    </div>

                </div>
                <div class="row text-center text-danger" style="display: none;" id="finalMsgBlock">
                    <div class="col-sm-12">
                        Please fill valid details in all mandatory fields.
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-sm-12">
                        <input name="submit" id="submitBtn" type="button" class="btn btn-primary btn-circled" value="Register">
                    </div>
                </div>
            </div>

            <div id="otpBlock" class="row mt-4 mb-4" style="display: none;">
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <label class="h6 small d-block text-uppercase">
                        Enter OTP sent to your E-Mail <span id="showEmail"></span>
                    </label>
                    <input type="number" class="form-control" placeholder="OTP" id="otp">
                </div>

                <div class="col-sm-3"></div>
                <div class="col-sm-3"></div>
                <div class="col-sm-6">
                    <div class="row mt-4">
                        <div class="col-sm-6 text-left">
                            <input name="submit" id="backToForm" type="button" class="btn btn-primary btn-circled" value="Back">
                        </div>
                        <div class="col-sm-6 text-right">
                            <input name="submit" id="verifyOTP" type="button" class="btn btn-primary btn-circled" value="Verify">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row text-center mt-3">
                <div class="col-sm-12">
                    <a class="btn btn-lg btn-google btn-block text-uppercase btn-outline" href="<?php echo $google_client->createAuthUrl(); ?>">
                        <img src="https://img.icons8.com/color/16/000000/google-logo.png"> Register Using Google
                    </a>
                </div>
                <div class="col-sm-12">
                    <a href="config/facebook/callback.php?provider=facebook" class="btn btn-lg btn-google btn-block text-uppercase btn-outline">
                        <i class="fab fa-facebook-f text-primary"></i> Register using Facebook
                    </a>
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

    <script src="js/form/contact.js"></script>
    <script src="js/theme.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            var flag = '<?php echo $showFirstLoginMsg; ?>';
            if (flag == '1') {
                Swal.fire({
                    customClass: {
                        confirmButton: 'btn btn-white btn-circled',
                    },
                    buttonsStyling: false,
                    icon: 'error',
                    title: 'Account creation failed.<br>Please try again.',
                    showConfirmButton: true,
                    timer: 6000
                })
            } else if (flag == '2') {
                Swal.fire({
                    customClass: {
                        confirmButton: 'btn btn-white btn-circled',
                    },
                    buttonsStyling: false,
                    icon: 'info',
                    title: 'No account found.<br> Create an account here.',
                    showConfirmButton: true,
                    timer: 6000
                })
            }
            window.history.pushState('data', 'title', '<?php echo $_SERVER['PHP_SELF'] ?>');
        })

        $('#backToForm').on('click', function() {
            $("#otpBlock").hide();
            $("#registerBlock").fadeIn(400);
        })

        $("#verifyOTP").on('click', function() {
            var otp = $("#otp").val();
            $("#otpResponse").hide();
            $.ajax({
                url: "signup_helper.php",
                method: 'post',
                data: {
                    verifyOTP: otp,
                },
                beforeSend: function() {

                },
                success: function(data) {
                    if (data == 1) {
                        window.location.replace('home.php?registeration=success');
                    } else {
                        $("#otpResponse").fadeIn(300);
                    }
                    Swal.fire('Registration Successful', '', 'success') ;
                    $('#verifyOTP').val("Verify");
                    $('#verifyOTP').attr("disabled", false);
                },
                error: function(data) {
                    alert("Error " + data);
                    $('#verifyOTP').val("Verify");
                    $('#verifyOTP').attr("disabled", false);
                }
            })
        })

        var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

        $(document).on('click', '#submitBtn', function() {
            var msg = $("#finalMsgBlock");
            if (!validateName($("#name").val()) || $("#name").val().length < 3 || $("#password").val().length < 8 || $("#password").val().localeCompare($("#rpassword").val()) != 0 || (!filter.test($("#email").val()))) {
                msg.show();
                return;
            }

            var name = $("#name").val();
            var email = $("#email").val();
            var pass = $("#password").val();

            $('#submitBtn').val("Registering...");
            $('#submitBtn').attr("disabled", true);

            $.ajax({
                url: "signup_helper.php",
                method: 'post',
                data: {
                    createAccount: name,
                    email: email,
                    pass: pass
                },
                beforeSend: function() {

                },
                success: function(data) {
                    if (data == 1) {
                        $("#registerBlock").hide();
                        $("#showEmail").html(email);
                        $("#otpBlock").fadeIn(400);
                    } else if (data == 2) {
                        Swal.fire('Name should be greater than 2 characters', '', 'error') ;
                        // alert("");
                    } else if (data == 3) {
                        Swal.fire('Enter valid email', '', 'error')
                        // alert("");
                    } else if (data == 4) {
                        Swal.fire('Password should be greater than 7 characters', '', 'error')
                        // alert("");
                    } else if (data == -2) {
                        Swal.fire('Already registered', '', 'error') ;
                        // alert(" ");
                    } else {
                        Swal.fire('Something went wrong', '', 'error') ;
                        console.log(data);
                    }
                    $('#submitBtn').val("Register");
                    $('#submitBtn').attr("disabled", false);
                },
                error: function(data) {
                    alert("Error " + data);
                    $('#submitBtn').val("Register");
                    $('#submitBtn').attr("disabled", false);
                }
            })

        })


        // name

        function validateName(name) {
            for (var i = 0; i < name.length; i++) {
                if (name[i].toLowerCase() == name[i].toUpperCase() && name[i] != ' ' && name[i] != '_') {
                    return false;
                }
            }
            return true;
        }

        $(document).on('keyup', '#name', function() {
            if (!validateName($(this).val())) {
                $("#nameMsgBlock").show();
            } else {
                $("#nameMsgBlock").hide();
            }
        })

        // email

        $(document).on('keyup', '#email', function() {
            if ($(this).val() < 5) {
                return;
            }


            if (!filter.test($(this).val())) {
                $("#emailMsgBlock").show();
            } else {
                $("#emailMsgBlock").hide();
            }
        })


        //   password

        $(document).on('focus', '#password', function() {
            $("#passMsgBlock").hide();
        })

        $(document).on('blur', '#password', function() {
            if ($(this).val().length < 8) {
                $("#passMsgBlock").show();
            } else {
                $("#passMsgBlock").hide();
            }
        })

        // confirm password

        $(document).on('focus', '#rpassword', function() {
            $("#rpassMsgBlock").hide();
        })

        $(document).on('blur', '#rpassword', function() {
            // alert('blur');
            if ($(this).val().localeCompare($("#password").val()) != 0) {
                $("#rpassMsgBlock").show();
            } else {
                $("#rpassMsgBlock").hide();
            }
        })
    </script>

</body>

</html>