<?php
require "./config/connection.php";

if (isset($_POST['contact_us'])) {
    $db = new DB();
    $name = $db->realEscape(trim($_POST['name']));
    $email = $db->realEscape(trim($_POST['email']));
    $phone = $db->realEscape(trim($_POST['phone']));
    $subject = $db->realEscape(trim($_POST['subject']));
    $message = $db->realEscape(trim($_POST['message']));

    $cols = array(
        'name' => $name,
        'email' => $email,
        'mobile' => $phone,
        'subject' => $subject,
        'message' => $message,
    );

    if ($db->insert("contact_us", $cols)) {
        $show_insert_msg = 1;
    } else {
        $show_insert_msg = 2;
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
    <link rel="stylesheet" href="./assets/plugins/sweet-alerts/sweetalert2.min.css">

    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">

</head>


<body id="top-header">
    <!-- LOADER TEMPLATE -->
    <div id="page-loader">
        <div class="loader-icon fa fa-spin colored-border"></div>
    </div>
    <!-- /LOADER TEMPLATE -->


    <?php
    require "./header2.php";
    ?>

    <!-- HERO
================================================== -->
    <section class="page-banner-area page-contact">
        <div class="overlay"></div>
        <!-- Content -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-12 col-12 text-center">
                    <div class="page-banner-content">
                        <h1 class="display-4 font-weight-bold">Got a question?</h1>
                        <p>We'd love to talk about how we can help you.</p>
                    </div>
                </div>
            </div> <!-- / .row -->
        </div> <!-- / .container -->
    </section>


    <!-- SECTIONS
    ================================================== -->
    <section id="contact-info">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-6 col-md-6">
                    <a href="mailto:vkg360.vikas@gmail.com?subject=Enquiry About Rabuh">
                        <div class="contact-info-block text-center">
                            <i class="pe-7s-mail"></i>
                            <h4>Email</h4>
                            <p class="lead">vkg360.vikas@gmail.com</p>
                        </div>
                    </a>
                </div>
                <div class="col-sm-6 col-md-6">
                    <a href="tel:+919045097609">
                        <div class="contact-info-block text-center">
                            <i class="pe-7s-phone"></i>
                            <h4>Phone Number</h4>
                            <p class="lead">+919045097609</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="contact">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-8 col-lg-6">
                    <h5>Leave a Message</h5>
                    <!-- Heading -->
                    <h2 class="section-title mb-2 ">
                        Tell us about <span class="font-weight-normal">yourself</span>
                    </h2>

                    <!-- Subheading -->
                    <p class="mb-5 ">
                        Whether you have questions or you would just like to say hello, contact us.
                    </p>

                </div>
            </div> <!-- / .row -->

            <div class="row">
                <div class="col-lg-6">
                    <!-- form message -->
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-success contact__msg" style="display: none" role="alert">
                                Your message was sent successfully.
                            </div>
                        </div>
                    </div>
                    <!-- end message -->
                    <!-- Contacts Form -->
                    <form method="POST" class="contact_form" action="">
                        <div class="row">
                            <!-- Input -->
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
                            </div>
                            <!-- End Input -->

                            <!-- Input -->
                            <div class="col-sm-6 mb-6">
                                <div class="form-group">
                                    <label class="h6 small d-block text-uppercase">
                                        Your email address
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group ">
                                        <input class="form-control" name="email" id="email" required="" placeholder="vkg360.vikas@gmail.com" type="email">
                                    </div>
                                </div>
                            </div>
                            <!-- End Input -->

                            <div class="w-100"></div>

                            <!-- Input -->
                            <div class="col-sm-6 mb-6">
                                <div class="form-group">
                                    <label class="h6 small d-block text-uppercase">
                                        Subject
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group">
                                        <input class="form-control" name="subject" id="subject" required="" placeholder="Data Privacy" type="text">
                                    </div>
                                </div>
                            </div>
                            <!-- End Input -->

                            <!-- Input -->
                            <div class="col-sm-6 mb-6">
                                <div class="form-group">
                                    <label class="h6 small d-block text-uppercase">
                                        Your Phone Number
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="input-group ">
                                        <input class="form-control" id="phone" name="phone" required="" placeholder="+91-9045097609" type="number">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Input -->
                        <div class="form-group mb-5">
                            <label class="h6 small d-block text-uppercase">
                                How can we help you?
                                <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <textarea class="form-control" rows="4" name="message" id="message" required="" placeholder="Hi there, I would like to ..."></textarea>
                            </div>
                        </div>
                        <!-- End Input -->

                        <div class="">
                            <input name="contact_us" type="submit" class="btn btn-primary btn-circled" value="Send Message">

                            <p class="small pt-3">We'll get back to you in 1-2 business days.</p>
                        </div>
                    </form>
                    <!-- End Contacts Form -->
                </div>
            </div>
        </div>
    </section>

    <?php require "./footer.php" ?>
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
    <script src="./assets/plugins/sweet-alerts/sweetalert2.min.js"></script>
    <script src="js/form/contact.js"></script>
    <script src="js/theme.js"></script>
    <script>
        $(document).ready(function() {

            <?php
            if (isset($show_insert_msg)) {
                switch ($show_insert_msg) {
                    case 1:
                        ?>
                        Swal.fire("Message Submitted", "", "success");
                        <?php
                        break;
                    case 2:
                        ?>
                        Swal.fire("Error", "", "error");
                        <?php
                        break;
                }
            }
            ?>

            $(".contact_form").submit(function(e) {
                let flag = true;
                if ($('input[name="name"]').val().trim().length < 3) {
                    flag = false;
                    let x = $('input[name="name"]');
                    x.css("border-color", "red");
                    setTimeout(() => {
                        x.css("border-color", "#ced4da");
                    }, 3000);
                }

                if ($('input[name="email"]').val().trim().length < 3) {
                    flag = false;
                    let x = $('input[name="email"]');
                    x.css("border-color", "red");
                    setTimeout(() => {
                        x.css("border-color", "#ced4da");
                    }, 3000);
                }

                if ($('input[name="phone"]').val().trim().length < 3) {
                    flag = false;
                    let x = $('input[name="phone"]');
                    x.css("border-color", "red");
                    setTimeout(() => {
                        x.css("border-color", "#ced4da");
                    }, 3000);
                }

                if ($('input[name="subject"]').val().trim().length < 3) {
                    flag = false;
                    let x = $('input[name="subject"]');
                    x.css("border-color", "red");
                    setTimeout(() => {
                        x.css("border-color", "#ced4da");
                    }, 3000);
                }

                if ($('textarea[name="message"]').val().trim().length < 3) {
                    flag = false;
                    let x = $('textarea[name="message"]');
                    x.css("border-color", "red");
                    setTimeout(() => {
                        x.css("border-color", "#ced4da");
                    }, 3000);
                }

                if (!flag) {
                    e.preventDefault();
                }
            });
        })
    </script>

</body>

</html>