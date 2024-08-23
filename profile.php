<?php
require 'config/connection.php';


$db = new DB();

if (!isset($_GET['u'])  &&  USER_ID < 1) {
    header("home");
}

define("STORY_CARD_WIDTH", "8");

$current_command = 0;
if (isset($_GET['cmd'])) {
    switch (base64_decode($_GET['cmd'])) {
        case 'OPEN_FRIEND_LIST':
            $current_command = 1;
    }
}


if (isset($_GET['u'])) {
    $user_id = (int)DFDEC($_GET['u']);

    $page_user_data = $db->select("register", "*", "id = $user_id");

    if (!isset($page_user_data[0]['id'])  ||  $db->checkRelationship($user_id)  == '3') {
        require "404.php";
        die;
    }

    $page_user_data = $page_user_data[0];
} else {
    $page_user_data = $user_data;
}


define("PAGE_TITLE", strShort($page_user_data['name'], 15));

if ($page_user_data['profile_pic'] == '') {
    $page_user_data['profile_pic'] = PROFILE_PLACEHOLDER;
}

if ($page_user_data['cover_photo'] == '') {
    $page_user_data['cover_photo'] = COVER_PHOTO_PLACEHOLDER;
}

if (isset($_SESSION['admin_id'])) {
    $_SESSION['VISITING_PROFILE'] = $page_user_data['id'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo PAGE_TITLE ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="it">
    <meta name="keywords" content="">

    <meta name="author" content="themefisher.com">

    <title><?php echo $site_name ?></title>
    <link rel="icon" href="<?php echo $icon ?>">

    <link rel="stylesheet" href="assets/app_styles/app.min.css">
    <script src="assets/app_styles/app.js"></script>

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
    <link rel="stylesheet" href="./assets/chat_styles/style.css">
    <link href="./assets/icons/material-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/home-styles.css">
    <link rel="stylesheet" href="./assets/css/profile.css">
    <link rel="stylesheet" href="./assets/css/emoji-styles.css">
    <link rel="stylesheet" href="./assets/plugins/sweet-alerts/sweetalert2.min.css">
    <style>
        .disabled-scroll {
            overflow: hidden !important;
        }

        .btn-custom {
            padding: 0.4rem;
            margin-left: 1.4rem;
            margin-right: 1rem;
            text-transform: none;
            font-weight: normal;
        }

        .banned-banner {
            position: fixed;
            font-size: 12rem;
            z-index: 555;
            color: #ff000075;
            rotate: -40deg;
            left: 15rem;
            letter-spacing: 1rem;
            text-transform: uppercase;
            top: 13rem;
        }
    </style>
</head>

<body>
    <?php
    if ($current_command > 0) {
    ?>
        <div id="request-loader" style="z-index: 9999999; position: fixed; display: flex; flex-direction: column; justify-content: center; align-items: center; width: 100vw; top:0; bottom:0; right:0;left:0; background: #545454b8;">
            <div class="spinner-grow text-success" style="height: 4rem; width: 4rem;"></div>
            <div style="color: White;">
                Processing Request Please Wait ...
            </div>
        </div>
    <?php
    }


    if ($page_user_data['status'] == 4) {
    ?>
        <div class="banned-banner">Banned</div>
    <?php
    }
    ?>



    <main style="overflow: clip;">
        <div class="mb-5">
            <?php require 'header3.php' ?>
        </div>
        <div id="profile-upper">
            <div id="profile-banner-image" class="d-flex">
                <img id="user-cover-photo" src="<?php echo $page_user_data['cover_photo'] ?>" alt="Cover Photo" style="object-fit: cover;">
            </div>
            <div id="profile-d">
                <div id="profile-pic" class="zoom-on-hover">
                    <img src="<?php echo $page_user_data['profile_pic'] ?>" id="user-profile" alt="Profile" style="height: 100%; width: 100%;">
                </div>

                <?php
                if ($page_user_data['is_verified'] == 1) {
                ?>
                    <i class="fa fa-check-circle verified-profile-btn"></i>
                    <span class="verified-tooltip">Verified by <b><?php echo $site_name ?></b></span>
                <?php
                }
                $username_edit_locked = true;
                if ($page_user_data['id'] == USER_ID  ||  isset($_SESSION['admin_id'])) {
                ?>
                    <input type="file" name="" class="d-none" id="profile_input">
                    <label for="profile_input">
                        <i class="fa fa-camera edit-profile-btn"></i>
                    </label>
                <?php
                }
                ?>
                <div id="u-name">
                    <span class="u-name-extra-styles">
                        <?php echo $page_user_data['name'] ?>
                    </span>
                    <?php
                    if ($page_user_data['id'] == USER_ID  ||  isset($_SESSION['admin_id'])) {
                    ?>
                        <i class="fa fa-pencil edit-username click-effect" style="font-size: 1rem; margin-left: 1rem; background: rgba(0,0,0,0.2); padding: 0.5rem; border-radius: 0.3rem;"></i>

                        <?php
                        if (($curr_date <= date("Y-m-d H:i:s", strtotime($user_data['name_update'] . "+60 days")))  &&  !isset($_SESSION['admin_id'])) {
                        ?>
                            <i class="fa fa-lock" style="position: relative; font-size: 0.8rem; left: -1rem; top: -1rem;"></i>
                        <?php
                        } else {
                            $username_edit_locked = false;
                        }
                    } else {
                        if ($db->are_friends($page_user_data['id'], USER_ID)) {
                        ?>
                            <button class="btn btn-xs click-effect text-white" data-id="<?php echo DFENC($page_user_data['id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600; background: #21c830;"><i class="click-effect fa fa-user-friends"></i></button>
                        <?php
                        } else {
                        ?>
                            <button class="btn btn-xs click-effect text-white send-friend-request" data-id="<?php echo DFENC($page_user_data['id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600; background: #21c830;"><i class="click-effect fas fa-user-plus"></i></button>
                    <?php
                        }
                    }
                    ?>
                </div>
                <?php
                if ($page_user_data['id'] == USER_ID  ||  isset($_SESSION['admin_id'])) {
                ?>
                    <input type="file" name="" id="cover_photo_input" class="d-none">
                    <label for="cover_photo_input">
                        <div class="edit-cover-photo">Edit Cover Photo &nbsp;<i class="fa fa-camera" style="font-size: 1.3rem;"></i></div>
                    </label>
                <?php
                }
                ?>
            </div>
            <div id="black-grd"></div>
        </div>
        <div id="main-content" style="overflow: clip;">
            <div class="tb" style="overflow: clip;">
                <div class="td" id="l-col" style="overflow: clip;">
                    <div class="left-card" style="position: sticky; top: 6rem; max-height: 90vh; padding-bottom: 5rem;">
                        <?php
                        if (trim($page_user_data['intro']) != '' || trim($page_user_data['intro']) != 'bio' ||  trim($page_user_data['intro']) != 'location')
                        ?>
                        <div class="l-cnt">
                            <div class="cnt-label">
                                <i class="l-i material-icons green-text">public</i>
                                <span>Intro</span>
                                <div class="lb-action">
                                    <?php
                                if ($page_user_data['id'] == USER_ID  ||  isset($_SESSION['admin_id'])) {
                                    ?>
                                        <i class="fa fa-pencil edit-intro"></i>
                                    <?php
                                }
                                    ?>
                                </div>
                            </div>
                            <div id="i-box">
                                <?php
                                if (trim($page_user_data['intro']) != '') {
                                ?>
                                    <div id="intro-line">
                                        <?php echo $page_user_data['intro']; ?>
                                    </div>
                                <?php
                                }
                                if (trim($page_user_data['bio']) != '') {
                                ?>
                                    <div id="u-occ">
                                        <?php echo $page_user_data['bio']; ?>
                                    </div>
                                <?php
                                }
                                if (trim($page_user_data['location']) != '') {
                                ?>
                                    <div class="green-text my-auto click-effect">
                                        <i class="material-icons" style="font-size: 1.1rem;">location_on</i>
                                        <span style="position: relative; top: -0.2rem;">
                                            <?php echo $page_user_data['location']; ?>
                                        </span>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>

                        <?php
                        $photos = $db->select("posts", "*", "user_id = " . $page_user_data['id'] . " AND (type = 'PROFILE' OR type = 'COVER') AND status = 1");
                        if (count($photos) > 0) {
                        ?>
                            <div class="l-cnt l-mrg">
                                <div class="cnt-label">
                                    <i class="l-i" id="l-i-p"></i>
                                    <span>Photos</span>
                                    <div class="lb-action accordion-button" data-bs-toggle="collapse" data-bs-target="#photos" id="b-i"><i class="material-icons">keyboard_arrow_down</i></div>
                                </div>
                                <div id="photos" class="photos accordion-collapse collapse show">
                                    <div class="tb">
                                        <?php
                                        $i = 0;
                                        foreach ($photos as $photo) {
                                            echo ($i % 3 == 0) ? '<div class="tr">' : "";
                                        ?>

                                            <div class="td" style="background-image: url('<?php echo $photo['image'] ?>');"></div>
                                        <?php
                                            echo ($i % 3 == 2) ? '</div>' : "";
                                            $i++;
                                        }
                                        echo ((count($photos) - 1) % 3 != 2) ? '</div>' : "";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <!-- <div class="l-cnt l-mrg">
                            <div class="cnt-label">
                                <i class="l-i" id="l-i-k"></i>
                                <span>Did You Know<i id="k-nm">1</i></span>
                            </div>
                            <div>
                                <div class="q-ad-c">
                                    <a href="#" class="q-ad">
                                        <img src="https://imagizer.imageshack.com/img923/1849/4TnLy1.png">
                                        <span>My favorite superhero is...</span>
                                    </a>
                                </div>
                                <div class="q-ad-c">
                                    <a href="#" class="q-ad" id="add_q">
                                        <i class="material-icons">add</i>
                                        <span>Add Answer</span>
                                    </a>
                                </div>
                            </div>
                        </div> -->
                        <hr>
                        <div class="text-center text-muted">
                            Section End
                        </div>
                    </div>
                </div>
                <div class="td" id="m-col">
                    <?php
                    if ($page_user_data['id'] == USER_ID  ||  isset($_SESSION['admin_id'])) {
                    ?>
                        <div class="m-mrg" id="composer">
                            <div id="c-tabs-cvr">
                                <div class="tb" id="c-tabs">
                                    <div class="td click-effect active"><i class="material-icons">subject</i><span>Make Post</span></div>
                                </div>
                            </div>
                            <div id="c-c-main">
                                <div class="tb">
                                    <div class="td" id="p-c-i"><img src="<?php echo $page_user_data['profile_pic'] ?>" alt="Profile pic" style="width: 3rem; height: 3rem; object-fit: cover;"></div>
                                    <div class="td" id="c-inp">
                                        <input type="text" id="create_new_post" placeholder="Type something here...">
                                    </div>
                                    <?php
                                    $keyboard_id = "post_keyboard";
                                    $calling_btn = "#insert_emoji";
                                    ?>
                                    <div style="display: flex; align-items: center; justify-content: space-evenly;">
                                        <i data-bs-toggle="collapse" data-bs-target="#<?php echo $keyboard_id ?>" id="insert_emoji" class="material-icons click-effect">insert_emoticon</i>
                                        <i class="fa share-post fa-paper-plane click-effect green-text" style="font-size: 1.2rem;"></i>
                                    </div>
                                </div>


                                <?php
                                $insert_emoji_target = "#create_new_post";
                                require "emoji_keyboard.php";
                                ?>

                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="m-mrg" id="p-tabs">
                        <div class="tb" style="margin-top: -1rem; padding-top: 0.6rem;">
                            <div class="td">
                                <div class="tb" id="p-tabs-m">
                                    <div data-target="#posts-wrapper" class="td activity-nav-bar-user-profile click-effect active"><i class="material-icons">av_timer</i><span>TIMELINE</span></div>
                                    <div data-target="#friends-wrapper" class="td activity-nav-bar-user-profile click-effect" id="friend-list-opener"><i class="material-icons">people</i><span>FRIENDS</span></div>
                                    <div data-target="#photos-wrapper" class="td activity-nav-bar-user-profile click-effect"><i class="material-icons">photo</i><span>PHOTOS</span></div>
                                    <!-- <div data-target="#posts-wrapper" class="td activity-nav-bar-user-profile click-effect"><i class="material-icons">explore</i><span>ABOUT</span></div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div id="posts-wrapper" class="post active-activity-element activity-nav-bar-user-profile-element">
                            <!-- POST CONTENT -->
                            <?php

                            $_SESSION['PRINTED_POSTS'] = array();

                            $posts = $db->select(
                                "posts ps",
                                "ps.*,
                                    usr.name, usr.profile_pic",
                                "(ps.type = 'POST' OR ps.type = 'PROFILE') AND ps.status = 1 AND ps.user_id = '" . $page_user_data['id'] . "'
                            ORDER BY ps.id DESC
                            LIMIT 30
                        ",
                                "INNER JOIN register usr ON usr.id = ps.user_id "
                            );

                            $i = 0;
                            if (count($posts) == 0) {
                                echo "<h4 class='text-muted text-center mt-5'>No post shared yet !!!</h4>";
                            ?>

                            <?php
                            }
                            foreach ($posts as $post) {
                                $_SESSION['PRINTED_POSTS'][$post['id']] = $post['id'];

                                $recently_liked_by = $db->select(
                                    "post_stats",
                                    "COUNT(*) AS 
                                total_likes",

                                    "post_id = '" . $post['id'] . "' AND type = 'LIKE' ORDER BY id "
                                );

                                $liked_by = $db->select("post_stats ps", "ps.*, rg.name", "ps.post_id = " . $post['id'] . " AND ps.type = 'COMMENT' AND 
                            (
                                ps.user_id IN 
                                    (SELECT sent_by FROM friend_list WHERE sent_to = '" . USER_ID . "' AND status = 1) 
                                OR 
                                ps.user_id IN 
                                    (SELECT sent_to FROM friend_list WHERE sent_by = '" . USER_ID . "' AND status = 1)
                            ) LIMIT 1
                            ", "INNER JOIN register rg ON rg.id = ps.user_id ");

                                if (isset($liked_by[0]['id'])) {
                                    $recently_liked_by[0]['recent_name'] = $liked_by[0]['name'];
                                } else {
                                    $recently_liked_by[0]['recent_name'] = "";
                                }


                                $total_comments = $db->select("post_stats", "COUNT(*) AS total_comments", "type = 'COMMENT' AND post_id = " . $post['id']);

                                $comments = $db->select(
                                    "post_stats ps",
                                    "ps.*, rg.name, rg.profile_pic",
                                    "ps.post_id = '" . $post['id'] . "' AND ps.type = 'COMMENT' AND (ps.user_id IN 
                                                                    (SELECT sent_by FROM friend_list WHERE sent_to = '" . USER_ID . "' AND status = 1) 
                                                                    OR 
                                                                    user_id IN 
                                                                    (SELECT sent_to FROM friend_list WHERE sent_by = '" . USER_ID . "' AND status = 1)
                                                                    OR
                                                                    user_id = " . USER_ID . " ) ",
                                    "INNER JOIN register rg ON rg.id = ps.user_id"
                                );
                            ?>
                                <div class="row justify-content-center chat" style="border: 1px solid #e8e8e8; margin: 0.1rem; border-radius: 0.5rem; margin-bottom: 1rem; margin-left: 0.1rem; width: 50.7rem; padding: 1rem;">
                                    <div class="col-lg-12 border-radius-02" style="padding: 0.3rem; background-color: white;">
                                        <div class="col-sm-2" style="padding: 0; margin: 0; padding-left: 0.6rem; padding-top: 0.6rem;">
                                            <a href="profile?u=<?php echo DFENC($post['user_id']) ?>" style="height: 100% !important; display: inline-block; min-width: 15rem;">
                                                <figure class="userIcon" style="left: 0.6rem; position: absolute;">
                                                    <img src="<?php echo $post['profile_pic'] ?>" style="height: 3rem; width: 3rem;">
                                                </figure>
                                                <i class="status-mark online" style="bottom: 0rem; right: 4.5rem;"></i>
                                                <span class="" style="font-weight: 700; color: #636363; left: 4rem; position: relative; width: 37rem; display: inline-block; text-align: left;">
                                                    <?php echo $post['name'] ?>
                                                </span>
                                                <h6 style="font-size: 0.6rem; color: grey; left: 4rem; position: relative; text-align: left;">
                                                    <?php echo date("d F Y | H:i", strtotime($post['created_date'])) ?>
                                                </h6>
                                            </a>
                                        </div>
                                        <hr color="green" style="margin-bottom: 0rem; margin-top: 0.5rem; opacity: 0.1;">
                                        <?php
                                        if ($post['image'] != '') {
                                        ?>
                                            <h6 class="post-caption">
                                                <?php
                                                if ($post['type'] == 'PROFILE') {
                                                ?>
                                                    <span class="" style="color:#21c830;">
                                                        #New_Profile
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                                <?php echo strShort($post['description'], 600) ?>
                                            </h6>
                                        <?php
                                        }
                                        ?>

                                        <div class="blog-box">
                                            <div class="blog-img-box">
                                                <div style="display: grid; align-items: center; justify-content: center; border-bottom: 1px solid #eaeaea;">
                                                    <i class="far fa-thumbs-up pr-2 green-text like-btn-popup" style="display: inline-flex; position: absolute; z-index: -1; align-self: center; justify-self: center; font-size: 4rem;"></i>
                                                    <?php
                                                    if ($post['image'] != '') {
                                                    ?>
                                                        <img src="<?php echo $post['image'] ?>" alt="" class="img-fluid blog-img double-tap-like" style="box-shadow: none; max-height: 35rem; margin-bottom: 1rem;">
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <h6 class="post-caption double-tap-like" style="font-size: 1.1rem; padding-bottom: 3rem;">
                                                            <?php echo strShort($post['description'], 600) ?>
                                                        </h6>
                                                    <?php
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-sm-12" style="text-align: left; padding-top: 1rem;">
                                                    <div class="row" style="padding: 0; margin: 0;">
                                                        <div class="col-sm-6" style="padding: 0;">
                                                            <i class="fas fa-thumbs-up text-success"></i>
                                                            <span style="font-weight: 600; color: grey; font-size: 0.7rem; padding-left: 0.3rem;">
                                                                Liked By <?php echo ($recently_liked_by[0]['recent_name'] != "") ? $recently_liked_by[0]['recent_name'] . " and <span id='lkc" . base64_encode(DFENC($post['id'])) . "'>" . $recently_liked_by[0]['total_likes'] . "</span> others" : "<span id='lkc" . base64_encode(DFENC($post['id'])) . "'>" . $recently_liked_by[0]['total_likes'] . "</span> people"; ?>
                                                            </span>
                                                        </div>
                                                        <div class="col-sm-6" style="padding: 0; text-align: right; padding-right: 1rem;">
                                                            <span style="font-weight: 600; color: grey; font-size: 0.7rem; padding-left: 0.3rem;">
                                                                <?php echo $total_comments[0]['total_comments'] ?> Comments
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <?php
                                                    $already_liked = false;
                                                    $like_check = $db->select("post_stats", "*", "type = 'LIKE' AND post_id = '" . $post['id'] . "' AND user_id = " . USER_ID);
                                                    if (isset($like_check[0]['id'])) {
                                                        $already_liked = true;
                                                    }
                                                    ?>
                                                    <div class="row text-center post-action-row" style="border-bottom: 1px solid #dddddd; border-top: 1px solid #dddddd; margin: 1rem 0; margin-bottom: 0.5rem;">
                                                        <div class="col-sm-6 post-engagement-btn post-like-btn click-effect <?php echo ($already_liked) ? "green-text" : ""; ?>" data-qr="<?php echo DFENC($post['id']); ?>" data-up-c="#<?php echo "lkc" . base64_encode(DFENC($post['id'])) ?>" data-liked="<?php echo $already_liked ? "1" : "0"; ?>" style="border-right: 1px solid silver;">
                                                            <i class="<?php echo $already_liked ? "fas show-like-animation" : "far"; ?> fa-thumbs-up pr-2"></i>
                                                            <span class="like-text"><?php echo $already_liked ? "Liked" : "Like"; ?></span>
                                                        </div>
                                                        <div class="col-sm-6 post-engagement-btn accordion-button click-effect" data-bs-toggle="collapse" data-bs-target="#B<?php echo base64_encode(DFENC($post['id'])) ?>" data-focus-on="#C<?php echo base64_encode(DFENC($post['id'])) ?>">
                                                            <i class="far fa-comment-alt pr-2"></i> Comment
                                                        </div>
                                                    </div>
                                                    <?php
                                                    if ($total_comments[0]['total_comments'] > 0) {
                                                    ?>
                                                        <div class="row comment-caption">
                                                            <a href="#">
                                                                <?php echo (count($comments) > 0) ? "View more comments" : "View comments" ?>
                                                            </a>
                                                        </div>
                                                    <?php
                                                    }




                                                    ?>
                                                    <div class="row top-comments border-radius-02">
                                                        <?php
                                                        if (count($comments) > 0) {
                                                        ?>
                                                            <?php
                                                            foreach ($comments as $cmt) {
                                                            ?>
                                                                <div class="col-sm-12 top-comment-wrapper">
                                                                    <a target="" href="profile?u=<?php echo DFENC($cmt['user_id']) ?>" class="d-flex">
                                                                        <figure class="userIcon">
                                                                            <img src="<?php echo $cmt['profile_pic'] ?>" class="border-radius-50">
                                                                        </figure>
                                                                        <span class="" style="font-weight: 700; color: #14aa28; font-size: 0.7rem; padding-left: 0.4rem;">
                                                                            <?php echo $cmt['name'] ?>
                                                                            <h6 style="font-size: 0.6rem; color: grey;"><?php echo agoTime($cmt['created_date']) ?></h6>
                                                                        </span>
                                                                    </a>
                                                                    <div class="top-comment-text">
                                                                        <?php echo strShort($cmt['comment'], 100); ?>
                                                                    </div>
                                                                </div>
                                                            <?php
                                                            }
                                                            ?>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                    <div class="row pb-3 accordion-collapse collapse" id="B<?php echo base64_encode(DFENC($post['id'])) ?>">
                                                        <div class="col-sm-1">
                                                            <figure class="userIcon" style="left: 0; position: absolute;">
                                                                <img src="<?php echo $user_data['profile_pic'] ?>" style="height: 3rem; width: 3rem; border-radius: 50%;">
                                                            </figure>
                                                        </div>
                                                        <div class="col-sm-9 comment-box-wrapper" style="padding-right: 0; padding-left: 0;">
                                                            <input type="text" name="" id="C<?php echo base64_encode(DFENC($post['id'])) ?>" data-qr="<?php echo DFENC($post['id']) ?>" placeholder="Enter Comment" class="form-control comment-box" style="border-radius: 0.5rem; font-size: 0.7rem;">
                                                        </div>
                                                        <div class="col-sm-1 my-auto click-effect" style="font-size: 1.5rem;">
                                                            <?php
                                                            $keyboard_id = "pk" . base64_encode(DFENC($post['id']));
                                                            $calling_btn = "#ie" . base64_encode(DFENC($post['id']));
                                                            ?>
                                                            <i class="material-icons" id="<?php echo "ie" . base64_encode(DFENC($post['id'])); ?>" data-bs-toggle="collapse" data-bs-target="#<?php echo $keyboard_id ?>">insert_emoticon</i>
                                                        </div>
                                                        <div class="col-sm-1 my-auto click-effect post-comment-btn" style="font-size: 1.5rem;">
                                                            <i class="far fa-paper-plane text-success"></i>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <?php
                                                            $insert_emoji_target = "#C" . base64_encode(DFENC($post['id']));
                                                            require "emoji_keyboard.php";
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <div id="friends-wrapper" class="friends post activity-nav-bar-user-profile-element">
                            <div class="row">
                                <?php

                                $fl_allowed = false;
                                if ($page_user_data['id'] == USER_ID  ||  isset($_SESSION['admin_id'])  ||  $page_user_data['friend_list'] == 1) {
                                    $fl_allowed = true;
                                } else if ($page_user_data['friend_list'] ==  2) {
                                    $fl = $db->select("friend_list", "*", "((sent_by = " . USER_ID . " AND sent_to = '" . $page_user_data['id'] . "') OR (sent_to = " . USER_ID . " AND sent_by = '" . $page_user_data['id'] . "')) AND status = 1");
                                    if (isset($fl[0]['id'])) {
                                        $fl_allowed = true;
                                    }
                                }

                                if ($fl_allowed) {

                                    $friends = $db->select("register", "*", "id <> '" . $page_user_data['id'] . "' AND status = 1 AND (id IN (SELECT sent_to FROM friend_list WHERE status = 1 AND sent_by = '" . $page_user_data['id'] . "' ) OR (id IN (SELECT sent_by FROM friend_list WHERE status = 1 AND sent_to = '" . $page_user_data['id'] . "' ))) ORDER BY name ");
                                    foreach ($friends as $frd) {

                                        if ($frd['profile_pic'] == '') {
                                            $frd['profile_pic'] = PROFILE_PLACEHOLDER;
                                        }

                                ?>
                                        <div class="col-lg-4 mb-3">
                                            <div class="card client-card">
                                                <div class="card-body text-center client-card-body">
                                                    <a href="profile?u=<?php echo DFENC($frd['id']) ?>">
                                                        <img src="<?php echo $frd['profile_pic'] ?>" alt="user" class="rounded-circle thumb-xl zoom-on-hover friend-profile-in-card">
                                                        <h5 class="client-name fw-bold mt-3"><?php echo $frd['name'] ?></h5>
                                                    </a>
                                                    <p class="text-muted text-center mb-2 fw-semibold"><?php echo strShort($frd['bio'], 50) ?></p>
                                                    <?php
                                                    if ($frd['location'] != '') {
                                                    ?>
                                                        <span class="text-muted fw-semibold me-3"><i class="la la-map-marker me-2 text-secondary"></i><?php echo $frd['location'] ?></span>
                                                    <?php
                                                    }
                                                    ?>

                                                    <div class="mt-2 friend-profile-actions">
                                                        <?php
                                                        if ($db->are_friends($page_user_data['id'], USER_ID)) {
                                                        ?>
                                                            <i class="fas click-effect fa-user-friends"></i>
                                                            <i class="far click-effect fa-comments text-info"></i>
                                                            <i class="fas click-effect fa-ban text-danger"></i>
                                                            <i class="fas click-effect fa-user-alt-slash text-dark"></i>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <i class="fas click-effect fa-user-plus send-friend-request "></i>
                                                            <i class="fas click-effect fa-ban text-danger"></i>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <div class="col-sm-12 text-center">
                                        <h4 class="text-center text-muted">Friend List is private !</h4>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>

                        </div>
                        <div id="photos-wrapper" class="post activity-nav-bar-user-profile-element">
                            <div class="l-cnt l-mrg" style="margin-top: 0; padding-top: 0;">
                                <div class="photos">
                                    <div class="tb">
                                        <?php

                                        $photos = $db->select("posts", "*, (SELECT COUNT(*) FROM post_stats WHERE post_id = posts.id AND type = 'LIKE') AS total_likes, (SELECT COUNT(*) FROM post_stats WHERE post_id = posts.id AND type = 'COMMENT') AS total_comments, (SELECT COUNT(*) FROM view_stats WHERE item_id = posts.id) AS total_views", "user_id = " . $page_user_data['id'] . " AND status = 1 AND image <> '' ORDER BY id DESC LIMIT 15");

                                        $i = 0;
                                        foreach ($photos as $photo) {
                                            echo (($i % 3) == 0) ? '<div class="tr feed-photo-wrapper">' : "";
                                        ?>
                                            <div class="td feed-photo">
                                                <div class="feed-photo-img">
                                                    <img src="<?php echo $photo['image'] ?>" alt="Feed Photo">
                                                </div>
                                                <div class="feed-photo-stats">
                                                    <?php
                                                    if ($photo['type'] == 'POST'  ||  $photo['type'] == 'PROFILE') {
                                                    ?>
                                                        <div class="like-btn-wrapper">
                                                            <i class="fas fa-thumbs-up"></i> <?php echo $photo['total_likes'] ?>
                                                        </div>
                                                        <div class="comment-btn-wrapper">
                                                            <i class="far fa-comment-alt"></i> <?php echo $photo['total_comments'] ?>
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>

                                                    <div class="eye-btn-wrapper">
                                                        <i class="fas fa-eye"></i> <?php echo $photo['total_views'] ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php

                                            echo (($i % 3) == 2) ? '</div>' : "";
                                            $i++;
                                        }

                                        echo (($i % 3) != 0) ? '</div>' : "";

                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="loading"><i class="material-icons">autorenew</i></div>
                </div>

            </div>
        </div>
    </main>

    <div class="d-none" id="comment-card-ui">
        <div class="col-sm-12 top-comment-wrapper">
            <a target="_blank" href="profile?u=<?php echo DFENC($user_data['id']) ?>" class="d-flex">
                <figure class="userIcon">
                    <img src="<?php echo $user_data['profile_pic'] ?>" class="border-radius-50">
                </figure>
                <span class="" style="font-weight: 700; color: #14aa28; font-size: 0.7rem; padding-left: 0.4rem;">
                    <?php echo $user_data['name'] ?>
                    <h6 style="font-size: 0.6rem; color: grey;">1 second</h6>
                </span>
            </a>
            <div class="top-comment-text">
                //COMMENT_TEXT//
            </div>
        </div>
    </div>

    <div id="profile-upload-progress" class="upload-progress-preview d-none">
        <div class="progress-text">
            Uploading Profile Please wait...
        </div>
    </div>

    <?php include 'footer.php' ?>
    <script src="plugins/jquery/jquery.min.js"></script>

    <?php
    if ($current_command > 0) {
    ?>
        <script>
            window.history.pushState("", "", "<?php echo explode(".", $_SERVER['PHP_SELF'])[0] ?>");
            $(document).ready(function() {
                let cmd = <?php echo $current_command ?>;
                switch (cmd) {
                    case 1:
                        let target = $("#p-tabs").offset().top - 120;
                        setTimeout(() => {
                            $("html, body").animate({
                                scrollTop: target,
                            }, 1000);
                            setTimeout(() => {
                                $("#friend-list-opener").trigger("click");
                                $("#request-loader").fadeOut();
                            }, 1000)
                        }, 2000);
                        break;
                    default:
                        break;
                }
            })
        </script>
    <?php
    }
    ?>


    <script src="plugins/bootstrap/js/popper.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <script src="js/theme.js"></script>
    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <script src="./assets/plugins/sweet-alerts/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/vkg001/library/0.0.3/shortcuts.js"></script>
    <script src="./assets/js/post-card-js.js"></script>
    <script src="./assets/js/new-friend.js"></script>
    <script>
        $(".verified-profile-btn").on("mouseenter", function() {
            $(".verified-tooltip").fadeIn();
        });

        $(".verified-profile-btn").on("mouseleave", function() {
            $(".verified-tooltip").fadeOut();
        });


        $(document).on("mouseenter", ".feed-photo", function() {
            $(this).children(".feed-photo-stats").css({
                "bottom": "4rem",
                "opacity": "1",
            });
        });

        $(document).on("mouseleave", ".feed-photo", function() {
            $(this).children(".feed-photo-stats").css({
                "bottom": "0rem",
                "opacity": "0",
            });
        });

        $(document).ready(function() {
            $(document).on("click", ".activity-nav-bar-user-profile", function() {
                if ($(this).hasClass("active")) {
                    return;
                }
                $(".activity-nav-bar-user-profile-element").hide();

                let th = $(this);
                let prev_active = $($(".activity-nav-bar-user-profile.active").data("target"));
                prev_active.addClass("swipe-out-animation").css({
                    "display": "block",
                    "position": "absolute",
                    "z-index": "2",
                });

                $(".activity-nav-bar-user-profile").removeClass("active");
                th.addClass("active");

                let target = th.data("target");
                $(target).show().addClass("swipe-in-animation");
                setTimeout(() => {
                    prev_active.removeClass("active-activity-element swipe-out-animation").css({
                        "display": "none",
                        "position": "static",
                        "z-index": "0",
                    });
                    $(target).addClass("active-activity-element").removeClass("swipe-in-animation").show();
                }, 500);
            })
        });

        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>
    <script>
        function toggleOverlay(text) {
            $(".progress-text").html(text);
            if ($("#profile-upload-progress").hasClass("d-none")) {
                $("#profile-upload-progress").removeClass("d-none");
                $("body").addClass("disabled-scroll");
            } else {
                $("#profile-upload-progress").addClass("d-none");
                $("body").removeClass("disabled-scroll");
            }
        }


        $(document).ready(function() {

            $(window).on("scroll", function() {
                $("#profile-upload-progress").css("margin-top", window.scrollY + "px");
            });

            // UPDATE PROFILE
            $(document).on('change', "#profile_input", function(event) {
                let allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];
                let th = $(this);
                let curr_ext = $(this).val().split(".").pop().toLowerCase();
                if ($.inArray(curr_ext, allowed_ext) !== false) {
                    let form = new FormData();
                    form.append("new_profile", $("#profile_input")[0].files[0]);
                    form.append("update_profile", true);
                    $.ajax({
                        url: "profile_helper",
                        method: "POST",
                        processData: false,
                        contentType: false,
                        data: form,
                        beforeSend: function() {
                            toggleOverlay("Uploading Profile please wait...");
                        },
                        success: function(data) {
                            let res;
                            toggleOverlay("");
                            try {
                                res = $.parseJSON(data);
                            } catch (error) {
                                Swal.fire("Error", "Something went wrong.", "error");
                                return;
                            }
                            if (res.success) {
                                $("#user-profile").attr("src", res.link);
                                Swal.fire("Profile Changed", "Your profile has been updated", "success");
                            } else if (res.error) {
                                console.log(data);
                                Swal.fire("Oops!", res.error, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Something went wrong", "", "error");
                            console.log("Error part");
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        }
                    })
                } else {
                    Swal.fire("Error", "Invalid File Format", "error");
                }
            });


            // UPDATE COVER PHOTO
            $(document).on('change', "#cover_photo_input", function(event) {
                let allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];
                let th = $(this);
                let curr_ext = $(this).val().split(".").pop().toLowerCase();
                if ($.inArray(curr_ext, allowed_ext) !== false) {
                    let form = new FormData();
                    form.append("new_cover", $("#cover_photo_input")[0].files[0]);
                    form.append("update_cover", true);
                    $.ajax({
                        url: "profile_helper",
                        method: "POST",
                        processData: false,
                        contentType: false,
                        data: form,
                        beforeSend: function() {
                            toggleOverlay("Uploading Cover Photo please wait...");
                        },
                        success: function(data) {
                            let res;
                            toggleOverlay("");
                            try {
                                res = $.parseJSON(data);
                            } catch (error) {
                                Swal.fire("Error", "Something went wrong.", "error");
                                return;
                            }
                            if (res.success) {
                                $("#user-cover-photo").attr("src", res.link);
                                Swal.fire("Cover Photo Changed", "Your Cover photo has been updated", "success");
                            } else if (res.error) {
                                console.log(data);
                                Swal.fire("Oops!", res.error, "error");
                            }
                        },
                        error: function() {
                            Swal.fire("Something went wrong", "", "error");
                            console.log("Error part");
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        }
                    })
                } else {
                    Swal.fire("Error", "Invalid File Format", "error");
                }
            });

            // share post
            $(document).on("click", ".share-post", function() {
                let text = $("#create_new_post").val();
                if (text.trim() == '') {
                    return;
                }

                $.ajax({
                    url: "profile_helper",
                    method: "POST",
                    data: {
                        create_post: text,
                    },
                    beforeSend: function() {
                        toggleOverlay("Uploading post please wait ...");
                    },
                    success: function(data) {
                        toggleOverlay("Uploading post please wait ...");
                        let res;
                        try {
                            res = $.parseJSON(data);
                        } catch (error) {
                            console.log(data);
                            Swal.fire("Somthing went wrong", "", "error");
                            return;
                        }

                        if (res.success) {
                            Swal.fire("Post Shared", "", "success");
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            Swal.fire("Error", res.error, "error");
                        }
                    },
                    error: function() {
                        toggleOverlay("Uploading post please wait ...");
                        Swal.fire("Error", "Something went wrong", "error");
                    }
                })
            });

            // UPDATE INTRO
            $(document).on("click", ".edit-intro", function() {
                let intro = '<?php echo $page_user_data['intro'] ?>';
                let bio = '<?php echo $page_user_data['bio'] ?>';
                let location = '<?php echo $page_user_data['location'] ?>';

                Swal.fire({
                    title: "Edit Intro",
                    html: '<div class="form-control" style="height: fit-content;">' +
                        '   <div class="row">' +
                        '       <div class="col-sm-12 mt-5">' +
                        '           <label class="text-muted" style="float: left;" for="user-intro">Intro</label>' +
                        '           <input class="form-control" id="user-intro" placeholder="Intro" value="' + intro + '" />' +
                        '       </div>' +
                        '       <div class="col-sm-12 mt-5">' +
                        '           <label class="text-muted" style="float: left;" for="user-bio">Bio</label>' +
                        '           <textarea class="form-control" id="user-bio" placeholder="Bio" rows="5">' + bio + '</textarea>' +
                        '       </div>' +
                        '       <div class="col-sm-12 mt-5">' +
                        '           <label class="text-muted" style="float: left;" for="user-location">Location</label>' +
                        '           <input class="form-control" id="user-location" placeholder="Location" value="' + location + '" />' +
                        '       </div>' +
                        '       <div class="col-sm-12 mt-3">' +
                        '           <button class="btn btn-custom text-white green-background update-intro-btn click-effect">Update Info</button>' +
                        '       </div>' +
                        '' +
                        '   </div>' +
                        ' </div>',
                    showConfirmButton: false,
                });
            });

            $(document).on("click", ".update-intro-btn", function() {
                let intro = $("#user-intro").val();
                let bio = $("#user-bio").val();
                let loc = $("#user-location").val();

                $.ajax({
                    url: "profile_helper",
                    method: "POST",
                    data: {
                        updateIntro: true,
                        intro,
                        bio,
                        loc,
                    },
                    beforeSend: function() {
                        toggleOverlay("Updating information please wait...");
                    },
                    success: function(data) {
                        toggleOverlay("Updating information please wait...");
                        let res;
                        try {
                            res = $.parseJSON(data);
                        } catch (error) {
                            console.log(data);
                            Swal.fire("Something went wrong", "", "error");
                            return;
                        }

                        if (res.success) {
                            Swal.fire("Information Updated", "", "success");
                            setTimeout(() => {
                                location.reload()
                            }, 1000);
                        } else {
                            Swal.fire("Error", res.error, "error");
                        }
                    },
                    error: function() {
                        toggleOverlay("Updating information please wait...");
                    }
                })
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            // EDIT USERNAME
            <?php
            if ($username_edit_locked) {
                $date_diff = round(abs(strtotime($curr_date) - strtotime(date("Y-m-d H:i:s", strtotime($user_data['name_update'] . "+60 days")))) / (60 * 60 * 24), 0);
                $response = "Cooldown period is not completed yet. You can change your username again after $date_diff days ";
            ?>
                $(document).on("click", ".edit-username", function() {
                    Swal.fire("Cooldown Period", '<?php echo $response; ?>', "error");
                });
            <?php
            } else {
            ?>
                $(document).on("click", ".edit-username", function() {
                    Swal.fire({
                        title: 'Change Username',
                        showConfirmButton: false,
                        html: '<h4 class="text-danger">You can change your username only once in 60 Days </h4>    <div class="p-1" style="height: fit-content; overflow-x: hidden;">   <div class="row"><div class="col-sm-12"><input type="text" class="form-control" id="new_username" placeholder="New Username" /></div></div>  <div class="row mt-2"><div class="col-sm-12"><input type="text" class="form-control" id="confirm_username" placeholder="Confirm Username" /></div></div>  <div class="row mt-2"><div class="col-sm-12"><button class="btn btn-xs btn-custom text-white click-effect" style="background: #22b783;" id="change_username_btn">Change Username</button></div></div>  </div>',
                    });
                });

                $(document).on("click", "#change_username_btn", function() {
                    let username = $("#new_username").val();
                    let cusername = $("#confirm_username").val();

                    if (username.trim() == '') {
                        Swal.fire("Username cann't be blank.", "", "error").then(() => {
                            $(".edit-username").trigger("click");
                        });
                        return;
                    }


                    if (username.trim().length > 25) {
                        Swal.fire("Username too long.", "", "error").then(() => {
                            $(".edit-username").trigger("click");
                        });
                        return;
                    }

                    if (username != cusername) {
                        Swal.fire("Username not matched.", "", "error").then(() => {
                            $(".edit-username").trigger("click");
                        });
                        return;
                    }

                    $.ajax({
                        url: "profile_helper",
                        method: "POST",
                        data: {
                            updateUsername: username,
                        },
                        beforeSend: function() {
                            toggleOverlay("Updating username");
                        },
                        success: function(data) {
                            toggleOverlay("Updating username");
                            let res;
                            try {
                                res = $.parseJSON(data);
                            } catch (e) {
                                console.log(data);
                                console.log(e);
                                Swal.fire("Something went wrong", "", "error");
                                return;
                            }

                            if (res.success) {
                                Swal.fire("Username Updated", "", "success");
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            } else {
                                Swal.fire("Error", res.error, "error");
                            }
                        }
                    })
                });
            <?php
            }
            ?>
        })
    </script>
</body>

</html>