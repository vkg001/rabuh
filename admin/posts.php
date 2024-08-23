<?php
require "../config/connection.php";
if (!isset($_SESSION['admin_id'])) {
    header("location: ./");
}
$db = new DB();
?>
<!DOCTYPE html>
<html lang="en">

<!-- /projects-clients.html  , Tue, 25 Jan 2022 08:03:48 GMT -->

<head>


    <meta charset="utf-8" />
    <title>Unikit - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $icon; ?>">



    <!-- App css -->
    <link href="../assets/app_styles/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/app_styles/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/app_styles/app.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="../assets/plugins/sweet-alerts/sweetalert2.min.css">

    <style>
        .card-menu-icon {
            position: absolute;
            right: 0.4rem;
            top: 0.3rem;
            font-size: 1.2rem;
        }

        .formLoader {
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

<body id="body">

    <?php require "menu.php"; ?>

    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content-tab">

            <div class="container-fluid mt-3">

                <div class="row" id="posts-wrapper">

                    <?php
                    $posts = $db->select("posts ps", "ps.*, reg.name", "1 = 1 ORDER BY ps.id DESC", "INNER JOIN register reg ON reg.id = ps.user_id");

                    foreach ($posts as $post) {
                    ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card">
                                <?php
                                if ($post['image'] != '') {
                                ?>
                                    <img class="card-img-top img-fluid bg-light-alt" src="<?php echo $post['image'] ?>" alt="Post Picture" style="height: 15rem; object-fit: contain;">
                                <?php
                                }
                                ?>
                                <div class="card-body">
                                    <p class="card-text text-muted ">
                                        <?php
                                        echo $post['description'];
                                        ?>
                                    </p>
                                </div>
                                <div class="card-header">
                                    ID: <?php echo $post['user_id'] ?><br>
                                    <?php echo $post['name'] ?>
                                </div>
                                <div class="card-footer">
                                    <button data-self="<?php echo DFENC($post['id']) ?>" data-target="<?php echo DFENC($post['user_id']) ?>" data-type="warn" class="btn remove-post btn-de-warning btn-sm">Remove & Warn</button>
                                    <button data-self="<?php echo DFENC($post['id']) ?>" data-target="<?php echo DFENC($post['user_id']) ?>" data-type="ban" class="btn remove-post btn-de-danger btn-sm">Remove & Ban</button>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>



                </div>

                <footer class="footer text-center text-sm-start">
                    &copy; <script>
                        document.write(new Date().getFullYear())
                    </script> <?php echo $site_name ?> <span class="text-muted d-none d-sm-inline-block float-end">Crafted with <i class="mdi mdi-heart text-danger"></i> by Vikas</span>
                </footer>
                <!-- end Footer -->
                <!--end footer-->
            </div>
            <!-- end page content -->
        </div>
    </div>






    <div class="formLoader" id="formLoader">
        <div class="row">
            <div class="col-sm-12 text-center">
                <div class="spinner-grow text-success" style="height: 10vh; width: 10vh;"></div>
            </div>
            <div class="col-sm-12 text-center text-white" id="loaderMsg">
                Loading please wait...
            </div>
        </div>
    </div>



    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../assets/plugins/sweet-alerts/sweetalert2.min.js"></script>
    <script src="../assets/app_styles/app.js"></script>

    <script>
        function toggleLoader(msg) {
            $("#loaderMsg").html(msg);
            let loader = $("#formLoader");
            if (loader.css("display") == 'none') {
                loader.css("display", "flex");
            } else {
                loader.css("display", "none");
            }
        }

        $(document).ready(function() {
            $(document).on("click", ".remove-post", function() {
                let action_type = $(this).data("type");
                let self = $(this).data("self");
                let target = $(this).data("target");
                let th = $(this).parent().parent().parent();

                toggleLoader("Issuing notice please wait...");

                $.ajax({
                    url: "user_helper",
                    method: "POST",
                    data: {
                        remove_post: true,
                        action_type,
                        self,
                        target,
                    },
                    success: function(data) {
                        let res;
                        toggleLoader("");
                        try {
                            res = $.parseJSON(data);
                        } catch (error) {
                            console.log(error);
                            console.log(data);
                            alert("error");
                            return;
                        }

                        if (res.status == 200) {
                            th.fadeOut();
                            Swal.fire("Notice issued", "", "success");
                        } else {
                            alert(res.error);
                        }
                    },
                    error: function() {
                        alert("Error");
                    }
                })
            })
        })
    </script>

</body>

</html>