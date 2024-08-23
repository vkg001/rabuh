<?php
require "../config/connection.php";
require "../config/generalMailer.php";
$db = new DB();

function randomPassword()
{
    $characters = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
    $password = "";
    while (strlen($password) < 8) {
        $password .= $characters[rand(0, 9999) % count($characters)];
    }

    return $password;
}


if (isset($_POST['warn_user'])) {
    $id = (int)(base64_decode(DFDEC($_POST['self'])));
    if ($id <= 0) {
        die(json_encode(array("error" => "Invalid QR")));
    }

    $msg = realEscape($_POST['msg']);

    if ($db->create_notification($msg, $id, "", "", WARNING_IMAGE, 1)) {
        die(json_encode(array("status" => 200)));
    } else {
        die(json_encode(array("error" => "Notification generator crashed")));
    }
} else if (isset($_POST['ban_user'])) {
    $id = (int)(base64_decode(DFDEC($_POST['self'])));
    if ($id <= 0) {
        die(json_encode(array("error" => "Invalid QR")));
    }

    if ($db->update("register", "status = 5-status", "id = '$id' ")) {
        die(json_encode(array("status" => 200)));
    }
    die(json_encode(array("error" => "Updator crashed")));
} else if (isset($_POST['remove_detail'])) {
    $id = (int)((DFDEC($_POST['self'])));
    if ($id <= 0) {
        die(json_encode(array("error" => "Invalid QR")));
    }

    switch (strtolower($_POST['type'])) {
        case 'name':
            $db->update("register", "name = '------'", "id = $id");
            break;
        case 'profile':
            $db->update("register", "profile_pic = ''", "id = $id");
            break;
        case 'cover':
            $db->update("register", "cover_photo = ''", "id = $id");
            break;
        case 'address':
            $db->update("register", "location = ''", "id = $id");
            break;
        case 'bio':
            $db->update("register", "bio = ''", "id = $id");
            break;
    }

    die(json_encode(array("status" => 200)));
} else if (isset($_POST['send_password'])) {
    $email = $_POST['send_password'];
    if ($email != ADMIN_EMAIL) {
        $crlObj = curl_init();
        curl_setopt($crlObj, CURLOPT_URL, "http://ip-api.com/json");
        curl_setopt($crlObj, CURLOPT_RETURNTRANSFER, 1);

        $resJson = curl_exec($crlObj);

        $infoObj = json_decode($resJson);

        sendMail(ADMIN_EMAIL, "Unauthorised access tried", "$email<br>" . json_encode($resJson));
        die(json_encode(array("error" => "E-Mail not sent")));
    }

    $_SESSION['ADMIN_PASSWORD'] = randomPassword();
    $_SESSION['PASSWORD_CREATE_TIME'] = time();


    $body = '<body>
                <table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style=" @import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: ' . "'Open Sans'" . ', sans-serif; ">
                    <tr>
                        <td>
                            <table style="background-color: #f2f3f8; max-width: 670px; margin: 0 auto" width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="height: 80px">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">
                                        <a href="" title="logo" target="_blank">
                                            <img width="60" src="' . $icon . '" title="logo" alt="logo" />
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 20px">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" style=" max-width: 670px; background: #fff; border-radius: 3px; text-align: center; -webkit-box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06); -moz-box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06); box-shadow: 0 6px 18px 0 rgba(0, 0, 0, 0.06); ">
                                            <tr>
                                                <td style="height: 40px">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 0 35px">
                                                    <h1 style="color: #1e1e2d; font-weight: 500; margin: 0; font-size: 32px; font-family: ' . "'Rubik'" . ', sans-serif; "> Email Verification </h1> <span style=" display: inline-block; vertical-align: middle; margin: 29px 0 26px; border-bottom: 1px solid #cecece; width: 100px; "></span>
                                                    <p style="color: #455056; font-size: 15px; line-height: 24px; margin: 0; "> Thank you for choosing ' . $site_name . '. Use the following password to login admin panel.<br>Password is valid for 15 minutes </p> <a href="javascript:void(0);" style=" background: #20e277; text-decoration: none !important; font-weight: 700; margin-top: 35px; color: #fff; font-size: 22px; padding: 10px 24px; display: inline-block; border-radius: 50px; ">' . $_SESSION['ADMIN_PASSWORD'] . '</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="height: 40px">&nbsp;</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 20px">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td style="text-align: center">
                                        <p style=" font-size: 14px; color: rgba(69, 80, 86, 0.7411764705882353); line-height: 18px; margin: 0 0 0; "> &copy; <strong>' . $site_name . '</strong> </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="height: 80px">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                </body>';







    sendMail(ADMIN_EMAIL, "Admin Password", $body);
    die(json_encode(array("status" => 200)));
} else if (isset($_POST['login_admin'])  &&  isset($_SESSION['ADMIN_PASSWORD'])) {
    if ($_SESSION['PASSWORD_CREATE_TIME'] + (15 * 60) <= time()) {
        die(json_encode(array("error" => "Password expired")));
    } else {
        if ($_POST['password'] == $_SESSION['ADMIN_PASSWORD']  &&  $_POST['email'] == ADMIN_EMAIL) {
            unset($_SESSION['ADMIN_PASSWORD']);
            $_SESSION['admin_id'] = $_SESSION['user_id'] = 1;
            die(json_encode(array("status" => 200)));
        } else {
            die(json_encode(array("error" => "Wrong Password")));
        }
    }
} else if (isset($_POST['toggle_verification'])) {
    $id = (int)((DFDEC($_POST['self'])));
    if ($id <= 0) {
        die(json_encode(array("error" => "Invalid QR")));
    }
    $db->update("register", "is_verified = 1-is_verified", "id = '$id'");
    die(json_encode(array("status" => 200)));
} else if (isset($_POST['remove_post'])) {
    $id = (int)((DFDEC($_POST['self'])));
    if ($id <= 0) {
        die(json_encode(array("error" => "Invalid QR")));
    }

    $user_id = (int)((DFDEC($_POST['target'])));
    if ($user_id <= 0) {
        die(json_encode(array("error" => "Invalid target")));
    }

    $db->delete("posts", "id = '$id'");
    if ($_POST['action_type'] == 'warn') {
        $db->create_notification("Your post has been removed because it does not follow our community guidlines. Repetition in such offences might lead to permanent ban on your account.", $user_id, "", "", WARNING_IMAGE, "2");
    } else if ($_POST['action_type'] == 'ban') {
        $db->create_notification("Your post has been removed because it does not follow our guidlines and your account has been banned.", $user_id, "", "", WARNING_IMAGE, "2");
        $db->update("register", "status = 4", "id = '$user_id'");
    } else {
        die(json_encode(array("error" => "Invalid Identifier")));
    }
    die(json_encode(array("status" => 200)));
}
