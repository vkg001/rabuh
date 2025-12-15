<?php
require "../config/connection.php";
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

                <div class="row" id="user-data-wrapper">
                    <?php

                    $users_list = $db->select("register", "*", "1 = 1 ORDER BY id DESC LIMIT 50");

                    foreach ($users_list as $user) {
                        if ($user['profile_pic'] == '') {
                            $user['profile_pic'] = PROFILE_PLACEHOLDER;
                        }


                        if ($user['cover_photo'] == '') {
                            $user['cover_photo'] = COVER_PHOTO_PLACEHOLDER;
                        }
                    ?>
                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="form-check form-switch form-switch-success">
                                        <input class="form-check-input toggle-verified-tag" data-self="<?php echo DFENC($user['id']) ?>" type="checkbox" id="is_verified" <?php echo ($user['is_verified'] == 1) ? "checked" : ""; ?>>
                                    </div>
                                    <a href="../profile?u=<?php echo DFENC($user['id']) ?>">
                                        <img src="<?php echo $user['profile_pic'] ?>" alt="user" class="rounded-circle thumb-xl">
                                    </a>
                                    <i class="mdi mdi-dots-vertical click-effect card-menu-icon dropdown-toggle" data-bs-toggle="dropdown"></i>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-item click-effect remove-detail" data-self="<?php echo DFENC($user['id']) ?>" data-type="name">Remove Name</div>
                                        <div class="dropdown-item click-effect remove-detail" data-self="<?php echo DFENC($user['id']) ?>" data-type="profile">Remove Profile</div>
                                        <div class="dropdown-item click-effect remove-detail" data-self="<?php echo DFENC($user['id']) ?>" data-type="cover">Remove Cover</div>
                                        <div class="dropdown-item click-effect remove-detail" data-self="<?php echo DFENC($user['id']) ?>" data-type="address">Remove Address</div>
                                        <div class="dropdown-item click-effect remove-detail" data-self="<?php echo DFENC($user['id']) ?>" data-type="bio">Remove Bio</div>
                                    </div>

                                    <h5 class="font-16 fw-bold username-text" data-parent="3">
                                        <?php echo $user['name'] ?>
                                    </h5>
                                    <span class="text-muted me-3 fw-semibold">
                                        <i class="fa fa-envelope me-1 text-secondary"></i>
                                        <span class="user-email-text" data-parent="4">
                                            <?php echo $user['email'] ?>
                                        </span>
                                    </span>
                                    <br>
                                    <span class="text-muted me-3 fw-semibold">
                                        <i class="las la-map-marker me-1 text-secondary"></i>
                                        <span class="user-location-text" data-parent="4">
                                            <?php echo $user['location'] ?>
                                        </span>
                                    </span>
                                    <p class="text-muted mt-1 user-bio-text" data-parent="3">
                                        <?php echo $user['bio'] ?>
                                    </p>
                                    <button type="button" class="btn btn-sm btn-de-warning" data-bs-toggle="modal" data-bs-target="#warn-user-modal-<?php echo base64_encode(DFENC($user['id'])) ?>">
                                        <i class="mdi mdi-alert-outline"></i>
                                        Warn
                                    </button>
                                    <button type="button" class="btn btn-sm btn-de-danger ban-user" data-self="<?php echo base64_encode(DFENC($user['id'])) ?>">
                                        <?php
                                        echo ($user['status'] != '4') ? '<i class="mdi mdi-block-helper"></i> Ban' : "Unban";
                                        ?>
                                    </button>
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






    <?php

    foreach ($users_list as $user) {
    ?>

        <div class="modal fade" id="warn-user-modal-<?php echo base64_encode(DFENC($user['id'])) ?>" tabindex="-1" role="dialog" aria-labelledby="warn-user-modal-<?php echo base64_encode(DFENC($user['id'])) ?>Label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h6 class="modal-title m-0" id="warn-user-modal-<?php echo base64_encode(DFENC($user['id'])) ?>Label">Issue a Warning Notice to <b>"<?php echo $user['name'] ?>"</b></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <textarea name="" id="warning-msg-for-<?php echo base64_encode(DFENC($user['id'])) ?>" cols="30" placeholder="Type warning message here..." rows="10" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-de-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-warning send-warning-btn btn-sm" data-self="<?php echo base64_encode(DFENC($user['id'])) ?>" data-msg="#warning-msg-for-<?php echo base64_encode(DFENC($user['id'])) ?>">Issue Warning</button>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }

    ?>



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
        const SERVER_LINK = "user_helper";

        function toggleLoader(msg) {
            $("#loaderMsg").html(msg);
            let loader = $("#formLoader");
            if (loader.css("display") == 'none') {
                loader.css("display", "flex");
            } else {
                loader.css("display", "none");
            }
        }

        function removeMe(target, parentLevel) {
            for (let i = 0; i < parentLevel; i++) {
                target = target.parent();
            }

            target.remove();
        }

        function getData(target, parentLevel) {
            for (let i = 0; i < parentLevel; i++) {
                target = target.parent();
            }
            return target.html();
        }

        $(document).ready(function() {

            $(document).on("change", ".toggle-verified-tag", function() {
                let self = $(this).data("self");
                $.ajax({
                    url: SERVER_LINK,
                    method: "POST",
                    data: {
                        toggle_verification: true,
                        self,
                    },
                    success: function(data) {
                        let res;
                        try {
                            res = $.parseJSON(data);
                        } catch (e) {
                            console.log(e);
                            console.log(data);
                            Swal.fire("Error", "Something went wrong", "error");
                            return;
                        }

                        if (res.status == 200) {
                        } else {
                            Swal.fire("Error", res.error, "error");
                        }
                    }
                })
            })

            $(document).on("click", ".remove-detail", function() {
                let type = $(this).data("type");
                let self = $(this).data("self");

                $.ajax({
                    url: SERVER_LINK,
                    method: "POST",
                    data: {
                        remove_detail: true,
                        type,
                        self,
                    },
                    beforeSend: function() {
                        toggleLoader("Removing data please wait...");
                    },
                    success: function(data) {
                        let res;
                        toggleLoader("");
                        try {
                            res = $.parseJSON(data);
                        } catch (e) {
                            console.log(e);
                            console.log(data);
                            Swal.fire("Error", "Something went wrong", "error");
                            return;
                        }

                        if (res.status == 200) {
                            Swal.fire("Removed", "", "success");
                            setTimeout(() => {
                                toggleLoader("Refreshing content please wait...");
                                location.reload();
                            }, 2000);
                        } else {
                            Swal.fire("Error", res.error, "error");
                        }
                    },
                    error: function() {
                        alert("Server not reachable");
                    }
                })
            });

            $(document).on("click", ".ban-user", function() {
                let self = $(this).data("self");
                let th = $(this);
                $.ajax({
                    url: SERVER_LINK,
                    method: "POST",
                    data: {
                        ban_user: true,
                        self,
                    },
                    beforeSend: function() {
                        toggleLoader("Banning account please wait...");
                    },
                    success: function(data) {
                        let res;
                        toggleLoader("");
                        try {
                            res = $.parseJSON(data);
                        } catch (e) {
                            console.log(e);
                            console.log(data);
                            Swal.fire("Error", "Something went wrong", "error");
                            return;
                        }

                        if (res.status == 200) {
                            if (th.html().trim() != 'Unban') {
                                Swal.fire("Banned", "", "success");
                                th.html("Unban");
                            } else {
                                Swal.fire("Ban Removed", "", "success");
                                th.html('<i class="mdi mdi-block-helper"></i> Ban');
                            }
                        } else {
                            Swal.fire("Error", res.error, "error");
                        }
                    },
                    error: function() {
                        alert("Server not reachable");
                    }
                })
            });

            $(document).on("click", ".send-warning-btn", function() {
                let self = $(this).data("self");
                let msg = $($(this).data("msg")).val();

                let textarea = $($(this).data("msg"));

                $.ajax({
                    url: SERVER_LINK,
                    method: "POST",
                    data: {
                        warn_user: true,
                        self,
                        msg,
                    },
                    beforeSend: function() {
                        toggleLoader("Issuing warning please wait...");
                    },
                    success: function(data) {
                        let res;
                        toggleLoader("");
                        try {
                            res = $.parseJSON(data);
                        } catch (e) {
                            console.log(e);
                            console.log(data);
                            Swal.fire("Error", "Something went wrong", "error");
                            return;
                        }

                        if (res.status == 200) {
                            textarea.val("");
                            $("#warn-user-modal-" + self).modal("hide");
                            Swal.fire("Warning issued", "", "success");
                        } else {
                            Swal.fire("Error", res.error, "error");
                        }
                    },
                    error: function() {
                        alert("Server not reachable");
                    }
                })
            });

            const ALL_USERS = $("#user-data-wrapper").html();
            $(document).on("input", ".top-search", function() {
                let keyword = $(this).val().trim().toLowerCase();
                $("#user-data-wrapper").html(ALL_USERS);
                let new_data = "";

                $(".username-text").each(function() {
                    let text = $(this).html().trim().toLowerCase();
                    if (text.search(keyword) != -1) {
                        new_data += '<div class="col-lg-3">' + getData($(this), $(this).data("parent")) + '</div>';
                        removeMe($(this), $(this).data("parent"));
                    }
                });


                $(".user-email-text").each(function() {
                    let text = $(this).html().trim().toLowerCase();
                    if (text.search(keyword) != -1) {
                        new_data += '<div class="col-lg-3">' + getData($(this), $(this).data("parent")) + '</div>';
                        removeMe($(this), $(this).data("parent"));
                    }
                });


                $(".user-location-text").each(function() {
                    let text = $(this).html().trim().toLowerCase();
                    if (text.search(keyword) != -1) {
                        new_data += '<div class="col-lg-3">' + getData($(this), $(this).data("parent")) + '</div>';
                        removeMe($(this), $(this).data("parent"));
                    }
                });


                $(".user-bio-text").each(function() {
                    let text = $(this).html().trim().toLowerCase();
                    if (text.search(keyword) != -1) {
                        new_data += '<div class="col-lg-3">' + getData($(this), $(this).data("parent")) + '</div>';
                        removeMe($(this), $(this).data("parent"));
                    }
                });


                $("#user-data-wrapper").html(new_data);
            });
        })
    </script>

</body>

</html>