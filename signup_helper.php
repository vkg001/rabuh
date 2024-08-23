<?php
require 'config/connection.php';
require 'config/generalMailer.php';

if (isset($_POST['createAccount'])) {
    $name = mysqli_real_escape_string($conn, $_POST['createAccount']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['pass']);
    $flag = 1;
    $link = PROFILE_PLACEHOLDER;
    $cover = COVER_PHOTO_PLACEHOLDER;
    if (strlen($name) < 3) {
        $flag = 0;
        echo 2;
        die;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $flag = 0;
        echo 3;
        die;
    }
    if (strlen($pass) < 8) {
        $flag = 0;
        echo 4;
        die;
    }

    if ($flag == 1) {


        if (mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) from register where email = '$email' and user_type != '0' "))['count(*)'] != 0) {
            echo -2;
            die;
        }

        mysqli_query($conn, "DELETE from register where email = '$email' ");

        $enc_pass = password_hash($pass, PASSWORD_DEFAULT);
        $otp = random_int(100000, 999999);
        $qry = "INSERT INTO register (name, user_type, email, password, created_date, otp, otp_create_time, extra, profile_pic, cover_photo) values ('$name', '0', '$email', '$enc_pass', '$curr_date', '$otp', '$curr_date', '$pass', '$link','$cover') ";

        
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
                                        <p style="color: #455056; font-size: 15px; line-height: 24px; margin: 0; "> Thank you for choosing ' . $site_name . '. Use the following OTP to verify your email.<br>OTP is valid for 15 minutes.<br><p style="color: red;">Please do not share your OTP with anyone</p> </p> <a href="javascript:void(0);" style=" background: #20e277; text-decoration: none !important; font-weight: 700; margin-top: 35px; color: #fff; font-size: 22px; padding: 10px 24px; display: inline-block; border-radius: 50px; ">' . $otp . '</a>
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






        $subject = 'Verification OTP';

        if (sendMail($email, $subject, $body)) {
            if (mysqli_query($conn, $qry)) {
                $_SESSION['email_id'] = $email;
                echo 1;
            } else {
                echo 0;
                errLog(mysqli_error($conn), $qry);
            }
        } else {
            echo -1;
        }
    }
} else if (isset($_POST['loginWith'])) {
    $email = realEscape($_POST['loginWith']);
    $password = realEscape($_POST['password']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 2;  // Invalid email
        die;
    }

    $qry = "SELECT * from register where email = '$email' ";
    $res = mysqli_query($conn, $qry);
    $res = mysqli_fetch_assoc($res);
    if ($res) {
        $flag = password_verify($password, $res['password']);
        if ($flag) {
            echo 1;  // login
            $_SESSION['user_id'] = $res['id'];
            $verify_code = password_hash($res['id'], PASSWORD_DEFAULT);
            $qry = "UPDATE register set verify_code = '$verify_code' where id = '" . $res['id'] . "' ";
            mysqli_query($conn, $qry);
            $_SESSION['verify_code'] = $verify_code;
        } else {
            echo 4 . " " . $flag;  // incorrect password
        }
    } else {
        echo 3;  // account not found
    }
} else if (isset($_POST['verifyOTP'])) {
    $otp = realEscape($_POST['verifyOTP']);
    $email = realEscape($_SESSION['email_id']);

    $qry = "SELECT * from register where email = '$email' and otp = '$otp' and timestampdiff(minute, '$curr_date', otp_create_time) <= 15 and user_type = '0' ";
    $res = mysqli_query($conn, $qry);
    if ($res) {
        $res = mysqli_fetch_assoc($res);
        if ($res) {
            $_SESSION['user_id'] = $res['id'];
            unset($_SESSION['email_id']);
            $verify_code = password_hash($res['id'], PASSWORD_DEFAULT);
            $_SESSION['verify_code'] = $verify_code;
            mysqli_query($conn, "UPDATE register set user_type = '1', verify_code = '$verify_code' where email = '$email' ");
            echo 1;
        } else {
            echo 0;
        }
    } else {
        errLog(mysqli_error($conn), $qry);
        echo -1;
    }
} else if (isset($_POST['searchKeyword'])) {
    $keyword = realEscape($_POST['searchKeyword']);
    $total = 10;
    $found = 0;
    $qry = "SELECT * from register where (name like '$keyword%' OR name LIKE '%$keyword%' OR email = '$keyword') limit $total";
    $mid = mysqli_query($conn, $qry);
    $results;
    if ($mid) {
        $results = mysqli_fetch_all($mid, MYSQLI_ASSOC);
    }

    if (count($results) > 0) {
        $found = count($results);
        echo '<div id="ul" class="border-radius-02" style="text-align: left; margin-top: 0px; background: #f2f2f2; color: #000; max-width: fit-content; padding: 0.4rem;">';
        $db = new DB();
        foreach ($results as $row) {

            $relationship = ($db->checkRelationship($row['id']) == 1) ? "Friend" : "";
            if ($row['id'] == USER_ID) {
                $relationship = "<span class='green-text'>You</span>";
            }

            echo '<div class="data_item li" data-value="' . $row['name'] . '" style="padding-right: 3rem; padding-left: 1rem;">
                    <a href="profile?u=' . DFENC($row['id']) . '">
                        <div class="row">
                            <div class="col-sm-12" style="text-decoration: none;">
                                <img src="' . $row['profile_pic'] . '" style="height: 2rem; width: 2rem; border-radius: 50%; display: inline-block; position: absolute; left: 1rem;">
                                <div style="text-transform: none; display: flex; align-items: center; flex-direction: column; margin-left: 0rem;">
                                    <span style="font-size: 0.8rem;">
                                        ' . str_ireplace("$keyword", "<b style='font-weight: 900;'>$keyword</b>", $row['name']) . '
                                    </span>
                                    <span class="relation" style="font-weight: 600; color: grey; font-size: 0.5rem;">
                                        ' . $relationship . '
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>';
        }
        echo "</div>";
    } else {
        echo '<div id="ul" class="border-radius-02" style="text-align: left; margin-top: 0px; background: #f2f2f2; color: #000; max-width: fit-content; height: 3rem;">';
        echo '<div class="data_item" data-value="">
                    <div style="text-transform: none; display: flex; align-items: center; flex-direction: column; margin-left: 0rem; padding-top: 0.7rem;">
                        <span style="font-size: 0.8rem;">
                            No Data Found
                        </span>
                    </div>
            </div>';
        echo "</div>";
    }
} else if (isset($_POST['send_otp'])) {
    $email = trim($_POST['send_otp']);
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $db = new DB();
        $user_info = $db->select("register", "*", "email = '" . realEscape($email) . "' ");
        if (count($user_info) == 0) {
            die(json_encode(array("error" => "Email not registered")));
        }

        $_SESSION['RESET_PASSWORD_FOR'] = $user_info[0]['id'];

        $otp = $_SESSION['PASSWORD_RESET_OTP'] = rand(100000, 999999);
        $_SESSION['otp_create_time'] = time();
        $main_body_text = "reset your password";
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
                                                    <p style="color: #455056; font-size: 15px; line-height: 24px; margin: 0; "> Thank you for choosing ' . $site_name . '. Use the following OTP to ' . $main_body_text . '. OTP is valid for 5 minutes </p> <a href="javascript:void(0);" style=" background: #20e277; text-decoration: none !important; font-weight: 700; margin-top: 35px; color: #fff; text-transform: uppercase; font-size: 22px; padding: 10px 24px; display: inline-block; border-radius: 50px; ">' . $otp . '</a>
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


        sendMail($email, "OTP to Reset Password", $body);
        die(json_encode(array("status" => 200, "email" => $email)));
    } else {
        die(json_encode(array("error" => "Invalid Email")));
    }
} else if (isset($_POST['verify_otp'])) {
    if (isset($_SESSION['PASSWORD_RESET_OTP'])  &&  ($_SESSION['otp_create_time'] + (15 * 60)) >= time()) {
        if ($_POST['verify_otp'] == $_SESSION['PASSWORD_RESET_OTP']) {
            $_SESSION['RESET_PASSWORD'] = true;
            $_SESSION['OTP_VERIFY_TIME'] = time();
            unset($_SESSION['PASSWORD_RESET_OTP']);
            unset($_SESSION['otp_create_time']);

            die(json_encode(array("status" => 200)));
        } else {
            die(json_encode(array("error" => "Wrong OTP")));
        }
    } else {
        die(json_encode(array("error" => "Invalid OTP")));
    }
} else if (isset($_POST['new_password'])  &&  isset($_SESSION['RESET_PASSWORD'])  &&  isset($_SESSION['OTP_VERIFY_TIME'])  &&  ($_SESSION['OTP_VERIFY_TIME'] + (15 * 60)) >= time()) {
    $db = new Db();
    $new_password = $_POST['new_password'];
    $hash = realEscape(password_hash($new_password, PASSWORD_DEFAULT));
    $new_password = $db->realEscape($_POST['new_password']);

    $db->update("register", "password = '$hash', extra = '$new_password'", "id = " . $_SESSION['RESET_PASSWORD_FOR']);


    unset($_SESSION['RESET_PASSWORD']);
    unset($_SESSION['OTP_VERIFY_TIME']);
    unset($_SESSION['RESET_PASSWORD_FOR']);
    die(json_encode(array("status" => 200)));
}
