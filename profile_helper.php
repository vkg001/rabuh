<?php
require_once "./config/connection.php";
if (USER_ID < 1) {
    die(json_encode(array('error' => 'Session Expired')));
}

$db = new DB();

if (isset($_SESSION['admin_id'])  &&  isset($_SESSION['VISITING_PROFILE'])) {
    define("USER_ID_V", $_SESSION['VISITING_PROFILE']);
} else {
    define("USER_ID_V", USER_ID);
}


if (isset($_POST['update_profile'])) {
    $response = array();
    if (!isset($_FILES['new_profile'])) {
        $response = array("error", "No profile selected");
    } else {
        require "./config/cloudinaryConfig.php";
        $link = getURL($_FILES['new_profile']['tmp_name']);

        if ($db->update("register", "profile_pic = '$link' ", "id = " . USER_ID_V)) {
            $response = array("success" => "Profile Updated", "link" => $link);
        }

        $data = array(
            "user_id" => USER_ID_V,
            "type" => "PROFILE",
            "image" => $link,
            "description" => "",
            "status" => 1,
        );

        $db->insert("posts", $data);
    }

    die(json_encode($response));
} else if (isset($_POST['update_cover'])) {
    $response = array();
    if (!isset($_FILES['new_cover'])) {
        $response = array("error", "No cover photo selected");
    } else {
        require "./config/cloudinaryConfig.php";
        $link = getURL($_FILES['new_cover']['tmp_name']);
        if ($db->update("register", "cover_photo = '$link' ", "id = " . USER_ID_V)) {
            $response = array("success" => "Cover Photo Updated", "link" => $link);
        }

        $data = array(
            "user_id" => USER_ID_V,
            "type" => "COVER",
            "image" => $link,
            "description" => "",
            "status" => 1,
        );

        $db->insert("posts", $data);
    }

    die(json_encode($response));
} else if (isset($_POST['updateUsername'])) {
    $username = $db->realEscape(trim($_POST['updateUsername']));
    $response = array();
    if ($username == ''  ||  strlen($username) > 25) {
        $response = array("error" => "Invalid Username");
    } else {
        if ($curr_date <= date("Y-m-d H:i:s", strtotime($user_data['name_update'] . "+60 days"))  &&  !isset($_SESSION['admin_id'])) {
            $date_diff = round(abs(strtotime($curr_date) - strtotime(date("Y-m-d H:i:s", strtotime($user_data['name_update'] . "+60 days")))) / (60 * 60 * 24), 0);
            $response = array("error" => "Cooldown period is not completed yet. You can change your username again after $date_diff days");
        } else {
            if ($db->update("register", "name = '$username', name_update = '$curr_date' ", "id = " . USER_ID_V)) {
                $response = array("success" => "Username Updated");
            } else {
                $response = array("error" => "Updator failed");
            }
        }
    }

    die(json_encode($response));
} else if (isset($_POST['create_post'])) {
    $post = trim(realEscape($_POST['create_post']));
    $response = array();
    if (strlen($post) < 1) {
        $response = array("error" => "Type something");
    } else {
        $cols = array(
            "user_id" => USER_ID_V,
            "description" => $post,
            "image" => "",
            "type" => "POST",
            "status" => 1,
        );
        if ($db->insert("posts", $cols)) {
            $response = array("success" => "Post shared");
        } else {
            $response = array("error" => "Post creator failed");
        }
    }

    die(json_encode($response));
} else if (isset($_POST['updateIntro'])) {
    $intro = isset($_POST['intro']) ? trim(realEscape($_POST['intro'])) : "";
    $bio = isset($_POST['bio']) ? trim(realEscape($_POST['bio'])) : "";
    $location = isset($_POST['loc']) ? trim(realEscape($_POST['loc'])) : "";

    $response = array();
    if (strlen($intro) > 200) {
        $response = array("error" => "Intro too long (Max. Limit 200 Characters)");
        die(json_encode($response));
    }

    if (strlen($bio) > 500) {
        $response = array("error" => "Bio. too long (Max. Limit 500 Characters)");
        die(json_encode($response));
    }

    if (strlen($location) > 100) {
        $response = array("error" => "Location too long (Max. Limit 100 Characters)");
        die(json_encode($response));
    }

    if ($db->update("register", "intro = '$intro', bio = '$bio', location = '$location' ", "id = " . USER_ID_V)) {
        $response = array("success" => "Information Updated");
    } else {
        $response = array("error" => "Updator failed.");
    }
    die(json_encode($response));
}
