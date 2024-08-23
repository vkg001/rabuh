<?php
$isp = $ip = $region = "--";

// $crlObj = curl_init();
// curl_setopt($crlObj, CURLOPT_URL, "http://ip-api.com/json");
// curl_setopt($crlObj, CURLOPT_RETURNTRANSFER, 1);

// $resJson = curl_exec($crlObj);

// $infoObj = json_decode($resJson);
// // print_r($infoObj);
// $region = $infoObj->regionName;
// $isp = $infoObj->isp;
// $ip = $infoObj->query;


?>
<script src="plugins/jquery/jquery.min.js"></script>
<style>
    /* .pop-up-menu-container {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        z-index: 1500 !important;
        position: fixed;
        bottom: 3rem;
        right: 73rem;
        height: fit-content;
    }

    .option-pop-up {
        border: 1px solid #21c87a;
        border-radius: 50%;
        height: 4rem;
        width: 4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        color: white;
        background-color: #21c87a;
        font-weight: 900;
    }


    .pop-up-menu {
        padding: 0.5rem;
        transition: all 1s !important;
        opacity: 0;
        height: 0;
        z-index: 999;
    }

    .pop-up-menu>.menu-option {
        text-decoration: none;
        padding: 0.6rem;
        margin-bottom: 0.3rem;
        border: 1px solid #21c87a;
        border-radius: 50%;
        height: 4rem;
        width: 4rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #21c87a;
        transition: all 0.3s;
        cursor: pointer;
    }


    .pop-up-menu-container:hover .pop-up-menu {
        animation: pop-up-menu 1s forwards;
    }

    .pop-up-menu-container:hover .option-pop-up {
        background-color: #018648;
        color: white;
    }

    .popup-icon-message:hover {
        background-color: mediumseagreen;
        color: white;
        animation: msg-icon-anim 1s linear forwards;
    }


    @keyframes msg-icon-anim {
        0% {
            transform: scaleX(1);
        }

        50% {
            transform: scaleX(-1);
        }

        100% {
            transform: scaleX(1);
        }
    }

    .popup-icon-settings:hover {
        background-color: mediumseagreen;
        color: white;
        border: 1px solid black;
        animation: setting-icon-anim 2s linear infinite;
    }

    @keyframes setting-icon-anim {
        0% {
            transform: rotateZ(0deg);
        }

        100% {
            transform: rotateZ(360deg);
        }
    }

    .popup-icon-post:hover {
        background-color: mediumseagreen;
        color: white;
        border: 1px solid mediumseagreen;
        animation: post-icon-anim 1s linear infinite;
    }

    @keyframes post-icon-anim {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }


    @keyframes pop-up-menu {
        from {
            height: 0;
            opacity: 0;
        }

        to {
            height: 100%;
            opacity: 1;
        }
    } */


    .notification-container {
        position: absolute;
        display: flex;
        flex-direction: column;
        background-color: #f1f1f1;
        border: 1px solid #21c87a;
        min-width: 40%;
        color: black;
        border-radius: 0.5rem;
        max-height: 70vh;
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 0 1rem rgba(0, 0, 0, 0.4);
        opacity: 0;
        height: 0;
        z-index: 2;
    }

    .notification-anim-show {
        animation: notif-anim-show 0.3s linear forwards;
    }

    @keyframes notif-anim-show {
        from {
            height: 0vh;
            opacity: 0;
        }

        to {
            opacity: 1;
            height: 70vh;
        }
    }


    .notification-anim-hide {
        animation: notif-anim-hide 0.3s linear forwards;
    }

    @keyframes notif-anim-hide {
        from {
            height: 70vh;
            opacity: 1;
        }

        to {
            opacity: 0;
            height: 0vh;
        }
    }

    .notification-tile {
        padding: 1rem;
        border-bottom: 1px solid grey;
        transition: all 0.2s linear;
        cursor: pointer;
        display: flex;
        flex-direction: row;
    }

    .notification-tile:hover {
        background-color: #21c87a;
        border-bottom-color: white;
    }


    .notification-tile:hover .notification-text {
        color: white;
    }

    .notification-tile:last-child {
        border-bottom: 0;
    }

    .notification-tile .notification-icon img {
        height: 2rem;
        width: 2rem;
        border-radius: 50%;
    }


    .notification-tile .notification-text {
        font-size: 0.7rem;
        color: grey;
        margin-left: 1rem;
    }

    .data_item {
        min-width: 15rem;
        border-radius: 0.4rem;
        transition: all 0.2s linear;
    }

    .nav-item {
        cursor: pointer;
    }

    .notification-header {
        padding: 0.7rem;
        color: #21c87a;
        border-bottom: 1px solid;
        background-color: white;
        display: flex;
        align-items: center;
    }

    .notification-title {
        font-weight: 600;
        text-align: center;
        font-size: 1.1rem;
    }

    .new-notification {
        background-color: #21c87a24;
    }

    .load-more-notifications {
        padding: 1rem;
        text-align: center;
        color: #26af26;
        font-size: 1.4rem;
    }

    .load-more-anim {
        display: inline-block;
        animation: load-more-anim 0.7s linear infinite;
    }

    .load-more-anim::content {
        display: none;
    }

    @keyframes load-more-anim {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(-360deg);
        }
    }

    .notification-mark {
        display: flex;
        position: absolute;
        top: 1.4rem;
        font-size: 0.6rem;
        background: red;
        width: 0.9rem;
        height: 0.9rem;
        border-radius: 50%;
        right: 39.7rem;
        justify-content: center;
    }

    .mark-read-btn {
        margin-left: 16.9rem;
        font-size: 0.8rem;
    }

    .notification-backdrop {
        height: 100vh;
        position: absolute;
        width: 100vw;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.2);
        z-index: 1;
        display: none;
        cursor: default;
    }

    .notif-date {
        text-align: right;
        position: absolute;
        right: 0.8rem;
        padding-top: 2rem;
        font-size: 0.6rem;
        font-weight: 600;
    }

    .loader-icon {
        border-right: 3px solid #21c87a !important;
        top: 50vh;
    }

    #page-loader {
        z-index: 9999;
        position: absolute;
        background: white;
        width: 100%;
        top: -7rem;
        bottom: 0;
        align-items: center;
        display: flex;
        justify-content: center;
    }
