<?php
require 'config/connection.php';

if (USER_ID <= 0) {
    header("Location: login");
}

define("STORY_CARD_WIDTH", "8");

$homeMade = 1;

$posted = -1;
if (isset($_GET['s'])) {

    $flag = DFDEC($_GET['s']);

    switch ($flag) {
        case 'posted':
            $posted = 1;
            break;
        case 'invalid_file':
        case 'post_failure':
            $posted = 2;
            break;
    }
}

$showFirstLoginMsg = 0;
if (isset($_GET['social'])  &&  $_GET['social'] == 'registered') {
    $showFirstLoginMsg = 1;
}


$db_conn = new DB;
$user_info = $db_conn->select("register", "*", " id = " . USER_ID . " ", "")[0];
$total_likes = $db_conn->select("post_stats", "COUNT(*) AS total_likes", "post_id IN (SELECT id FROM posts WHERE user_id = '" . USER_ID . "' ) AND type = 'LIKE' ")[0]['total_likes'];
$total_comments = $db_conn->select("post_stats", "COUNT(*) AS total_comments", "post_id IN (SELECT id FROM posts WHERE user_id = '" . USER_ID . "' ) AND type = 'COMMENT' ")[0]['total_comments'];
$total_friends = $db_conn->select("friend_list", "COUNT(*) AS total_friends", " (sent_by = " . USER_ID . " OR sent_to = " . USER_ID . ") AND status = 1 ")[0]['total_friends'];
$total_interactions = $db_conn->select("post_stats", "COUNT(*) as total", "post_id IN (SELECT id FROM posts WHERE user_id = '" . USER_ID . "' )")[0]['total'];
$total_interactions += $db_conn->select("view_stats", "COUNT(*) AS total", "item_id IN (SELECT id FROM posts WHERE user_id = '" . USER_ID . "' ) ")[0]['total'];

