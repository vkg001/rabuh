<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
$curr_date = date('Y-m-d H:i:s');

$site_name = 'Rabuh';
$icon = 'https://res.cloudinary.com/dza7mzhl1/image/upload/v1687181052/pngwing.com_fchhlj.png';
$web_desc = 'Made to connect each other';
$this_site_link = 'http://localhost/Mini%20Project';
$temporary_image = 'https://res.cloudinary.com/dza7mzhl1/image/upload/v1677327966/loq2vu7zamjydkvextit.png';




define("PROFILE_PLACEHOLDER", "https://res.cloudinary.com/dza7mzhl1/image/upload/v1687623868/gmey1zqsxqzpmeashnvr.png");
define("COVER_PHOTO_PLACEHOLDER", "https://res.cloudinary.com/dza7mzhl1/image/upload/v1686837273/pexels-james-wheeler-417074_rvm1zq.jpg");
define("WARNING_IMAGE", "https://res.cloudinary.com/dza7mzhl1/image/upload/v1688133822/pngwing.com_w30c4k.png");
define("ADMIN_EMAIL", "vkg360.vikas@gmail.com");

define("HOST", "localhost");
define("USERNAME", "root");
define("PASSWORD", "");
define("DATABASE", "rabuh");
if (isset($_SESSION["user_id"])) {
    define("USER_ID", $_SESSION['user_id']);
} else {
    define("USER_ID", -1);
}


$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
if (!$conn) {
    die("Couldn't connect to the server'");
}

function realEscape(String $string): String
{
    global $conn;
    return mysqli_escape_string($conn, $string);
}

function errLog(String $error, String $qry)
{
    global $curr_date;
    $handle = fopen('error.txt', 'a');
    $data = "\n\n[" . $curr_date . "] " . " --- " . $error . " --- " . $qry;
    fwrite($handle, $data);
}

function agoTimeInMsgTile($date)
{
    $time = strtotime($date);
    $diff = time() - $time;

    $response = "";
    if ($diff < (60 * 60 * 24)) {
        $response = "Today at " . date("H:i", $time);
    } else if ($diff < (60 * 60 * 24 * 2)) {
        $response = "Yesterday at " . date("H:i", $time);
    } else if ($diff < (60 * 60 * 24 * 7)) {
        $response = date("l H:i", $time);
    } else {
        $response = date("d-F-Y", $time);
    }

    return $response;
}


function agoTime($time, $chat = false, $online = false)
{
    $time = strtotime($time);
    $time_difference = time() - $time;

    if ($chat) {
        $res = "";
        $flag = false;
        if ($time_difference < 60) {
            $flag = true;
            $res = "Just Now";
            if ($online) {
                $res = "Online";
            }
        } else if ($time_difference < (60 * 60)) {
            $res = round($time_difference / (60), 0) . " Min";
        } else if ($time_difference < (60 * 60 * 24)) {
            $res = round($time_difference / (60 * 60), 0) . " Hr";
        } else if ($time_difference < (60 * 60 * 24 * 2)) {
            $flag = true;
            $res = "Yesterday";
        } else if ($time_difference < (60 * 60 * 24 * 365)) {
            $flag = true;
            $res = date("d-M", ($time));
        } else {
            $flag = true;
            $res = date("d-M-y", ($time));
        }

        if ($online  &&  !$flag) {
            $res .= " ago";
        }

        return $res;
    }


    if ($time_difference < 1) {
        return '1 Second';
    }
    $condition = array(
        12 * 30 * 24 * 60 * 60  =>  'Year',
        30 * 24 * 60 * 60       =>  'Month',
        24 * 60 * 60            =>  'Day',
        60 * 60                 =>  'Hour',
        60                      =>  'Minute',
        1                       =>  'Second'
    );

    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;

        if ($d >= 1) {
            $t = round($d);
            return $t . ' ' . $str . ($t > 1 ? 's' : '');
        }
    }
}

function strShort($str, $len = 20)
{
    $str = strip_tags($str);
    if (strlen($str) > $len + 3) {
        return substr($str, 0, $len) . "...";
    }
    return substr($str, 0, $len);
}

function DFENC($str)
{
    return urlencode(base64_encode(base64_encode($str)));
}

function DFDEC($str)
{
    return urldecode(base64_decode(base64_decode($str)));
}

function validateFile($filename, $extension = array("jpg", "jpeg", "png", "gif"))
{
    $ext = explode(".", $filename);
    $ext = strtolower($ext[count($ext) - 1]);

    if (!in_array($ext, $extension)) {
        errLog($ext, $filename);
        return array("ERROR" => "Invalid file format. Only " . implode(",", $extension) . " types of files are allowed");
    }
    return array("SUCCESS" => true);
}

class DB
{
    private $conn;
    private $curr_date;
    public $qry_frame;

    public function realEscape(String $string): String
    {
        return mysqli_escape_string($this->conn, $string);
    }

