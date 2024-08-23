<?php
require 'config/connection.php';
require 'config/cloudinaryConfig.php';

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
}

if (isset($_POST['post'])) {
    $file = '';
    if (isset($_FILES['image'])) {
        $allowed = array('jpeg', 'png', 'jpg', 'JPG', 'PNG', 'JPEG');
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
            header('location: home?s=' . DFENC("invalid_file"));
        }
        if (isset($_FILES['image']['tmp_name'])) {
            $file = getURL($_FILES['image']['tmp_name']);
        }
    }

    $desc = "";
    if (isset($_POST['description'])) {
        $desc = ($_POST['description']);
    }

    $cols = array(
        "user_id" => USER_ID,
        "image" => $file,
        "description" => $desc,
    );

    $db = new DB;
    if ($db->insert("posts", $cols)) {
        header('location: home?s=' . DFENC("posted"));
    } else {
        header('location: home?s=' . DFENC("post_failure"));
    }
} else if (isset($_POST['saveDraft'])) {
    $file = $_FILES['image']['tmp_name'];

    setcookie('description' . $_SESSION['user_id'], $_POST['description'], time() + 60 * 60 * 24 * 30, '/');
    header('location: home.php?post=draft');
}

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
    <link rel="stylesheet" href="./assets/app_styles/icons.min.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

    <style>
        #formLoader {

            display: none;

            position: fixed;

            top: 0;

            left: 0;

            right: 0;

            bottom: 0;

            justify-content: center;

            align-items: center;

            flex-direction: column;

            width: 100%;

            background: rgba(0, 0, 0, 0.75) no-repeat center center;

            z-index: 10000;

        }
    </style>
</head>


<body id="top-header">
    <?php include 'header3.php' ?>


    <section class="section" id="contact">
        <div class="container">
            <form action="<?php echo explode(".", $_SERVER['PHP_SELF'])[0] ?>" id="post-form" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-4">
                        <input onchange="preview()" type="file" name="image" accept="image/*" id="file" style="display: none;">
                        <img id="previewImage" width="100%" onclick="document.getElementById('file').click()" src="https://res.cloudinary.com/dza7mzhl1/image/upload/v1689689532/upload_image_placeholder_grey_yyxr3u.png" alt="Upload Image">
                    </div>
                    <div class="col-sm-8">
                        <textarea name="description" id="description" cols="30" rows="10" class="form-control" placeholder="Write something"><?php if (isset($_COOKIE['description' . $_SESSION['user_id']])) echo $_COOKIE['description' . $_SESSION['user_id']]; ?></textarea>
                    </div>
                </div>
                <div class="row text-right mt-3">
                    <div class="col-sm-12">
                        <input name="post" id="submit" type="submit" class="btn btn-success btn-circled" value="Post">
                        <!-- <input name="saveDraft" type="submit" class="btn btn-dark btn-circled" value="Save Draft"> -->
                        <input name="cancel" onclick="history.back()" type="button" class="btn btn-danger btn-circled" value="Cancel">
                    </div>
                </div>
            </form>
        </div>
    </section>
    <?php include 'footer.php' ?>


    <div id="formLoader">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="spinner-grow text-success" style="height: 5rem; width: 5rem;"></div>
            </div>
        </div>
    </div>

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
    <script>
        $(document).on("submit", "#post-form", function() {
            $("#formLoader").css("display", "flex");
        });

        function isImage(icon) {
            const ext = ['.jpg', '.jpeg', '.png', '.JPG', '.JPEG', '.PNG'];
            return ext.some(el => icon.endsWith(el));
        }

        function preview() {
            if (!isImage($("#file").val())) {
                Swal.fire({
                    customClass: {
                        confirmButton: 'btn btn-white btn-circled',
                    },
                    buttonsStyling: false,
                    icon: 'error',
                    title: 'File format not supported.<br>Please upload .jpg, .jpeg or .png image.',
                    showConfirmButton: true,
                    timer: 6000
                })
                $("#file").val('');
                return;
            }
            document.getElementById("previewImage").src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

</body>

</html>