$people_you_may_know = $db_conn->select("register rg", "rg.*", "rg.id <> " . USER_ID . " 
                                        AND rg.status = 1 
                                        AND rg.id NOT IN 
                                            (SELECT sent_to FROM friend_list WHERE sent_by = " . USER_ID . " ) 
                                        AND rg.id NOT IN 
                                            (SELECT sent_by FROM friend_list WHERE sent_to = " . USER_ID . " ) 
                                        ");


$friend_requests = $db_conn->select("friend_list fl", "fl.id AS request_id, rg.* ", " fl.sent_to = " . USER_ID . " AND fl.status = 0", "INNER JOIN register rg ON rg.id = fl.sent_by ");
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

    <link href="assets/app_styles/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/app_styles/app.min.css" rel="stylesheet" type="text/css" />

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
    <link href="./assets/icons/material-icons.css" rel="stylesheet">


    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="./assets/chat_styles/style.css">
    <link rel="stylesheet" href="./assets/plugins/sweet-alerts/sweetalert2.min.css">
    <style>
        .story-card-style {
            width: <?php echo STORY_CARD_WIDTH; ?>rem;
        }

        
    </style>
    <link rel="stylesheet" href="./assets/css/home-styles.css">
    <link rel="stylesheet" href="./assets/css/emoji-styles.css">
</head>


<body style="max-width: 100%; overflow-x:clip;background: #f4f4f4;">
    <div class="mb-5">
        <?php require 'header3.php' ?>
    </div>
    <section class="mt-5" style="margin-top: 6rem !important; padding: 0 1.2rem;">
        <div class="row text-center mb-5">
            <div class="col-sm-2 p-0" style="z-index: 3;">
                <div class="card client-card user-profile-card-top-left border-radius-06">
                    <div class="card-body text-center client-card-body border-radius-06">
                        <img src="<?php echo $user_info['profile_pic'] ?>" alt="user" class="rounded-circle thumb-xl zoom-on-hover friend-profile-in-card">
                        <h5 class="client-name fw-bold mt-3"><?php echo $user_info['name'] ?></h5>
                        <p class="text-muted text-center mb-2 fw-semibold"><?php echo $user_info['bio'] ?></p>
                        <div class="mt-2 user-profile-stats">
                            <span>
                                <i class="fas green-text click-effect fa-thumbs-up"></i> <?php echo $total_likes ?> Likes
                            </span>
                            <span>
                                <i class="far green-text click-effect fa-comments"></i> <?php echo $total_comments ?> Comments
                            </span>
                            <span>
                                <i class="fas green-text click-effect fa-user-friends"></i> <?php echo $total_friends ?> Friends
                            </span>
                            <span>
                                <i class="fas green-text click-effect fa-signal"></i> <?php echo $total_interactions ?> Interactions
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card client-card user-profile-card-top-left scroll-bar-width-02 border-radius-06" style="padding-bottom: 15rem;">
                    <div class="card-header" style="z-index: 4; margin-bottom: 0.4rem;">
                        Friend Requests
                    </div>
                    <?php
                    if (count($friend_requests) == 0) {
                    ?>
                        <h5 class="text-muted">No friend request received</h5>
                        <?php
                    } else {
                        foreach ($friend_requests as $item) {
                        ?>
                            <div class="card client-card user-profile-card-top-left border-radius-06">
                                <div class="card-body text-center client-card-body border-radius-06">
                                    <a href="profile?u=<?php echo DFENC($item['id']) ?>">
                                        <img src="<?php echo $item['profile_pic'] ?>" alt="user" class="rounded-circle thumb-xl zoom-on-hover friend-profile-in-card">
                                        <h5 class="client-name fw-bold mt-3"><?php echo $item['name'] ?></h5>
                                    </a>
                                    <p class="text-muted text-center mb-2 fw-semibold"><?php echo strShort($item['bio'], 40) ?></p>
                                    <div class="icons-wrapper">
                                        <button class="btn btn-xs btn-success text-white accept-friend_request" data-id="<?php echo DFENC($item['request_id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600; background: #21c830;"><i class="click-effect fas fa-user-plus"></i> Accept</button>
                                        <button class="btn btn-xs btn-danger text-white deny-friend-request" data-id="<?php echo DFENC($item['request_id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600;"><i class="click-effect fas fa-user-alt-slash"></i> Deny</button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <i class="click-effect ti-reload" style="font-weight: 900; margin-top: 5rem 0;"></i>
                </div>
                <div class="card client-card user-profile-card-top-left scroll-bar-width-02 border-radius-06" style="position: sticky; top: 6rem; max-height: 42rem; overflow: auto; min-height: 30rem; padding-bottom: 15rem;">
                    <div class="card-header" style="z-index: 4; margin-bottom: 0.4rem;">
                        People you may know
                    </div>
                    <?php
                    foreach ($people_you_may_know as $item) {
                        if ($item['profile_pic'] == '') {
                            $item['profile_pic'] = PROFILE_PLACEHOLDER;
                        }
                    ?>
                        <div class="card client-card user-profile-card-top-left border-radius-06">
                            <div class="card-body text-center client-card-body border-radius-06">
                                <a href="profile?u=<?php echo DFENC($item['id']) ?>">
                                    <img src="<?php echo $item['profile_pic'] ?>" alt="user" class="rounded-circle thumb-xl zoom-on-hover friend-profile-in-card">
                                </a>
                                <span class="remove-suggested-profile click-effect">
                                    <i class="fa fa-times remove-friend-suggestion" data-id="<?php echo DFENC($item['id']) ?>"></i>
                                </span>
                                <a href="profile?u=<?php echo DFENC($item['id']) ?>">
                                    <h5 class="client-name fw-bold mt-3"><?php echo $item['name'] ?></h5>
                                </a>
                                <p class="text-muted text-center mb-2 fw-semibold"><?php echo $item['bio'] ?></p>
                                <div class="icons-wrapper">
                                    <button class="btn btn-xs btn-success text-white send-friend-request" data-id="<?php echo DFENC($item['id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600; background: #21c830;"><i class="click-effect fas fa-user-plus"></i>Add Friend</button>
                                    <button class="btn btn-xs btn-danger text-white remove-friend-suggestion" data-id="<?php echo DFENC($item['id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600;"><i class="click-effect fas fa-user-alt-slash"></i> Remove</button>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                    <i class="click-effect ti-reload" style="font-weight: 900; margin-top: 5rem 0;"></i>
                </div>
            </div>

            <div class="col-sm-7" style="padding-right: 0; padding-left: 0;">
                <!-- post and story section start  -->
                <div id="posts-and-story-wrapper" class="container posts-and-story-wrapper" style="padding-left: 1.4rem; width: 47rem;">
                    <div class="row mb-3 d-xs-none" style="min-width: auto; max-height: 25rem;">
                        <div class="col-lg-12 chat">
                            <div class="card" style="box-shadow: none;">
                                <div class="card-header">
                                    <h4 class="text-white">
                                        Stories <i class="fas fa-book-open"></i>
                                    </h4>
                                </div>
                                <div class="card-body story-block-wrapper" style="padding-bottom: 0;">
                                    <div class="story-swipper swipper-left click-effect" data-target=".story-block-wrapper">
                                        <i class="fa fa-arrow-left"></i>
                                    </div>
                                    <div class="story-swipper swipper-right click-effect" data-target=".story-block-wrapper">
                                        <i class="fa fa-arrow-right"></i>
                                    </div>
                                    <div class="row story-row" style="min-width: fit-content;">


                                        <div class="custom-padding-story-card story-card-style click-effect" data-bs-toggle="modal" data-bs-target="#upload_story_modal">
                                            <div class="card story-card">
                                                <div class="story-wrapper">
                                                    <div class="story-blocker" style="background-color: #cfcfcf;"></div>
                                                    <div class="user-name-story add-story-text">
                                                        <i class='fa fa-plus-circle'></i>
                                                        <br>
                                                        Add Story
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="display: inherit;" id="story-section-wrapper">
                                            <?php

                                            $stories = $db->select("posts ps", "ps.*, rg.profile_pic, rg.is_verified, rg.name, (SELECT COUNT(*) FROM view_stats WHERE item_id = ps.id AND item_type = ps.type AND viewed_by <> '" . USER_ID . "') as total_views", "ps.user_id = " . USER_ID . " AND ps.type = 'STORY' AND ps.created_date >= '" . date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "-1 day")) . "' ORDER BY ps.id DESC ", " INNER JOIN register rg ON rg.id = ps.user_id ");
                                            foreach ($stories as $story) {
                                            ?>
                                                <div class="custom-padding-story-card story-card-style update-story-view story-shower click-effect" data-bs-toggle="modal" data-bs-target="#view_stories" data-activate-carousal="<?php echo DFENC($story['id']) ?>">
                                                    <div class="card story-card">
                                                        <div class="story-wrapper">
                                                            <div class="story-blocker"></div>
                                                            <div class="story-view-count">
                                                                <?php echo $story['total_views'] ?>
                                                            </div>
                                                            <div class="user-profile-story">
                                                                <img src="<?php echo $story['profile_pic'] ?>" alt="">
                                                            </div>
                                                            <img src="<?php echo $story['image'] ?>" alt="">
                                                            <div class="user-name-story">
                                                                Your Story
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }

                                            $other_stories = $db->select("posts ps", "ps.*, rg.profile_pic, rg.is_verified, rg.name", "type = 'STORY' AND ps.created_date >= '" . date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s") . "-1 day")) . "' AND ( ps.user_id IN (SELECT sent_by FROM friend_list WHERE status = 1 AND sent_to = " . USER_ID . ") OR ps.user_id IN (SELECT sent_to FROM friend_list WHERE status = 1 AND sent_by = " . USER_ID . ") ) ORDER BY ps.id DESC", "INNER JOIN register rg ON rg.id = ps.user_id");
                                            foreach ($other_stories as $story) {
                                                $temp_user_data = $db->select("register", "*", "id = " . $story['user_id']);
                                                $story['name'] = $temp_user_data[0]['name'];
                                            ?>
                                                <div class="custom-padding-story-card story-card-style update-story-view story-shower click-effect" data-bs-toggle="modal" data-bs-target="#view_stories" data-activate-carousal="<?php echo DFENC($story['id']) ?>">
                                                    <div class="card story-card">
                                                        <div class="story-wrapper">
                                                            <div class="story-blocker"></div>
                                                            <div class="user-profile-story">
                                                                <img src="<?php echo $story['profile_pic'] ?>" alt="">
                                                            </div>
                                                            <img src="<?php echo $story['image'] ?>" alt="">
                                                            <div class="user-name-story">
                                                                <?php echo $story['name'] ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                            }

                                            $stories = array_merge($stories, $other_stories);

                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- POST CONTENT -->
                    <?php




                    function getFriendSuggestions()
                    {
                        global $people_you_may_know;
                        $db = new DB();
                    ?>
                        <div class="row mb-3 d-xs-none" style="max-height: 25rem;">
                            <div class="col-lg-12 chat">
                                <div class="card" style="box-shadow: none;">
                                    <div class="card-body story-block-wrapper" style="padding-bottom: 0;">
                                        <div class="story-swipper swipper-left click-effect" data-target=".story-block-wrapper">
                                            <i class="fa fa-arrow-left"></i>
                                        </div>
                                        <div class="story-swipper swipper-right click-effect" data-target=".story-block-wrapper">
                                            <i class="fa fa-arrow-right"></i>
                                        </div>
                                        <div class="row story-row" style=" min-width: fit-content;padding-right: 10rem;">

                                            <div style="display: inherit;" id="story-section-wrapper">
                                                <?php
                                                $stories = $db->select(
                                                    "posts ps",

                                                    "ps.*, rg.profile_pic, rg.is_verified, rg.name, (SELECT COUNT(*) FROM view_stats WHERE item_id = ps.id AND item_type = ps.type AND viewed_by <> '" . USER_ID . "') as total_views",

                                                    "1=1",

                                                    " INNER JOIN register rg ON rg.id = ps.user_id "
                                                );

                                                foreach ($people_you_may_know as $item) {
                                                ?>
                                                    <div class="card client-card user-profile-card-top-left profile-card-feed-suggestion border-radius-06">
                                                        <div class="card-body text-center client-card-body border-radius-06">
                                                            <a href="profile?u=<?php echo DFENC($item['id']) ?>">
                                                                <img src="<?php echo $item['profile_pic'] ?>" alt="user" class="rounded-circle thumb-xl zoom-on-hover friend-profile-in-card">
                                                            </a>
                                                            <span class="remove-suggested-profile click-effect">
                                                                <i class="fa fa-times remove-friend-suggestion" data-id="<?php echo DFENC($item['id']) ?>"></i>
                                                            </span>
                                                            <a href="profile?u=<?php echo DFENC($item['id']) ?>">
                                                                <h5 class="client-name fw-bold mt-3"><?php echo $item['name'] ?></h5>
                                                            </a>
                                                            <p class="text-muted text-center mb-2 fw-semibold"><?php echo $item['bio'] ?></p>
                                                            <div class="icons-wrapper">
                                                                <button class="btn btn-xs btn-success text-white send-friend-request" data-id="<?php echo DFENC($item['id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600; background: #21c830;"><i class="click-effect fas fa-user-plus"></i>Add Friend</button>
                                                                <button class="btn btn-xs btn-danger text-white remove-friend-suggestion" data-id="<?php echo DFENC($item['id']) ?>" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600;"><i class="click-effect fas fa-user-alt-slash"></i> Remove</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                }

                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                    }



                    $_SESSION['PRINTED_POSTS'] = array();

                    $posts = $db->select(
                        "posts ps",
                        "ps.*, usr.is_verified, 
                            (
                                (SELECT COUNT(*) FROM view_stats WHERE item_type = 'POST' AND item_id = ps.id )
                                    - 
                                (SELECT COUNT(*) FROM post_stats WHERE post_id = ps.id)
                            ) AS interactions,
                            usr.name, usr.profile_pic",

                        "   (ps.type = 'POST'  OR ps.type = 'PROFILE' OR  ps.type = 'COVER')
                        AND 
                            ps.status = 1 
                        AND
                            (
                                    ps.user_id IN 
                                        (SELECT sent_by FROM friend_list WHERE sent_to = '" . USER_ID . "' AND status = 1) 
                                OR 
                                    ps.user_id IN 
                                        (SELECT sent_to FROM friend_list WHERE sent_by = '" . USER_ID . "' AND status = 1)
                                OR 
                                    (
                                            ps.user_id = '" . USER_ID . "' 
                                        AND
                                            (
                                                    (SELECT SUM(viewed_times) FROM view_stats WHERE item_type = 'POST' AND item_id = ps.id AND viewed_by = '" . USER_ID . "' ) = 0
                                                OR
                                                    (SELECT SUM(viewed_times) FROM view_stats WHERE item_type = 'POST' AND item_id = ps.id AND viewed_by = '" . USER_ID . "' ) = ''
                                                OR
                                                    (SELECT SUM(viewed_times) FROM view_stats WHERE item_type = 'POST' AND item_id = ps.id AND viewed_by = '" . USER_ID . "' ) IS NULL
                                            )
                                        AND
                                            ps.created_date >= '" . date("Y-m-d H:i:s", strtotime($curr_date . "-1 day")) . "'
                                    )
                            )
                        AND 
                            ps.id NOT IN (SELECT post_id FROM post_stats WHERE user_id = '" . USER_ID . "' )

                            ORDER BY ps.id DESC, interactions ASC
                            LIMIT 10
                        ",
                        "INNER JOIN register usr ON usr.id = ps.user_id "
                    );

                    $i = 0;
                    if (count($posts) == 0) {
                        echo "<h4 class='text-muted text-center mt-5'>Add new friends to see their posts</h4>";
                    ?>

                    <?php
                    }

                    foreach ($posts as $post) {
                        $_SESSION['PRINTED_POSTS'][] = $post['id'];

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
                                                                    (SELECT sent_to FROM friend_list WHERE sent_by = '" . USER_ID . "' AND status = 1) ) ",
                            "INNER JOIN register rg ON rg.id = ps.user_id"
                        );


                        $all_comments = $db->select(
                            "post_stats ps",
                            "ps.*, rg.name, rg.profile_pic",
                            "ps.post_id = '" . $post['id'] . "' AND ps.type = 'COMMENT' ORDER BY created_date DESC ",
                            "INNER JOIN register rg ON rg.id = ps.user_id "
                        );
                    ?>
                        <div data-seen="0" data-self="<?php echo DFENC($post['id']) ?>" class="row justify-content-center post-wrapper chat" style="border: 1px solid #e8e8e8; margin: 0.1rem; border-radius: 0.5rem; margin-bottom: 1rem; margin-left: -1rem; width: 46.5rem; padding: 1rem;">
                            <div class="col-lg-12 border-radius-02" style="padding: 0.3rem; background-color: white;">
                                <div class="col-sm-2" style="padding: 0; margin: 0; padding-left: 0.6rem; padding-top: 0.6rem;">
                                    <a href="profile?u=<?php echo DFENC($post['user_id']) ?>" style="height: 100% !important; display: inline-block; min-width: 15rem;">
                                        <figure class="userIcon" style="left: 0.6rem; position: absolute;">
                                            <img src="<?php echo $post['profile_pic'] ?>" style="height: 3rem; width: 3rem;">
                                        </figure>
                                        <i class="status-mark online" style="bottom: 0rem; right: 4rem;"></i>
                                        <span class="" style="font-weight: 700; color: #636363; left: 4rem; position: relative; width: 37rem; display: inline-block; text-align: left;"><?php echo $post['name'] ?></span>
                                        <h6 style="font-size: 0.6rem; color: grey; left: 4rem; position: relative; text-align: left;"><?php echo date("d F Y | H:i", strtotime($post['created_date'])) ?></h6>
                                    </a>
                                </div>
                                <hr color="green" style="margin-bottom: 0rem; margin-top: 0.5rem; opacity: 0.1;">

                                <?php
                                if ($post['image'] != '') {
                                ?>
                                    <h6 class="post-caption">
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
                                                <div class="col-sm-6 post-engagement-btn click-effect" data-bs-toggle="collapse" data-bs-target="#B<?php echo base64_encode(DFENC($post['id'])) ?>" data-focus-on="#C<?php echo base64_encode(DFENC($post['id'])) ?>">
                                                    <i class="far fa-comment-alt pr-2"></i> Comment
                                                </div>
                                            </div>
                                            <?php
                                            if ($total_comments[0]['total_comments'] > 0) {
                                            ?>
                                                <div class="row comment-caption">
                                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#comment-section-<?php echo base64_encode(DFENC($post['id'])) ?>">
                                                        <?php echo (count($comments) > 0) ? "View more comments" : "View comments" ?>
                                                    </a>
                                                </div>
                                            <?php
                                            }




                                            ?>
                                            <div class="row top-comments border-radius-02">

                                                <div id="comment-section-<?php echo base64_encode(DFENC($post['id'])) ?>" class="accordion-collapse collapse" aria-labelledby="headingOne">
                                                    <div class="accordion-body">
                                                        <?php
                                                        foreach ($all_comments as $cmt) {
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
                                                                    <?php echo strShort($cmt['comment'], 35); ?>
                                                                </div>
                                                            </div>
                                                        <?php
                                                        }
                                                        ?>
                                                    </div>
                                                </div>


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
                                                                <?php echo strShort($cmt['comment'], 35); ?>
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

                    getFriendSuggestions();
                    ?>
                </div>
                <div id="load-more-post-icon" style="font-weight: 900; margin: 5rem 0; font-size: 2rem; display: inline-block;">
                    <i class="click-effect ti-reload loadMorePosts"></i>
                </div>

                <div id="load-more-post-spinner" class="d-none" style="font-weight: 900; margin: 5rem 0; font-size: 2rem; display: inline-block;">
                    <div class="spinner-grow text-success"></div>
                </div>
                <!-- post section end  -->
            </div>
            <div class="col-sm-3 d-none d-lg-block" style="padding-left:0rem; z-index: 3;">
                <div class="card chat" style="margin: 0; padding: 0; min-height: 100%; width: 20.4rem;position: fixed;">
                    <div class="card-header" style="background: white; max-height: 3.9rem;">
                        <h4 style="text-align: left;" class="transition-04">
                            Inbox
                            <span style="display: inline-block; margin-top: 0.1rem; position: absolute; margin-left: 0.3rem;">
                                <i class="fa fa-comment"></i>
                            </span>
                        </h4>
                        <span style="display: inline-block; margin-top: -2.4rem; position: absolute; margin-left: 7.3rem; font-size: 1.3rem;">
                            <i class="fa fa-search search-friend click-effect"></i>
                        </span>
                        <input type="text" id="search-bar-chat" class="form-control transition-04 search-bar-init" placeholder="Search ..." style="position: relative; top: -2.3rem; border-radius: 0.5rem;">
                    </div>
                    <div class="card-body">
                        <div class="center friend-list-holder-wrapper" style="position: absolute; left: -2.34rem; top: -1.1rem; transform: scale(0.78); height: 45rem;">
                            <div class="friend-list-holder" id="chat-list" style="top: 0rem; justify-content: start; left: 3.4rem;padding-top: 1rem;width: 25rem;">
                                <?php

                                $_SESSION['PRINTED_MSG'] = array();
                                $_SESSION['MSG_SEEN_UPDATE'] = false;
                                $_SESSION['WAS_TYPING'] = false;
                                $_SESSION['CHAT_LIST_LAST_UPDATED'] = time();
                                $most_recent_msg_temp = $db->select("messages", "*", "sender = " . USER_ID . " OR receiver = " . USER_ID);
                                if (count($most_recent_msg_temp) > 0) {
                                    $_SESSION['MOST_RECENT_MSG'] = $most_recent_msg_temp[0]['id'];
                                } else {
                                    $_SESSION['MOST_RECENT_MSG'] = null;
                                }

                                $chat = $db->select(
                                    "messages",
                                    "*,
                                (sender+receiver) AS unique_id,
                                (SELECT name FROM register WHERE id = sender) AS sender_name,
                                (SELECT name FROM register WHERE id = receiver) AS receiver_name,
                                (SELECT profile_pic FROM register WHERE id = sender) AS sender_pic, 
                                (SELECT last_online FROM register WHERE id = receiver) AS receiver_seen,
                                (SELECT last_online FROM register WHERE id = sender) AS sender_seen, 
                                (SELECT profile_pic FROM register WHERE id = receiver) AS receiver_pic
                                ",
                                    " (sender = " . USER_ID . " OR receiver = " . USER_ID . ") ORDER BY created_date DESC "
                                );

                                $map = array();
                                foreach ($chat as $slide) {
                                    if (isset($map[$slide['receiver']][$slide['sender']])) {
                                        continue;
                                    }
                                    $map[$slide['receiver']][$slide['sender']] = $map[$slide['sender']][$slide['receiver']] = $slide['id'];

                                    $slide['id'] = $slide['unique_id'];


                                    $results = $db->select(
                                        "messages",
                                        "messages.*
                                        ",
                                        "((sender = '" . $slide['sender'] . "' AND receiver = '" . $slide['receiver'] . "')
                                            OR
                                        (sender = '" . $slide['receiver'] . "' AND receiver = '" . $slide['sender'] . "'))
                                        ORDER BY id DESC LIMIT 1"
                                    );


                                    $results = $results[0];

                                    $results['total_unread'] = $db->select("messages", "COUNT(*) as total_unread", "
                                    (
                                        (sender = '" . $slide['sender'] . "' AND receiver = '" . USER_ID . "')
                                            OR
                                        (sender = '" . $slide['receiver'] . "' AND receiver = '" . USER_ID . "')
                                    )
                                    AND (seen = 0 OR seen = 1)")[0]['total_unread'];

                                    if ($slide['sender'] == USER_ID) {
                                        $tile['profile_pic'] = $slide['receiver_pic'];
                                        $tile['name'] = $slide['receiver_name'];
                                        $tile['last_seen'] = $slide['receiver_seen'];
                                        $tile['for'] = $slide['receiver'];
                                    } else {
                                        $tile['profile_pic'] = $slide['sender_pic'];
                                        $tile['name'] = $slide['sender_name'];
                                        $tile['last_seen'] = $slide['sender_seen'];
                                        $tile['for'] = $slide['sender'];
                                    }

                                    $tile['seen'] = '';
                                    if ($results['sender'] == USER_ID) {
                                        switch ($results['seen']) {
                                            case 0:
                                                $tile['seen'] = '<i class="mdi mdi-check"></i> sent';
                                                break;
                                            case 1:
                                                $tile['seen'] = '<i class="mdi mdi-check green-text"></i> delivered';
                                                break;
                                            case 2:
                                                $tile['seen'] = '<i class="mdi mdi-check-all green-text"></i> seen';
                                                break;
                                        }

                                        $tile['seen'] = "<br>" . $tile['seen'];
                                    }

                                    if ($tile['profile_pic'] == '') {
                                        $tile['profile_pic'] = PROFILE_PLACEHOLDER;
                                    }

                                    $db->update("messages", "seen = 1", "seen < 1 AND sender = '" . $tile['for'] . "' AND receiver = " . USER_ID);

                                    $icon = "";
                                    if ($results['media_type'] == 1) {
                                        $icon = '<i class="mdi mdi-image-size-select-actual" style="margin-right: 0.5rem;"></i>';
                                    }
                                ?>
                                    <span id="avatar-<?php echo base64_encode(DFENC($slide['id'])) ?>">
                                        <div class="contact border-radius-06 bar chat-list-item click-effect toggleVisibility" data-refresher="#refresh-chat-spinner-<?php echo base64_encode(DFENC($tile['for'])) ?>" data-msg-wrapper="#messages-wrapper-<?php echo base64_encode(DFENC($tile['for'])) ?>" data-self="<?php echo (DFENC($tile['for'])) ?>" data-msg-container="chat-message-container-<?php echo base64_encode(DFENC($slide['id'])) ?>" data-msg-loader="chat-msg-loader-<?php echo base64_encode(DFENC($slide['id'])) ?>" data-onclick-hide="#chat-list" data-onclick-fadein="#chat-individual-id-<?php echo base64_encode(DFENC($slide['id'])) ?>" data-init="0" style="justify-content: left; align-items: flex-start; text-align: left; border-bottom: 1px dashed silver; padding-bottom: 5rem; margin-top: 0.1rem; padding-top: 0.2rem;">
                                            <div class="pic" style="background-image: url('<?php echo $tile['profile_pic'] ?>'); margin-top: 0.4rem; margin-left: -4rem;">
                                            </div>
                                            <?php
                                            if (($results['total_unread']) > 0) {
                                            ?>
                                                <div class="badge" style="font-family: cursive; background: #21c87a; padding-top: 0;display: flex; align-items: center; justify-content: center;margin-top: 0.4rem; margin-left: 0.4rem;"><?php echo $results['total_unread'] ?></div>
                                            <?php
                                            }
                                            ?>
                                            <div class="last-chat-date" data-date="<?php echo ($results['created_date']) ?>" style="position: relative;right: -14rem;margin-bottom: -2rem;">
                                                <?php echo agoTime($results['created_date'], true) ?>
                                            </div>
                                            <div data-parent="#avatar-<?php echo base64_encode(DFENC($slide['id'])) ?>" data-fullname="<?php echo ($tile['name']); ?>" class="name chat-avatar-names" style="margin-top: 0.4rem; margin-left: 0.4rem; font-weight:600;">
                                                <?php echo strShort($tile['name']); ?>
                                            </div>
                                            <div class="seen" style="margin-top: -0.4rem; margin-left: 0.4rem;">
                                                <?php echo $icon ?>
                                                <div style="display: inline-block; width: 12rem; height: 1.7rem; overflow: hidden; margin-top: -0.5rem; position: relative; top: 0.5rem;">
                                                    <?php echo (($results['message'])); ?>
                                                </div>
                                                <?php echo $tile['seen'] ?>
                                            </div>
                                        </div>
                                    </span>
                                <?php
                                }
                                ?>
                            </div>

                            <?php
                            $map = array();
                            foreach ($chat as $slide) {
                                if (isset($map[$slide['receiver']][$slide['sender']])) {
                                    continue;
                                }
                                $map[$slide['receiver']][$slide['sender']] = $map[$slide['sender']][$slide['receiver']] = $slide['id'];

                                $slide['id'] = $slide['unique_id'];

                                if ($slide['sender'] == USER_ID) {
                                    $tile['profile_pic'] = $slide['receiver_pic'];
                                    $tile['name'] = $slide['receiver_name'];
                                    $tile['last_seen'] = $slide['receiver_seen'];
                                    $tile['for'] = $slide['receiver'];
                                } else {
                                    $tile['profile_pic'] = $slide['sender_pic'];
                                    $tile['name'] = $slide['sender_name'];
                                    $tile['last_seen'] = $slide['sender_seen'];
                                    $tile['for'] = $slide['sender'];
                                }

                            ?>
                                <div class="chat friend-list-holder" id="chat-individual-id-<?php echo base64_encode(DFENC($slide['id'])) ?>" style="top: -5.1rem; left: 3rem; padding-bottom: 5.5rem; width: 26.2rem; display: none; height: 50rem !important;">
                                    <div class="contact bar">
                                        <div class="click-effect close-chat-btn toggleVisibility" data-onclick-hide="#chat-individual-id-<?php echo base64_encode(DFENC($slide['id'])) ?>" data-onclick-fadein="#chat-list" style="position: absolute; left: 0rem; font-size: 1.5rem;">
                                            <i class="fa fa-arrow-left"></i>
                                        </div>
                                        <div class="pic" style="background-image: url('<?php echo $tile['profile_pic'] ?>');">
                                        </div>
                                        <div class="name">
                                            <?php echo (($tile['name'])) ?>
                                        </div>
                                        <div class="seen" id="seen-of-<?php echo base64_encode(DFENC($tile['for'])) ?>">
                                            <?php echo (agoTime($tile['last_seen'], true, true)) ?>
                                        </div>
                                    </div>
                                    <div class="triple-dots-menu click-effect">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </div>
                                    <div class="triple-dots-menu-content d-none">
                                        <div class="menu-items text-danger click-effect block-user-btn" data-target="<?php echo DFENC($tile['for']) ?>">
                                            <?php
                                            if ($db->checkRelationship($tile['for']) != 3) {
                                            ?>
                                                <i class="mdi mdi-block-helper me-2"></i>Block
                                            <?php
                                            } else {
                                            ?>
                                                Unblock
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="msg-loader-wrapper" id="chat-msg-loader-<?php echo base64_encode(DFENC($slide['id'])) ?>">
                                        <div class="spinner-grow text-success message-loader" style="height: 7rem; width: 7rem;"></div>
                                    </div>
                                    <div class="messages" id="chat-message-container-<?php echo base64_encode(DFENC($slide['id'])) ?>" style="opacity: 0; border-top: 1px solid silver; height: 42rem;">
                                        <span id="messages-wrapper-<?php echo base64_encode(DFENC($tile['for'])) ?>">
                                        </span>

                                        <span id="typing-msg-<?php echo base64_encode(DFENC($tile['for'])) ?>" class="typing-effect-internal-chat" style="display: none;">
                                            <div class="message stark typing-msg-effect">
                                                <div class="typing typing-1"></div>
                                                <div class="typing typing-2"></div>
                                                <div class="typing typing-3"></div>
                                            </div>
                                        </span>

                                        <span id="refresh-chat-spinner-<?php echo base64_encode(DFENC($tile['for'])) ?>" style="display: none;">
                                            <div class="row" id="chat-spinner-<?php echo base64_encode(DFENC($tile['for'])) ?>">
                                                <div class="col-sm-12">
                                                    <div class="spinner-border text-info"></div>
                                                </div>
                                            </div>
                                        </span>

                                        <span id="refresh-chat-spinner-<?php echo base64_encode(DFENC($tile['for'])) ?>-msg" style="display: none;">
                                            <div class="row" id="chat-spinner-<?php echo base64_encode(DFENC($tile['for'])) ?>">
                                                <div class="col-sm-12 green-text">
                                                    <i class="far fa-check-circle me-2"></i> Chat Updated
                                                </div>
                                            </div>
                                        </span>
                                    </div>

                                    <div class="input" style="bottom: 2rem; position: absolute; transform: scale(1.1);max-width: 80%;">
                                        <i class="fas fa-camera green-text" data-bs-toggle="modal" data-bs-target="#input-modal-for-<?php echo base64_encode(DFENC($tile['for'])) ?>"></i>
                                        <?php
                                        $keyboard_id = "chat-key-" . base64_encode(DFENC($tile['for']));
                                        $calling_btn = "#chat-emo-btn-" . base64_encode(DFENC($tile['for']));
                                        ?>
                                        <i class="material-icons green-text" id="#chat-emo-btn-<?php echo base64_encode(DFENC($tile['for'])); ?>" data-bs-toggle="collapse" data-bs-target="#chat-key-<?php echo base64_encode(DFENC($tile['for'])) ?>">insert_emoticon</i>
                                        <input placeholder="Type your message here!" id="inp-for-<?php echo base64_encode(DFENC($tile['for'])) ?>" data-for="<?php echo (DFENC($tile['for'])) ?>" class="new-msg-input-box" type="text" data-target="" data-container="chat-message-container-<?php echo base64_encode(DFENC($slide['id'])) ?>" />
                                        <i class="fas fa-microphone green-text"></i>
                                    </div>

                                    <?php
                                    $insert_emoji_target = "#inp-for-" . base64_encode(DFENC($tile['for']));
                                    require "emoji_keyboard.php";
                                    ?>

                                    <div class="input input-blocked d-none chat-blocked" style="bottom: 2rem; position: absolute; transform: scale(1.1);max-width: 80%;">
                                        You can't reply to this conversation any more.
                                    </div>
                                </div>




                                <div class="modal-content-temp">
                                    <div class="modal fade bd-example-modal-lg" id="input-modal-for-<?php echo base64_encode(DFENC($tile['for'])) ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-body chat" style="width: 100%; box-shadow: none;">
                                                    <div class="row">
                                                        <div class="col-sm-12" style="justify-content: center; display: flex; align-items: center; flex-direction: column;">
                                                            <label for="send-media-input-<?php echo base64_encode(DFENC($tile['for'])) ?>">
                                                                <input type="file" data-preview-on="#media-preview-<?php echo base64_encode(DFENC($tile['for'])) ?>" name="send-media-input-<?php echo base64_encode(DFENC($tile['for'])) ?>" id="send-media-input-<?php echo base64_encode(DFENC($tile['for'])) ?>" accept=".png,.jpg,.jpeg,.gif" class="d-none preview-image">
                                                                <div class="upload-icon-<?php echo base64_encode(DFENC($tile['for'])) ?>" style="font-size: 8rem;">
                                                                    <i class="fas fa-cloud-upload-alt" style="color: #21c87a;"></i>
                                                                </div>
                                                            </label>
                                                            <label for="send-media-input-<?php echo base64_encode(DFENC($tile['for'])) ?>" class="send-media-wrapper d-none">
                                                                <img src="#" alt="Uploaded Image" id="media-preview-<?php echo base64_encode(DFENC($tile['for'])) ?>" style="max-width: 20rem; max-height: 20rem;">
                                                            </label>
                                                            <div class="upload-text text-center green-text">
                                                                Upload Image (Only png, jpeg, gif and jpg formats are allowed)
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 mt-3 input">
                                                        <input type="text" data-modal="#input-modal-dismiss-<?php echo base64_encode(DFENC($tile['for'])) ?>" name="" data-for="<?php echo (DFENC($tile['for'])) ?>" data-container="chat-message-container-<?php echo base64_encode(DFENC($slide['id'])) ?>" data-media="#send-media-input-<?php echo base64_encode(DFENC($tile['for'])) ?>" id="msg-for-<?php echo base64_encode(DFENC($tile['for'])) ?>" placeholder="Type your message here and Hit ENTER BUTTON to send" class="new-msg-input-box form-control" />
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" data-msg="#msg-for-<?php echo base64_encode(DFENC($tile['for'])) ?>" data-media="#send-media-input-<?php echo base64_encode(DFENC($tile['for'])) ?>" data-self="<?php echo base64_encode(DFENC($tile['for'])) ?>" data-for="<?php echo (DFENC($tile['for'])) ?>" class="btn send-msg-from-modal green-background click-effect text-white btn-sm btn-custom d-none">Send Message <i class="mdi mdi-send"></i></button>
                                                    <button type="button" id="input-modal-dismiss-<?php echo base64_encode(DFENC($tile['for'])) ?>" class="btn btn-danger btn-sm btn-custom" data-bs-dismiss="modal">Close</button>
                                                </div><!--end modal-footer-->
                                            </div><!--end modal-content-->
                                        </div><!--end modal-dialog-->
                                    </div>
                                </div>


                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include 'footer.php' ?>


    <!--  Page Scroll to Top  -->

    <div class="d-none" id="story-card-ui">
        <div class="custom-padding-story-card story-card-style update-story-view story-shower click-effect">
            <div class="card story-card">
                <div class="story-wrapper">
                    <div class="story-blocker"></div>
                    <div class="user-profile-story">
                        <img src="<?php echo $user_data['profile_pic'] ?>" alt="Profile">
                    </div>
                    <img src="//STORY_LINK//" alt="">
                    <div class="user-name-story">
                        Your Story
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="upload_story_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalDefaultLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background: #21c87a;">
                    <h6 class="modal-title m-0">Upload Story</h6>
                    <span class="btn-close click-effect text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" style="justify-content: center; display: flex; align-items: center; flex-direction: column;">
                            <label for="upload-story-input">
                                <input type="file" name="upload-story-input" id="upload-story-input" accept=".png,.jpg,.jpeg,.gif" class="d-none">
                                <div class="upload-icon" style="font-size: 8rem;">
                                    <i class="fas fa-cloud-upload-alt" style="color: #21c87a;"></i>
                                </div>
                            </label>
                            <label for="upload-story-input" class="upload-story-image-wrapper d-none">
                                <img src="#" alt="Uploaded Image" id="story-image-preview">
                            </label>
                            <div class="upload-text">
                                Upload Image (Only png, jpeg, gif and jpg formats are allowed)
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="reset-story-image-upload btn btn-danger btn-sm btn-custom" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary upload-story btn-sm btn-custom">Upload Story</button>
                </div>
            </div>
        </div>
    </div>


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


    <div class="modal fade bd-example-modal-lg" id="view_stories" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="margin-top: 5rem;">
                <div class="modal-header d-none" style="background: #21c87a;">
                    <h6 class="modal-title m-0">Stories</h6>
                    <span class="btn-close click-effect text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-times"></i>
                    </span>
                </div>
                <div class="modal-body" style="height: fit-content; padding-bottom: 0; padding-top: 0; border-top: 1px solid white;">
                    <div class="row">
                        <div class="col-12" style="padding: 0;">
                            <div class="card" style="margin-bottom: 1px;">
                                <div class="card-body" style="padding: 0;">
                                    <div class="row">
                                        <div class="col-lg-12 text-center">
                                            <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    <?php
                                                    $flag = "active";
                                                    foreach ($stories as $story) {
                                                    ?>
                                                        <div class="carousel-item <?php echo $flag; ?>" data-story-container="<?php echo DFENC($story['id']) ?>" style="height: 25rem;">
                                                            <div class="profile-info-wrapper">
                                                                <a href="profile?u=<?php echo DFENC($story['user_id']); ?>">
                                                                    <div class="user-profile-story" style="left: 1.2rem; top: 1.2rem;">
                                                                        <img src="<?php echo $story['profile_pic'] ?>" alt="" style="height: 3rem; width: 3rem; ">
                                                                    </div>
                                                                    <div class="user-name-story" style="top: 1.4rem; left: -18rem;">
                                                                        <?php echo $story['name'] ?>
                                                                    </div>
                                                                </a>

                                                                <div class="user-name-story ago-time" style="display: inline-block; max-width: fit-content;">
                                                                    <?php echo agoTime($story['created_date']) ?>
                                                                </div>
                                                            </div>
                                                            <img src="<?php echo $story['image'] ?>" style="max-height: 100%; object-fit: cover;" class="story-details" alt="...">
                                                        </div>
                                                    <?php
                                                        $flag = "";
                                                    }
                                                    ?>
                                                </div>
                                                <a class="carousel-control-prev click-effect update-story-view" style="background-image: linear-gradient(90deg, #000000c2, transparent);" href="#carouselExampleControls" role="button" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </a>
                                                <a class="carousel-control-next click-effect update-story-view" style="background-image: linear-gradient(270deg, #000000c2, transparent);" href="#carouselExampleControls" role="button" data-bs-slide="next">
                                                    <span class="visually-hidden">Next</span>
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                </a>
                                            </div>
                                        </div><!--end col-->


                                    </div><!--end row-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="story-upload-progress" class="upload-progress-preview d-none">
        <!-- <div class="progress mb-3">
            <div class="progress-bar bg-success progress-bar-striped" role="progressbar" style="width: 50%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
        </div> -->
        <div class="progress-text">
            <!-- Uploading <span class="progress-percent">50</span>% <br> -->
            Uploading Story Please wait...
        </div>
    </div>


    <div id="modal-content-dump"></div>

    <!-- 
    Essential Scripts
    =====================================-->


    <!-- Main jQuery -->
    <script src="./assets/chat_styles/script.js"></script>
    <!-- Bootstrap 3.1 -->
    <script src="plugins/bootstrap/js/popper.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- Slick Slider -->
    <script src="plugins/slick-carousel/slick/slick.min.js"></script>
    <script src="js/theme.js"></script>
    <script src="./assets/plugins/sweet-alerts/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/vkg001/library/0.0.3/shortcuts.js"></script>
    <script src="./assets/js/post-card-js.js"></script>
    <script src="./assets/js/home-js.js"></script>
    <script src="./assets/js/new-friend.js"></script>

    <script>
        $(document).ready(function() {
            $('#page_loader').fadeOut(300);

            document.addEventListener("online", function() {
                console.log("online");
            })
            document.addEventListener("offline", function() {
                console.log("offline");
            })

            let flag = '<?php echo $showFirstLoginMsg; ?>';
            if (flag == '1') {
                Swal.fire({
                    customClass: {
                        confirmButton: 'btn btn-white btn-circled',
                    },
                    buttonsStyling: false,
                    icon: 'success',
                    title: 'Your account has been created.',
                    showConfirmButton: true,
                    timer: 4000
                })
            }


            window.history.pushState('data', 'title', '<?php echo explode(".", $_SERVER['PHP_SELF'])[0] ?>');
            let posted = '<?php echo $posted; ?>';
            switch (posted) {
                case '-1':
                    break;
                case "0":
                    Swal.fire({
                        customClass: {
                            confirmButton: 'btn btn-white btn-circled',
                        },
                        buttonsStyling: false,
                        icon: 'error',
                        title: 'Some error occured. <br>Please try again.',
                        showConfirmButton: true,
                        timer: 4000
                    })
                    break;
                case '1':
                    Swal.fire({
                        customClass: {
                            confirmButton: 'btn btn-white btn-circled',
                        },
                        buttonsStyling: false,
                        icon: 'success',
                        title: 'Post shared',
                        showConfirmButton: true,
                        timer: 4000
                    })
                    break;
                case '2':
                    Swal.fire({
                        customClass: {
                            confirmButton: 'btn btn-white btn-circled',
                        },
                        buttonsStyling: false,
                        icon: 'error',
                        title: 'Something went wrong.',
                        showConfirmButton: true,
                        timer: 4000
                    })
                    break;
                default:
                    break;
            }

        });


        let card_width = <?php echo STORY_CARD_WIDTH ?>;
        let wrapper_width = 1;
        $(".story-card-style").each(function() {
            wrapper_width += card_width;
        });

        $(".story-row").css("width", wrapper_width + "rem");
        $(".story-block-wrapper").css("overflow-x", "auto");

        $(".story-block-wrapper").on("scroll", function() {
            if ($(this).scrollLeft() == 0) {
                $(this).children(".swipper-left").fadeOut();
            } else {
                $(this).children(".swipper-left").fadeIn();
            }
            if ($(this).scrollLeft() + (card_width * 1.3 * 4 * 15) >= $(this).prop("scrollWidth") - 30) {
                $(this).children(".swipper-right").fadeOut();
            } else {
                $(this).children(".swipper-right").fadeIn();
            }
        })

        $(document).on("click", ".swipper-right", function() {
            let target = $(this).data("target");
            $(target).animate({
                scrollLeft: $(target).scrollLeft() + (20 * 1.3 * 5),
            }, 500);
        });

        $(document).on("click", ".swipper-left", function() {
            let target = $(this).data("target");
            $(target).animate({
                scrollLeft: $(target).scrollLeft() - (20 * 1.3 * 5),
            })
        });
    </script>
    <script>
        $(document).ready(function() {

            $(document).on("click", ".remove-friend-suggestion", function() {
                $(this).parent().parent().parent().fadeOut();
            });
        });
    </script>
</body>

</html>