    function __construct()
    {
        $this->curr_date = date("Y-m-d H:i:s");
        $this->conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
        if (!$this->conn) {
            die(json_encode(array("error" => "C107")));
        }
    }

    function __destruct()
    {
        mysqli_close($this->conn);
    }

    function errlog(String $error, String $qry)
    {
        $handle = fopen('error.txt', 'a');
        $data = "\n\n[" . $this->curr_date . "] " . " --- " . $error . " --- " . $qry;
        fwrite($handle, $data);
    }

    public function checkRelationship(int $user1, int $user2 = USER_ID): int
    {
        $data = $this->select("friend_list", "*", "((sent_by = '$user1' AND sent_to = '$user2') OR (sent_by = '$user2' AND sent_to = '$user1')) ");
        $result = 0;
        if (isset($data[0]['id'])) {
            $result = $data[0]['status'];
        }

        return $result;
    }

    public function insert(String $table, array $columns_n_value)
    {
        $qry = "INSERT INTO " . $this->realEscape($table) . " ( ";
        $vals = " VALUES ( ";
        foreach ($columns_n_value as $col => $val) {
            $qry .= "`" . $col . "`, ";
            $vals .= "'" . $this->realEscape($val) . "', ";
        }

        $this->qry_frame = $qry .= " created_date ) " . $vals . " '" . $this->curr_date . "' ) ";
        if (mysqli_query($this->conn, $qry)) {
            return mysqli_insert_id($this->conn);
        } else {
            $this->errlog(mysqli_error($this->conn), $qry);
            return false;
        }
    }

    public function select($table, $column, $condition, $join = "")
    {
        $this->qry_frame = $qry = "SELECT $column FROM $table $join WHERE $condition ";
        $res = mysqli_query($this->conn, $qry);
        if (!$res) {
            $this->errlog(mysqli_error($this->conn), $qry);
            return false;
        }
        $data = mysqli_fetch_all($res, MYSQLI_ASSOC);
        foreach ($data as $key => $row) {
            foreach ($row as $k => $tile) {
                $data[$key][$k] = htmlspecialchars($tile);
                if ($k == 'profile_pic'  &&  $tile == '') {
                    $data[$key][$k] = PROFILE_PLACEHOLDER;
                }

                if ($k == 'cover_photo'  &&  $tile == '') {
                    $data[$key][$k] = COVER_PHOTO_PLACEHOLDER;
                }

                if ($k == 'name'  &&  isset($row['is_verified'])  &&  $row['is_verified'] == 1) {
                    $data[$key][$k] .= '<i class="far fa-check-circle" style="margin-left: 0.3rem; color: #21c87a;"></i>' ;
                    $data[$key]['only_name'] = htmlspecialchars($tile) ;
                }

                if ($k == 'name') {
                    $data[$key]['only_name'] = htmlspecialchars($tile) ;
                }
            }
        }

        return $data;
    }

    public function update($table, $col_n_val, $condition)
    {
        $this->qry_frame = $qry = "UPDATE $table SET $col_n_val WHERE $condition ";
        if (mysqli_query($this->conn, $qry)) {
            return true;
        } else {
            $this->errlog(mysqli_error($this->conn), $qry);
            return false;
        }
    }

    public function delete($table, $condition)
    {
        $this->qry_frame = $qry = "DELETE FROM $table WHERE $condition ";
        if (mysqli_query($this->conn, $qry)) {
            return true;
        } else {
            $this->errlog(mysqli_error($this->conn), $qry);
            return false;
        }
    }

    public function create_notification($text, $for, $icon, $link, $image, $notification_type = 0)
    {
        $data = array(
            "text" => $this->realEscape($text),
            "user_id" => (int)($for),
            "icon" => $icon,
            "link" => $link,
            "image" => $image,
            "notification_type" => $notification_type,
        );
        return $this->insert("notification", $data);
    }

    function are_friends(Int $user1, Int $user2): bool
    {
        if ($user1 == $user2) {
            return true;
        }
        $fr = $this->select("friend_list", "*", "(sent_by = '" . $user1 . "' AND sent_to = '" . $user2 . "') OR (sent_to = '" . $user1 . "' AND sent_by = '" . $user2 . "') AND status = 1 ");
        if (isset($fr[0]['id'])) {
            return true;
        }

        return false;
    }
}

if (USER_ID > 0) {
    $db = new DB();
    $db->update("register", "last_online = '$curr_date' ", "id = " . USER_ID);
    $user_data = $db->select("register", "*", "id = " . USER_ID)[0];

    if ($user_data['status'] == "4") {
        session_destroy();
        require "suspended_account.php";
        die;
    }

    if ($user_data['profile_pic'] == '') {
        $user_data['profile_pic'] = PROFILE_PLACEHOLDER;
    }

    if ($user_data['cover_photo'] == '') {
        $user_data['cover_photo'] = COVER_PHOTO_PLACEHOLDER;
    }
}