</style>

<!-- <div class="pop-up-menu-container">
    <div class="pop-up-menu">
        <a href="messages" class="menu-option popup-icon-message">
            <div class="ti-comment">
            </div>
        </a>
        <div class="menu-option popup-icon-settings">
            <div class="ti-settings">
            </div>
        </div>
        <a href="create_post" class="menu-option popup-icon-post">
            <div class="ti-plus">
            </div>
        </a>
    </div>
    <div class="option-pop-up">
        <div class="ti-plus">
        </div>
    </div>
</div> -->

<!-- LOADER TEMPLATE -->
<div id="page-loader">
    <div class="loader-icon fa fa-spin colored-border"></div>
</div>

<div style="position:fixed; z-index: 99; min-width: 100%;top: 0;">
    <div class="top-bar bg-dark " id="top-bar" style="display: none;">
        <div class="container p-2">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 d-none d-md-block">
                    <div class="top-bar-left text-white">
                        <i class="fa fa-map-marker"></i>
                        <span class="ml-2"><?php echo $region . " | " . $isp . " | " . $ip; ?></span>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 text-right bg-black">

                </div>
            </div>
        </div>
    </div>

    <!-- NAVBAR
    ================================================= -->
    <div class="main-navigation" id="mainmenu-area" style="margin-top: 0; border-radius: 0 !important;">
        <div class="">
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary main-nav navbar-togglable">

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
                        <li class="nav-item">
                            <a class="nav-link" href="<?php if (isset($_SESSION['user_id'])) echo 'home.php';
                                                        else echo 'index.php'; ?>" id="navbarWelcome" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-home"></i> Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="profile" class="nav-link">
                                My Profile
                                <i class="fa fa-user"></i>
                            </a>
                        </li>
                        <li class="nav-item d-md-none">
                            <a href="message.php" class="nav-link">
                                Message
                                <i class="fa fa-comment"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="create_post" class="nav-link">
                                Create Post
                                <i class="ti-plus"></i>
                            </a>
                        </li>

                        <?php
                        if (isset($_SESSION['admin_id'])) {
                        ?>
                            <li class="nav-item">
                                <a href="./admin/" class="nav-link">
                                    Admin Panel
                                </a>
                            </li>
                        <?php
                        } else {
                        ?>
                            <li class="nav-item">
                                <a href="profile?cmd=<?php echo base64_encode("OPEN_FRIEND_LIST") ?>" class="nav-link">
                                    Friends
                                    <i class="fa fa-user-friends"></i>
                                </a>
                            </li>
                        <?php
                        }
                        ?>

                        <li class="nav-item">
                            <?php
                            $db = new DB();
                            $notifications = $db->select("notification", "*", "user_id = " . USER_ID . " ORDER BY id DESC LIMIT 20");
                            $_SESSION['LAST_NOTIFICATION'] = PHP_INT_MAX;
                            $_SESSION['FIRST_NOTIFICATION'] = 0;

                            $unread = 0;
                            foreach ($notifications as $notif) {
                                if ($notif['is_read'] == 0) {
                                    $unread++;
                                }
                            }
                            ?>
                            <span class="nav-link notification-btn">
                                Notification
                                <div class="notification-mark" <?php echo ($unread == 0) ? 'style="display: none;"' : ""; ?>><?php echo $unread; ?></div>
                                <i class="fa fa-bell"></i>
                            </span>
                            <div class="notification-backdrop"></div>
                            <div class="notification-container">
                                <div class="notification-header">
                                    <div class="notification-title">
                                        Notifications
                                    </div>
                                    <div class="mark-read-btn click-effect">
                                        Mark All as Read <i class="far fa-envelope-open"></i>
                                    </div>
                                </div>
                                <span id="notification-body-wrapper">
                                    <?php
                                    foreach ($notifications as $notif) {
                                        $_SESSION['LAST_NOTIFICATION'] =  min($notif['id'], $_SESSION['LAST_NOTIFICATION']);
                                        $_SESSION['FIRST_NOTIFICATION'] =  max($notif['id'], $_SESSION['FIRST_NOTIFICATION']);
                                    ?>
                                        <a href="<?php echo ($notif['link'] != "") ? $notif['link'] : "#"; ?>" data-id="<?php echo DFENC($notif['id']) ?>" class="notification-tile <?php echo ($notif['is_read'] == 0) ? "new-notification" : ""; ?>" style="<?php echo ($notif['notification_type'] == 1) ? "background-color: yellow;" : ""; ?> <?php echo ($notif['notification_type'] == 2) ? "background-color: red;" : ""; ?>">
                                            <?php
                                            if ($notif['image'] != "") {
                                            ?>
                                                <div class="notification-icon">
                                                    <img src="<?php echo $notif['image'] ?>" alt="">
                                                </div>
                                            <?php
                                            }
                                            ?>
                                            <div class="notification-text" style="<?php echo ($notif['notification_type'] == 2) ? "color: white;" : ""; ?>">
                                                <?php echo $notif['text'] ?>
                                            </div>
                                            <div class="notif-date text-muted" style="<?php echo ($notif['notification_type'] == 2) ? "color: white !important;" : ""; ?>">
                                                <?php echo agoTime($notif['created_date']) ?>
                                            </div>
                                        </a>
                                    <?php
                                    }
                                    ?>
                                </span>
                                <div class="load-more-notifications">
                                    <i class="click-effect ti-reload" style="font-weight: 900;"></i>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item ms-5 my-auto" style="margin-top: -0.5rem !important; max-width: fit-content;">
                            <a class="nav-link">
                                <input type="text" class="form-control" placeholder="Search" id="searchPeople" style="border-radius: 1rem; min-width: 20rem;">
                                <div id="search_result" style="position: absolute; z-index: 10; display: block; box-shadow: -1px 1px 11px silver; width: fit-content; max-width: fit-content;">
                                </div>
                            </a>
                        </li>

                    </ul>

                    <ul class="ml-lg-auto list-unstyled m-0">
                        <li>
                            <?php if (isset($_SESSION['user_id'])) { ?>
                                <a href='#' id="logout" class="btn btn-white btn-circled" style="position: sticky; top: 0;">Logout</a>
                            <?php } else { ?>
                                <a href="login" class="btn btn-white btn-circled">Login</a>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>

<div class="d-none" id="notification-tile-ui">
    <a href="//NOTIF-LINK//" class="notification-tile //NOTIF-NEW//">
        <div class="notification-icon //NOTIF-IMG//">
            <img src="//NOTIF-IMG-LINK//" alt="">
        </div>
        <div class="notification-text">
            //NOTIF-TEXT//
        </div>
    </a>
</div>

<div id="load-more-notif-ui" class="d-none">
    <div class="load-more-notifications">
        <i class="click-effect ti-reload" style="font-weight: 900;"></i>
    </div>
</div>


<script src="./assets/js/header-3-js.js"></script>
<script>
    $(document).ready(function() {


        $(document).on("click", ".load-more-notifications", function() {
            let th = $(this);
            $.ajax({
                url: "home_helper",
                method: "POST",
                data: {
                    load_notifications: true,
                },
                beforeSend: function() {
                    th.children("ti-reload").addClass("load-more-anim");
                },
                success: function notification_updator(data) {
                    let res;
                    try {
                        res = $.parseJSON(data);
                    } catch (e) {
                        console.log(e);
                        console.log(data);
                        Swal.fire("Error", res.error, "error");
                        return;
                    }
                    if (data.error) {
                        console.log(data);
                        Swal.fire("Error", res.error, "error");
                        return;
                    } else if (res.success) {
                        if (res.appendable) {
                            let ui = $("#notification-tile-ui").html();
                            res.appendable.forEach(notif => {
                                let tile = ui.replace("//NOTIF-LINK//", notif.link);
                                tile = tile.replace("//NOTIF-NEW//", notif.is_new);
                                tile = tile.replace("//NOTIF-IMG//", notif.is_image);
                                tile = tile.replace("//NOTIF-IMG-LINK//", notif.image_link);
                                tile = tile.replace("//NOTIF-TEXT//", notif.text);
                                th.parent().append(tile);
                            });
                            th.parent().append($("#load-more-notif-ui").html());
                            th.remove();
                        } else if (res.no_data) {
                            th.html(res.no_data);
                        }


                        if (res.prependable) {
                            let ui = $("#notification-tile-ui").html();
                            res.appendable.forEach(notif => {
                                let tile = ui.replace("//NOTIF-LINK//", notif.link);
                                tile = tile.replace("//NOTIF-NEW//", notif.is_new);
                                tile = tile.replace("//NOTIF-IMG//", notif.is_image);
                                tile = tile.replace("//NOTIF-IMG-LINK//", notif.image_link);
                                tile = tile.replace("//NOTIF-TEXT//", notif.text);
                                th.parent().prepend(tile);
                            });
                        }
                    }
                },
                error: function() {
                    Swal.fire("Error", "Something went wrong.", "error");
                }
            })
        });

    })
</script>