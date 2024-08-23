<?php

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
} else {
    $verify_code = realEscape($_SESSION['verify_code']);
    $uid = realEscape($_SESSION['user_id']);

    $qry = "SELECT * from register where id = '$uid' and binary verify_code = '$verify_code' ";
    $res = mysqli_query($conn, $qry);
    if ($res) {
        $res = mysqli_fetch_assoc($res);
        if (!isset($res['id'])) {
            unset($_SESSION['user_id']);
            echo '';
            die('Error U04<br><a href="login.php">Login again</a>');
        } else {
            $uid = realEscape($_SESSION['user_id']);
            $status = 'Login';

            if (!setOnlineStatus($uid, $status)) {
                die('error ON4');
            }
        }
    } else {
        unset($_SESSION['user_id']);
        echo '';
        die('Info. Mismatch<br><a href="login.php">Login again</a>');
    }
}
