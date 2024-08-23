<?php
require "config/connection.php";

if (USER_ID <= 0) {
    die(json_encode(array("error" => "Something went wrong please refresh the page.")));
}


function getFriendSuggestions()
{

    $result = "";
    $db = new DB();
    $people_you_may_know = $db->select("register rg", "rg.*", "rg.id <> " . USER_ID . " 
                                        AND rg.status = 1 
                                        AND rg.id NOT IN 
                                            (SELECT sent_to FROM friend_list WHERE sent_by = " . USER_ID . " ) 
                                        AND rg.id NOT IN 
                                            (SELECT sent_by FROM friend_list WHERE sent_to = " . USER_ID . " ) 
                                        ");
    $result .= '
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
                        ';

    foreach ($people_you_may_know as $item) {
        $result .= '
                                <div class="card client-card user-profile-card-top-left profile-card-feed-suggestion border-radius-06">
                                    <div class="card-body text-center client-card-body border-radius-06">
                                        <a href="profile?u=' . DFENC($item['id']) . '">
                                            <img src="' . $item['profile_pic'] . '" alt="user" class="rounded-circle thumb-xl zoom-on-hover friend-profile-in-card">
                                        </a>
                                        <span class="remove-suggested-profile click-effect">
                                            <i class="fa fa-times remove-friend-suggestion" data-id="' . DFENC($item['id']) . '"></i>
                                        </span>
                                        <a href="profile?u=' . DFENC($item['id']) . '">
                                            <h5 class="client-name fw-bold mt-3">' . $item['name'] . '</h5>
                                        </a>
                                        <p class="text-muted text-center mb-2 fw-semibold">' . $item['bio'] . '</p>
                                        <div class="icons-wrapper">
                                            <button class="btn btn-xs btn-success text-white send-friend-request" data-id="' . DFENC($item['id']) . '" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600; background: #21c830;"><i class="click-effect fas fa-user-plus"></i>Add Friend</button>
                                            <button class="btn btn-xs btn-danger text-white remove-friend-suggestion" data-id="' . DFENC($item['id']) . '" style="padding: 0.3rem; font-size: 0.6rem; font-weight: 600;"><i class="click-effect fas fa-user-alt-slash"></i> Remove</button>
                                        </div>
                                    </div>
                                </div>';
    }

    $result .= '
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    ';


    return $result;
}


function getTime($time)
{
    return '
    <div class="time">
        ' . agoTimeInMsgTile($time) . '
    </div>
    ';
}


function getMsg($type, $message)
{
    if ($message['media_type'] == -1) {
        return '';
    }


    $msg = $message['message'];
    $height_width = "";
    $media_block = "";

    $border_radius = 'border-radius: 1rem 1rem 1rem 0;';
    if ($type == 'parker') {
        $border_radius = 'border-radius: 1rem 1rem 0 1rem;';
    }

    if ($message['media_type'] > 0) {
        $height_width = ' style="min-height: fit-content; width: 14rem;" ';


        switch ($message['media_type']) {
            case 1:
                $media_block = '
                <a href="' . $message['media_link'] . '" style="display: inline-block; max-height: 12rem; width: 100%; border-bottom: 1px solid #8080803d;">
                    <img src="' . $message['media_link'] . '" style="' . $border_radius . ' background-color: white;max-height: 100%; max-width: 100%; margin-bottom: 1rem; display: inline-block;" />
                </a>
                ';
                break;
        }
    }



    return '

    <div class="message ' . $type . ' " ' . $height_width . ' >
    ' . $media_block . '
        ' . $msg . '
    </div>

    ';
}


function getLeftMsg($message)
{
    return getMsg("stark", $message);
}


function getRightMsg($message)
{
    return getMsg("parker", $message);
}


function getChatStarterUI()
{
    return '
    <h5 class="text-muted">Say Hello to your friend 😊.</h5>
    ';
}


function getNewMsgSeparator()
{
    return '
    <div class="time new-messages-separator">
        New Messages
    </div>
    ';
}


$db = new DB();

if (isset($_POST['send_friend_request'])) {
    $target = (int) DFDEC($_POST['send_friend_request']);
    if ($target <= 0) {
        die(json_encode(array("error" => "Error Code: 400")));
    }
    $existing_entry = $db->select("friend_list", "*", " ( sent_by = " . USER_ID . " AND sent_to = $target ) OR (sent_by = $target AND sent_to = " . USER_ID . " ) ");
    if (isset($existing_entry[0])  &&  isset($existing_entry[0]['id'])) {
        switch ($existing_entry[0]['status']) {
            case "3":
                die(json_encode(array("success" => "200", "response" => "Can't send friend request to this user.")));
                break;
            case "2":
                die(json_encode(array("success" => "200", "response" => "Friend request denied previously by this user.")));
                break;
            case "1":
                die(json_encode(array("success" => "200", "response" => "Already Friends.")));
                break;
            case "0":
                if ($db->delete('friends_list', "id = " . $existing_entry[0]['id']))
                    die(json_encode(array("success" => "200", "response" => "Request removed")));
                else
                    die(json_encode(array("error" => "Error Code: 500")));
                break;
        }
    } else {
        $col_n_val = array(
            "status" => "0",
            "sent_by" => USER_ID,
            "sent_to" => $target,
            "updated_date" => $curr_date,
            "accepted_date" => $curr_date,
        );

        if ($db->insert("friend_list", $col_n_val)) {
            die(json_encode(array("success" => "200", "response" => "Request sent")));
        } else {
            die(json_encode(array("error" => "Error Code: 500")));
        }
    }
} else if (isset($_POST['accept_friend_request'])) {
    $target = (int) DFDEC($_POST['accept_friend_request']);
    $data = $db->select("friend_list", "*", "id = $target");

    if ($db->update("friend_list", "status = 1", "sent_to = " . USER_ID . " AND status = 0 AND id = $target ")) {
        $db->create_notification($user_data['only_name'] . " has accepted your friend request.", $data[0]['sent_by'], "", "profile?u=" . DFENC(USER_ID), $user_data['profile_pic']);
        $cols = array(
            "sender" => USER_ID,
            "receiver" => $data[0]['sent_by'],
            "seen" => 3,
            "message" => "Say hi to your new friend",
            "media_type" => -1,
        );
        $db->insert("messages", $cols);
        die(json_encode(array("success" => "200", "response" => "Accepted")));
    } else {
        die(json_encode(array("error" => "Error Code: 500")));
    }
} else if (isset($_POST['deny_friend_request'])) {
    $target = (int) DFDEC($_POST['deny_friend_request']);
    if ($db->update("friend_list", "status = 2", "sent_to = " . USER_ID . " AND status = 0 AND id = $target ")) {
        die(json_encode(array("success" => "200", "response" => "Rejected")));
    } else {
        die(json_encode(array("error" => "Error Code: 500")));
    }
} else if (isset($_POST['load_notifications'])) {
    $notifications = $db->select("notification", "*", "user_id = " . USER_ID . " AND id < " . $_SESSION['LAST_NOTIFICATION'] . " ORDER BY id LIMIT 20 OFFSET " . $_SESSION['SHOWN_NOTIFICATION'] . " ");

    if ($notifications === false) {
        die(json_encode(array("error" => "Error Code: 500")));
    }

    $response = array("success" => true);
    if (count($notifications) > 0) {
        foreach ($notifications as $notif) {
            $_SESSION['LAST_NOTIFICATION'] = $notif['id'];
            $response["appendable"][] = array(
                "link" => ($notif['link'] != "") ? $notif['link'] : "#",
                "is_new" => ($notif['is_read'] == 0) ? "new-notification" : "",
                "is_image" => ($notif['image'] == "") ? "d-none" : "",
                "image_link" => $notif['image'],
                "text" => $notif['text'],
            );
        }
    } else {
        $response = array("success" => true, "no_data" => "<span style='font-size: 0.8rem; color: grey;'>All notifications have been shown.</span>");
    }

    $notifications = $db->select("notification", "*", "user_id = " . USER_ID . " AND id > " . $_SESSION['FIRST_NOTIFICATION'] . " ORDER BY id DESC LIMIT 20 OFFSET " . $_SESSION['SHOWN_NOTIFICATION'] . " ");

    if (count($notifications) > 0) {
        foreach ($notifications as $notif) {
            $response["prependable"][] = array(
                "link" => ($notif['link'] != "") ? $notif['link'] : "#",
                "is_new" => ($notif['is_read'] == 0) ? "new-notification" : "",
                "is_image" => ($notif['image'] == "") ? "d-none" : "",
                "image_link" => $notif['image'],
                "text" => $notif['text'],
            );
        }
    }

    die(json_encode($response));
} else if (isset($_POST['mark_all_notif_read'])) {
    if ($db->update("notification", "is_read = 1", "user_id = " . USER_ID)) {
        die(json_encode(array("success" => true)));
    } else {
        die(json_encode(array("error" => "500")));
    }
} else if (isset($_POST['mark_notif_read'])) {
    $id = (int) DFDEC($_POST['mark_notif_read']);
    if ($db->update("notification", "is_read = 1", "id = $id AND user_id = " . USER_ID)) {
        die(json_encode(array("success" => true)));
    } else {
        die(json_encode(array("error" => "500")));
    }
} else if (isset($_POST['upload_story'])) {
    $response = array();
    if (!isset($_FILES['image']['tmp_name'])) {
        $response = array("error" => "File not attached");
    } else {
        $ext = $_FILES['image']['name'];

        if (isset(validateFile($ext)['SUCCESS'])) {
            require "./config/cloudinaryConfig.php";
            $link = getURL($_FILES['image']['tmp_name']);
            $col_n_val = array(
                "user_id" => USER_ID,
                "type" => "STORY",
                "image" => $link,
            );

            if (!$db->insert("posts", $col_n_val)) {
                $response = array("error" => "Story upload failed");
            } else {
                $response = array("success" => "Your story has been shared", "story_link" => $link);
            }
        } else {
            $response = array("error" => "Invalid image");
        }
    }

    die(json_encode($response));
} else if (isset($_POST['update_story_view'])) {
    $id = (int)DFDEC($_POST['update_story_view']);

    $response = array();
    if ($id > 0) {
        $data = $db->select("view_stats", "*", "viewed_by = " . USER_ID . " AND item_id = $id AND item_type = 'STORY' ");
        if (count($data) > 0  &&  isset($data[0])  &&  isset($data[0]['id'])) {
            $db->update("view_stats", "viewed_times = viewed_times + 1, created_date = '" . $curr_date . "' ", "id = " . $data[0]['id']);
        } else {
            $col_n_val = array(
                "item_id" => $id,
                "item_type" => "STORY",
                "viewed_by" => USER_ID,
                "viewed_times" => 1,
            );
            $db->insert("view_stats", $col_n_val);
        }

        $response = array("success" => true);
    } else {
        $response = array("error" => "Invalid Story");
    }
    die(json_encode($response));
} else if (isset($_POST['likePost'])) {
    $qr = (int)DFDEC($_POST['likePost']);

    $data = $db->select("post_stats", "*",  "type = 'LIKE' AND user_id = " . USER_ID . " AND post_id = $qr ");
    if (isset($data[0]['id'])) {
        if (!$db->delete("post_stats", "type = 'LIKE' AND user_id = " . USER_ID . " AND id = " . $data[0]['id'])) {
            die(json_encode(array("error" => "Like remover failed")));
        }
    } else {
        $cols = array(
            "user_id" => USER_ID,
            "post_id" => $qr,
            "type" => "LIKE",
            "comment" => ""
        );

        if (!$db->insert("post_stats", $cols)) {
            die(json_encode(array("error" => "Like creator failed")));
        } else {
            $user_data = $db->select("register", "*", "id = " . USER_ID);
            $target = $db->select("posts", "*", "id = $qr");
            $db->create_notification($user_data['only_name'] . " Liked your post", $target[0]['user_id'], "", "profile?u=" . DFENC($user_data['id']) . "&cmd=" . DFENC("OPEN_POST") . "&i=" . DFENC($qr), $user_data['profile_pic']);
        }
    }

    die(json_encode(array("success" => true)));
} else if (isset($_POST['post_comment'])) {
    $qr = (int)DFDEC($_POST['qr']);
    if ($qr < 0) {
        die(json_encode(array("error" => "Invalid Post")));
    }


    $dt = array(
        "user_id" => USER_ID,
        "post_id" => $qr,
        "comment" => $_POST['post_comment'],
        "type" => "COMMENT",
    );

    $target = $db->select("posts", "*", "id = $qr");

    if ($db->insert("post_stats", $dt)) {
        $db->create_notification($user_data['only_name'] . " commented on your post", $target[0]['user_id'], "", "profile?cmd=" . DFENC("OPEN_POST") . "&i=" . DFENC($qr) . "&c=" . DFENC($user_data['id']), $user_data['profile_pic']);
        die(json_encode(array("success" => true)));
    }
    die(json_encode(array("error" => "Can't add comment at the moment")));
} else if (isset($_POST['refresh_chat'])) {
    $chat_list = array();

    $most_recent_msg = $db->select("messages", "*", "sender = " . USER_ID . " OR receiver = " . USER_ID . " ORDER BY id DESC ");
    if (count($most_recent_msg) > 0) {
        $most_recent_msg = $most_recent_msg[0];
    } else {
        $most_recent_msg = array();
    }

    $recent_msg_condition = true;

    $last_seen_list = array();

    foreach ($most_recent_msg as $key => $val) {
        if (!isset($_SESSION['MOST_RECENT_MSG'][$key])) {
            $recent_msg_condition = false;
            break;
        }

        if ($_SESSION['MOST_RECENT_MSG'][$key] != $most_recent_msg[$key]) {
            $recent_msg_condition = false;
            break;
        }
    }

    $typ = $db->select("register", "*", "typing_for = " . USER_ID);

    if ($_SESSION['MSG_SEEN_UPDATE']  &&  $recent_msg_condition  &&  $_SESSION['CHAT_LIST_LAST_UPDATED'] + 10 >= time()  &&  count($typ) == 0  &&  !$_SESSION['WAS_TYPING']) {
        $chat_list = array("have_update" => false);
    } else {
        $_SESSION['MOST_RECENT_MSG'] = $most_recent_msg;
        $_SESSION['CHAT_LIST_LAST_UPDATED'] = time();
        $_SESSION['MSG_SEEN_UPDATE'] = true;

        if (count($typ) > 0) {
            $_SESSION['WAS_TYPING'] = true;
        } else {
            $_SESSION['WAS_TYPING'] = false;
        }

        $response_data = "";

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
        $typing_effects = array();
        $message_box = array();

        if (count($chat) == 0) {
            $response_data = '<h4 class="text-muted">Add new friends to chat with them</h4>';
        }

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
                "(sender = '" . $slide['sender'] . "' AND receiver = '" . $slide['receiver'] . "')
                    OR
                (sender = '" . $slide['receiver'] . "' AND receiver = '" . $slide['sender'] . "')
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
                $temp_user_data = $db->select("register", "*", "id = '" . $slide['receiver'] . "'");

                $tile['profile_pic'] = $slide['receiver_pic'];
                $tile['name'] = $slide['receiver_name'];
                $tile['only_name'] = $slide['receiver_name'];
                if (isset($temp_user_data[0]['name'])) {
                    $tile['name'] = $temp_user_data[0]['name'];
                }
                $tile['last_seen'] = $slide['receiver_seen'];
                $tile['user_id'] = $slide['receiver'];
                $tile['for'] = $slide['receiver'];
            } else {
                $temp_user_data = $db->select("register", "*", "id = '" . $slide['sender'] . "'");
                $tile['profile_pic'] = $slide['sender_pic'];
                $tile['name'] = $slide['sender_name'];
                $tile['only_name'] = $slide['sender_name'];
                if (isset($temp_user_data[0]['name'])) {
                    $tile['name'] = $temp_user_data[0]['name'];
                }
                $tile['last_seen'] = $slide['sender_seen'];
                $tile['user_id'] = $slide['sender'];
                $tile['for'] = $slide['sender'];
            }

            $last_seen_list[] = array("#seen-of-" . base64_encode(DFENC($tile['for'])) => agoTime($tile['last_seen'], true, true));

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

            $typ = $db->select("register", "*", "typing_for = " . USER_ID . " AND id = " . $tile['user_id']);
            if (isset($typ[0]['id'])) {
                $tile['seen'] = '<br><span class="green-text">Typing...</span>';
                $typing_effects[] = "#typing-msg-" . base64_encode(DFENC($tile['user_id'])) . "";
                $message_box[] = "chat-message-container-" . base64_encode(DFENC($slide['id']));
            }

            if ($tile['profile_pic'] == '') {
                $tile['profile_pic'] = PROFILE_PLACEHOLDER;
            }

            $unread_count_badge = '';
            if (($results['total_unread']) > 0) {
                $unread_count_badge = '
                    <div class="badge" style="font-family: cursive; background: #21c87a; padding-top: 0;display: flex; align-items: center; justify-content: center;margin-top: 0.4rem; margin-left: 0.4rem;">' . $results['total_unread'] . '</div>
                    ';
            }

            $icon = "";
            if ($results['media_type'] == 1) {
                $icon = '<i class="mdi mdi-image-size-select-actual" style="margin-right: 0.5rem;"></i>';
            }

            $db->update("messages", "seen = 1", "seen < 1 AND sender = '" . $tile['user_id'] . "' AND receiver = " . USER_ID);

            $response_data .= '
            <span id="avatar-' . base64_encode(DFENC($slide['id'])) . '">
                <div class="contact border-radius-06 bar chat-list-item click-effect toggleVisibility"  data-refresher="#refresh-chat-spinner-' . base64_encode(DFENC($tile['for'])) . '" data-msg-wrapper="#messages-wrapper-' . base64_encode(DFENC($tile['for'])) . '" data-self="' . (DFENC($tile['for'])) . '" data-msg-container="chat-message-container-' . base64_encode(DFENC($slide['id'])) . '" data-msg-loader="chat-msg-loader-' . base64_encode(DFENC($slide['id'])) . '" data-onclick-hide="#chat-list" data-onclick-fadein="#chat-individual-id-' . base64_encode(DFENC($slide['id'])) . '" data-chat-spinner="#chat-spinner-' . base64_encode(DFENC($slide['id'])) . '" data-init="0" style="justify-content: left; align-items: flex-start; text-align: left; border-bottom: 1px dashed silver; padding-bottom: 5rem; margin-top: 0.1rem; padding-top: 0.2rem;">
                    <div class="pic" style="background-image: url(\'' . $tile['profile_pic'] . '\'); margin-top: 0.4rem; margin-left: -4rem;">
                    </div>
                    ' . $unread_count_badge . '
                    <div class="last-chat-date" data-date="' . ($results['created_date']) . '" style="position: relative;right: -14rem;margin-bottom: -2rem;">
                        ' . agoTime($results['created_date'], true) . '
                    </div>
                    <div data-parent="#avatar-' . base64_encode(DFENC($slide['id'])) . '" data-fullname="' . ($tile['only_name']) . '" class="name chat-avatar-names" style="margin-top: 0.4rem; margin-left: 0.4rem; font-weight:600;">
                        ' . ($tile['name']) . '
                    </div>
                    <div class="seen" style="margin-top: -0.4rem; margin-left: 0.4rem;">
                        ' . $icon . '<div style="display: inline-block; width: 12rem; height: 1.7rem; overflow: hidden; margin-top: -0.5rem; position: relative; top: 0.5rem;">' . (($results['message'])) . '</div>
                        ' . $tile['seen'] . '
                    </div>
                </div>
            </span>
            ';
        }

        $chat_list = array("have_update" => true, "data" => $response_data, "typing" => $typing_effects, "message_box" => $message_box);
    }

    $response = array(
        "success" => true,
        "chat_list" => $chat_list,
        "last_seens" => $last_seen_list,
    );

    die(json_encode($response));
} else if (isset($_POST['get_chat'])) {
    $qr = (int)(DFDEC($_POST['qr']));
    $response = array();
    if ($qr > 0) {
        $msgs = $db->select("messages", "*", "((sender = " . USER_ID . " AND receiver = '$qr') OR (receiver = " . USER_ID . " AND sender = '$qr')) ORDER BY created_date ASC LIMIT 100");

        $msg_dump = "";
        $new_msg_dump = "";
        $last_time = false;

        $x = $db->select("register", "*", "id = '$qr'");
        if (!isset($x[0]['name'])) {
            errlog($db->qry_frame, "----$qr----");
        }
        $qr_name = $x[0]['name'];

        if (!isset($_SESSION['PRINTED_MSG'][$qr])) {
            $_SESSION['PRINTED_MSG'][$qr] = array();
        }

        if (count($msgs) == 0) {
            $msg_dump = getChatStarterUI();
        } else {

            if (count($msgs) < 50) {
                $msg_dump = "<h6 class='green-text mt-4 mb-4 text-center'>You and $qr_name are now friends.</h6>";
            }

            $new_msg_separator = false;

            foreach ($msgs as $msg) {

                if (!$new_msg_separator  &&  $msg['sender'] == "$qr"  &&  ($msg['seen'] == '0'  ||  $msg['seen'] == '1')) {
                    $msg_dump .= getNewMsgSeparator();
                    $new_msg_separator = true;
                }

                if (!$last_time  ||  date("Y-m-d H:i:s", strtotime($last_time . "+15 min")) < $msg['created_date']) {
                    $msg_dump .= getTime($msg['created_date']);
                }

                if (!isset($_SESSION['PRINTED_MSG'][$qr][$msg['id']])) {
                    $_SESSION['PRINTED_MSG'][$qr][$msg['id']] = true;

                    if (!$last_time  ||  date("Y-m-d H:i:s", strtotime($last_time . "+15 min")) < $msg['created_date']) {
                        $new_msg_dump .= getTime($msg['created_date']);
                    }

                    if ($msg['sender'] == USER_ID) {
                        $new_msg_dump .= getRightMsg($msg);
                    } else {
                        $new_msg_dump .= getLeftMsg($msg);
                    }
                }

                $last_time = $msg['created_date'];

                if ($msg['sender'] == USER_ID) {
                    $msg_dump .= getRightMsg($msg);
                } else {
                    $msg_dump .= getLeftMsg($msg);
                }
            }
        }

        $db->update("messages", "seen = 2", "seen < 2 AND sender = '$qr' AND receiver = " . USER_ID);
        $_SESSION['MSG_SEEN_UPDATE'] = false;
        $response = array("success" => true, "all_messages" => $msg_dump, "new_messages" => $new_msg_dump);
        if ($db->checkRelationship($qr) != 1) {
            $response['blocked'] = true;
        }
        if ($new_msg_separator) {
            $response['new_msg_separator'] = true;
        }
    } else {
        $response = array("invalid_qr" => "Invalid Session");
    }
    die(json_encode($response));
} else if (isset($_POST['start_typing'])) {
    $target = (int)DFDEC($_POST['start_typing']);

    if ($target > 0) {
        $db->update("register", "typing_for = " . $target, "id = " . USER_ID);
    }

    die(json_encode(array("success" => true)));
} else if (isset($_POST['stop_typing'])) {
    $db->update("register", "typing_for = 0", "id = " . USER_ID);
    die(json_encode(array("success" => true)));
} else if (isset($_POST['send_message'])) {
    $response = array();
    $receiver = (int) DFDEC($_POST['send_message']);

    if ($db->checkRelationship($receiver) == 1) {

        $media_type = 0;
        $media_link = "";
        if (isset($_FILES['media']['tmp_name'])) {
            $media_type = 1;
            require_once "./config/cloudinaryConfig.php";
            $media_link = getURL($_FILES['media']['tmp_name']);
        }

        $cols = array(
            "sender" => USER_ID,
            "receiver" => $receiver,
            "message" => $_POST['message'],
            "type" => "MSG",
            "media_type" => $media_type,
            "media_link" => $media_link,
        );
        if ($db->insert("messages", $cols)) {
            $response = array("success" => true);
        } else {
            $response = array("error" => true);
        }
    } else {
        $response = array("blocked" => true);
    }

    die(json_encode($response));
} else if (isset($_POST['post_view'])) {
    $id = (int)DFDEC($_POST['post_view']);

    $response = array();
    if ($id > 0) {
        // $data = $db->select("view_stats", "*", "viewed_by = " . USER_ID . " AND item_id = $id AND item_type = 'POST' ");
        // if (count($data) > 0  &&  isset($data[0])  &&  isset($data[0]['id'])) {
        //     $db->update("view_stats", "viewed_times = viewed_times + 1, created_date = '" . $curr_date . "' ", "id = " . $data[0]['id']);
        // } else {
        //     $col_n_val = array(
        //         "item_id" => $id,
        //         "item_type" => "POST",
        //         "viewed_by" => USER_ID,
        //         "viewed_times" => 1,
        //     );
        //     $db->insert("view_stats", $col_n_val);
        // }

        $response = array("success" => true);
    } else {
        $response = array("error" => "Invalid Post");
    }
    die(json_encode($response));
} else if (isset($_POST['blockuser'])) {
    $target = (int)(DFDEC($_POST['blockuser']));
    if ($target > 0) {
        $prev = $db->select("block_list", "*", "blocked_by = '" . USER_ID . "' AND blocked = '$target' ");
        if (count($prev) > 0) {
            $db->delete("block_list", "blocked_by = '" . USER_ID . "' AND blocked = '$target' ");

            $prev_ver = $db->select("block_list", "*", "blocked = '" . USER_ID . "' AND blocked_by = '$target' ");
            if (count($prev_ver) == 0) {
                $db->update("friend_list", "status = 1", "(sent_by = '" . USER_ID . "' AND sent_to = '$target' ) OR (sent_to = '" . USER_ID . "' AND sent_by = '$target')");
            }

            $response = array(
                "success" => true,
                "now" => '<i class="mdi mdi-block-helper me-2"></i>Block',
            );
        } else {
            $cols = array(
                "blocked_by" => USER_ID,
                "blocked" => $target,
            );

            $db->insert("block_list", $cols);
            $db->update("friend_list", "status = 3", "(sent_by = '" . USER_ID . "' AND sent_to = '$target' ) OR (sent_to = '" . USER_ID . "' AND sent_by = '$target')");
            $response = array(
                "success" => true,
                "now" => 'Unblock',
            );
        }
    } else {
        $response = array(
            "error" => "Invalid Data",
        );
    }

    die(json_encode($response));
} else if (isset($_POST['refresh_feed'])) {


    if (count($_SESSION['PRINTED_POSTS']) > 0) {
        $id_not_in = "ps.id NOT IN (" . implode(",", $_SESSION['PRINTED_POSTS']) . ") AND ";
    } else {
        $id_not_in = "";
    }

    $posts = $db->select(
        "posts ps",
        "ps.*, usr.is_verified, 
            (
                (SELECT COUNT(*) FROM view_stats WHERE item_type = 'POST' AND item_id = ps.id )
                    - 
                (SELECT COUNT(*) FROM post_stats WHERE post_id = ps.id)
            ) AS interactions,
            usr.name, usr.profile_pic",

        "   $id_not_in

            (ps.type = 'POST'  OR  ps.type = 'PROFILE'  OR  ps.type = 'COVER')
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

    $post_data = "";
    if (count($posts) == 0) {
        $post_data = "<h4 class='text-muted text-center mt-5'>Add new friends to see their posts</h4>";
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



        $post_data .= '
        <div data-seen="0" data-self="' . DFENC($post['id']) . '" class="row justify-content-center post-wrapper chat" style="border: 1px solid #e8e8e8; margin: 0.1rem; border-radius: 0.5rem; margin-bottom: 1rem; margin-left: -1rem; width: 46.5rem; padding: 1rem;">
            <div class="col-lg-12 border-radius-02" style="padding: 0.3rem; background-color: white;">
                <div class="col-sm-2" style="padding: 0; margin: 0; padding-left: 0.6rem; padding-top: 0.6rem;">
                    <a href="profile?u=' . DFENC($post['user_id']) . '" style="height: 100% !important; display: inline-block; min-width: 15rem;">
                        <figure class="userIcon" style="left: 0.6rem; position: absolute;">
                            <img src="' . $post['profile_pic'] . '" style="height: 3rem; width: 3rem;">
                        </figure>
                        <i class="status-mark online" style="bottom: 0rem; right: 4rem;"></i>
                        <span class="" style="font-weight: 700; color: #636363; left: 4rem; position: relative; width: 37rem; display: inline-block; text-align: left;">' . $post['name'] . '</span>
                        <h6 style="font-size: 0.6rem; color: grey; left: 4rem; position: relative; text-align: left;">' . date("d F Y | H:i", strtotime($post['created_date'])) . '</h6>
                    </a>
                </div>
                <hr color="green" style="margin-bottom: 0rem; margin-top: 0.5rem; opacity: 0.1;">';

        if ($post['image'] != '') {
            $post_data .= '
                    <h6 class="post-caption">
                        ' . strShort($post['description'], 600) . '
                    </h6>
                ';
        }


        $post_data .= '
                <div class="blog-box">
                    <div class="blog-img-box">
                        <div style="display: grid; align-items: center; justify-content: center; border-bottom: 1px solid #eaeaea;">
                            <i class="far fa-thumbs-up pr-2 green-text like-btn-popup" style="display: inline-flex; position: absolute; z-index: -1; align-self: center; justify-self: center; font-size: 4rem;"></i>
                            ';


        if ($post['image'] != '') {
            $post_data .= '
                                <img src="' . $post['image'] . '" alt="" class="img-fluid blog-img double-tap-like" style="box-shadow: none; max-height: 35rem; margin-bottom: 1rem;">
                                ';
        } else {
            $post_data .= '
                                <h6 class="post-caption double-tap-like" style="font-size: 1.1rem; padding-bottom: 3rem;">
                                    ' . strShort($post['description'], 600) . '
                                </h6>
                                ';
        }

        $post_data .= '
                        </div>
                        <div class="col-sm-12" style="text-align: left; padding-top: 1rem;">
                            <div class="row" style="padding: 0; margin: 0;">
                                <div class="col-sm-6" style="padding: 0;">
                                    <i class="fas fa-thumbs-up text-success"></i>
                                    <span style="font-weight: 600; color: grey; font-size: 0.7rem; padding-left: 0.3rem;">
                                        Liked By ';

        $post_data .= ($recently_liked_by[0]['recent_name'] != "")

            ?
            $recently_liked_by[0]['recent_name'] . " and <span id='lkc" . base64_encode(DFENC($post['id'])) . "'>" . $recently_liked_by[0]['total_likes'] . "</span> others"

            :

            "<span id='lkc" . base64_encode(DFENC($post['id'])) . "'>" . $recently_liked_by[0]['total_likes'] . "</span> people";

        $post_data .= '
                                    </span>
                                </div>
                                <div class="col-sm-6" style="padding: 0; text-align: right; padding-right: 1rem;">
                                    <span style="font-weight: 600; color: grey; font-size: 0.7rem; padding-left: 0.3rem;">
                                        ' . $total_comments[0]['total_comments'] . ' Comments
                                    </span>
                                </div>
                            </div>
                            ';

        $already_liked = false;
        $like_check = $db->select("post_stats", "*", "type = 'LIKE' AND post_id = '" . $post['id'] . "' AND user_id = " . USER_ID);
        if (isset($like_check[0]['id'])) {
            $already_liked = true;
        }



        $post_data .= '
                            <div class="row text-center post-action-row" style="border-bottom: 1px solid #dddddd; border-top: 1px solid #dddddd; margin: 1rem 0; margin-bottom: 0.5rem;">
                                <div class="col-sm-6 post-engagement-btn post-like-btn click-effect';
        $post_data .= ($already_liked) ? "green-text" : "";

        $post_data .= '" data-qr="' . DFENC($post['id']) . '" data-up-c="#lkc' . base64_encode(DFENC($post['id'])) . '" data-liked="';
        $post_data .= $already_liked ? "1" : "0";
        $post_data .= '" style="border-right: 1px solid silver;">
                                    <i class="';
        $post_data .=  $already_liked ? "fas show-like-animation" : "far";
        $post_data .= ' fa-thumbs-up pr-2"></i>
                                    <span class="like-text">';
        $post_data .= $already_liked ? "Liked" : "Like";

        $post_data .= '</span>
                                </div>
                                <div class="col-sm-6 post-engagement-btn click-effect" data-bs-toggle="collapse" data-bs-target="#B' . base64_encode(DFENC($post['id'])) . '" data-focus-on="#C' . base64_encode(DFENC($post['id'])) . '">
                                    <i class="far fa-comment-alt pr-2"></i> Comment
                                </div>
                            </div>
                            ';


        if ($total_comments[0]['total_comments'] > 0) {
            $post_data .= '
                                <div class="row comment-caption">
                                    <a href="#" data-bs-toggle="collapse" data-bs-target="#comment-section-' . base64_encode(DFENC($post['id'])) . '">';
            $post_data .=
                (count($comments) > 0) ? "View more comments" : "View comments";
            $post_data .= '
                                    </a>
                                </div>
                            ';
        }



        $post_data .= '
                            <div class="row top-comments border-radius-02">


                            <div id="comment-section-' . base64_encode(DFENC($post['id'])) . '" class="accordion-collapse collapse" aria-labelledby="headingOne">
                                                    <div class="accordion-body">';

        $all_comments = $db->select(
            "post_stats ps",
            "ps.*, rg.name, rg.profile_pic",
            "ps.post_id = '" . $post['id'] . "' AND ps.type = 'COMMENT' ORDER BY created_date DESC ",
            "INNER JOIN register rg ON rg.id = ps.user_id "
        );

        foreach ($all_comments as $cmt) {
            $post_data .= '
                                                            <div class="col-sm-12 top-comment-wrapper">
                                                                <a target="" href="profile?u=' . DFENC($cmt['user_id']) . '" class="d-flex">
                                                                    <figure class="userIcon">
                                                                        <img src="' . $cmt['profile_pic'] . '" class="border-radius-50">
                                                                    </figure>
                                                                    <span class="" style="font-weight: 700; color: #14aa28; font-size: 0.7rem; padding-left: 0.4rem;">
                                                                        ' . $cmt['name'] . '
                                                                        <h6 style="font-size: 0.6rem; color: grey;">' . agoTime($cmt['created_date']) . '</h6>
                                                                    </span>
                                                                </a>
                                                                <div class="top-comment-text">
                                                                    ' . strShort($cmt['comment'], 35) . '
                                                                </div>
                                                            </div>';
        }
        $post_data .= '
                                                    </div>
                                                </div>';


        if (count($comments) > 0) {
            foreach ($comments as $cmt) {
                $post_data .= '
                                        <div class="col-sm-12 top-comment-wrapper">
                                            <a target="" href="profile?u=' . DFENC($cmt['user_id']) . '" class="d-flex">
                                                <figure class="userIcon">
                                                    <img src="' . $cmt['profile_pic'] . '" class="border-radius-50">
                                                </figure>
                                                <span class="" style="font-weight: 700; color: #14aa28; font-size: 0.7rem; padding-left: 0.4rem;">
                                                    ' . $cmt['name'] . '
                                                    <h6 style="font-size: 0.6rem; color: grey;">' . agoTime($cmt['created_date']) . '</h6>
                                                </span>
                                            </a>
                                            <div class="top-comment-text">
                                                ' . strShort($cmt['comment'], 35) . '
                                            </div>
                                        </div>';
            }
        }

        $post_data .= '
                            </div>
                            <div class="row pb-3 accordion-collapse collapse" id="B' . base64_encode(DFENC($post['id'])) . '">
                                <div class="col-sm-1">
                                    <figure class="userIcon" style="left: 0; position: absolute;">
                                        <img src="' . $user_data['profile_pic'] . '" style="height: 3rem; width: 3rem; border-radius: 50%;">
                                    </figure>
                                </div>
                                <div class="col-sm-9 comment-box-wrapper" style="padding-right: 0; padding-left: 0;">
                                    <input type="text" name="" id="C' . base64_encode(DFENC($post['id'])) . '" data-qr="' . DFENC($post['id']) . '" placeholder="Enter Comment" class="form-control comment-box" style="border-radius: 0.5rem; font-size: 0.7rem;">
                                </div>
                                <div class="col-sm-1 my-auto click-effect" style="font-size: 1.5rem;">';

        $keyboard_id = "pk" . base64_encode(DFENC($post['id']));
        $calling_btn = "#ie" . base64_encode(DFENC($post['id']));
        $post_data .= '
                                    <i class="material-icons" id="ie' . base64_encode(DFENC($post['id'])) . '" data-bs-toggle="collapse" data-bs-target="#' . $keyboard_id . '">insert_emoticon</i>
                                </div>
                                <div class="col-sm-1 my-auto click-effect post-comment-btn" style="font-size: 1.5rem;">
                                    <i class="far fa-paper-plane text-success"></i>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">';

        $insert_emoji_target = "#C" . base64_encode(DFENC($post['id']));
        $require_json_data = true;
        require "emoji_keyboard.php";

        $post_data .= $keyboard_ui_and_data;

        $post_data .= '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        ';
    }




    if (count($posts) > 7) {
        $post_data .= getFriendSuggestions();
    }


    if (count($posts) < 10) {
        $response['data_end'] = true;
        $post_data .= "<h4 class='text-muted text-center mt-5' style='padding-top: 7rem; padding-bottom: 7rem;'>
                            <i class='far fa-check-circle green-text' style='font-size: 3rem; margin-bottom: 3rem;'></i>
                            <br>
                            You're all caught up.
                            <br>
                            Add new friends to see their posts
                    </h4>";
    }

    $response["success"] = true;
    $response["data"] = $post_data;


    die(json_encode($response));
} else if (isset($_POST['get_new_notification'])) {
    $new_notification = $db->select("notification", "*", "id > " . $_SESSION['FIRST_NOTIFICATION'] . " AND user_id = " . USER_ID);

    $new_notification_data = "";

    $total_unread = 0;
    foreach ($new_notification as $notif) {
        $total_unread++;
        $_SESSION['FIRST_NOTIFICATION'] =  max($notif['id'], $_SESSION['FIRST_NOTIFICATION']);
        $new_notification_data .= '<a href="';
        $new_notification_data .= ($notif['link'] != "") ? $notif['link'] : "#";
        $new_notification_data .= '" data-id="' . DFENC($notif['id']) . '" class="notification-tile ';
        $new_notification_data .= ($notif['is_read'] == 0) ? "new-notification" : "";
        $new_notification_data .= '" style="';
        $new_notification_data .= ($notif['notification_type'] == 1) ? "background-color: yellow;" : "";
        $new_notification_data .= ($notif['notification_type'] == 2) ? "background-color: red;" : "";
        $new_notification_data .= '">';

        if ($notif['image'] != "") {
            $new_notification_data .= '
                <div class="notification-icon">
                    <img src="' . $notif['image'] . '" alt="">
                </div>';
        }
        $new_notification_data .= '
            <div class="notification-text" style="';
        $new_notification_data .= ($notif['notification_type'] == 2) ? "color: white;" : "";
        $new_notification_data .= '">
                ' . $notif['text'] . '
            </div>
            <div class="notif-date text-muted" style="';
        $new_notification_data .= ($notif['notification_type'] == 2) ? "color: white !important;" : "";
        $new_notification_data .= '">
                ' . agoTime($notif['created_date']) . '
            </div>
        </a>';
    }

    die(json_encode(array("status" => 200, "data" => $new_notification_data, "total_unread" => $total_unread)));
}
