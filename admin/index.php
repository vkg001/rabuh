<?php
require "../config/connection.php";
if (isset($_SESSION['admin_id'])) {
    header("Location: user");
}
unset($_SESSION['ADMIN_PASSWORD']);
?>
<!DOCTYPE html>
<html lang="en">

<head>


    <meta charset="utf-8" />
    <title>Unikit - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo $icon ?>">

    <link href="../assets/app_styles/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/app_styles/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="../assets/app_styles/app.min.css" rel="stylesheet" type="text/css" />

</head>

<body id="body" class="auth-page" style="background-image: url('../assets/img/p-1.png'); background-size: cover; background-position: center center; filter: hue-rotate(270deg);">
    <!-- Log In page -->
    <div class="container-md">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 auth-header-box">
                                    <div class="text-center p-3">
                                        <h4 class="mt-3 mb-1 fw-semibold text-white font-18">
                                            Admin Panel
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <form class="my-4" action="" id="loginform">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <div class="form-group mb-2">
                                                    <label class="form-label" for="email">E-Mail</label>
                                                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email">
                                                </div>
                                            </div>
                                            <div class="col-sm-2 my-auto" style="bottom: -0.8rem;">
                                                <button class="btn btn-xs btn-primary" id="send-password-btn">Send</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
                                        </div>
                                        <div class="form-group row mt-3">
                                            <div class="col-sm-6">
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                <a href="auth-recover-pw.html" class="text-muted font-13"><i class="dripicons-lock"></i> Forgot password?</a>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" id="login-btn" type="button">Log In <i class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script src="../plugins/jquery/jquery.min.js"></script>
    <script src="../assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            $("#loginform").submit(function(e) {
                e.preventDefault();
            });

            $("#login-btn").on("click", function() {
                let form = new FormData(document.getElementById('loginform'));
                form.append("login_admin", true);
                let login_btn = $(this);

                $.ajax({
                    url: "user_helper",
                    method: "POST",
                    data: form,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        login_btn.html("Logging in...").attr("disabled", true);
                    },
                    success: function(data) {
                        let res;
                        try {
                            res = $.parseJSON(data);
                        } catch (error) {
                            console.log(error);
                            console.log(data);
                            alert("error");
                            return;
                        }

                        if (res.status == 200) {
                            location.href = "user";
                            return;
                        }

                        if (res.error) {
                            alert(res.error);
                        }
                    }
                })
            });

            $("#send-password-btn").on("click", function() {
                if ($("#email").val() == '') {
                    return;
                }

                let th = $(this);
                $.ajax({
                    url: "user_helper",
                    method: "POST",
                    data: {
                        send_password: $("#email").val(),
                    },
                    beforeSend: function() {
                        th.html("Sending").attr("disabled", true);
                    },
                    success: function(data) {
                        let res;
                        try {
                            res = $.parseJSON(data);
                        } catch (e) {
                            console.log(e);
                            alert("error");
                            console.log(data);
                            return;
                        }

                        if (res.status == 200) {
                            th.html("Send").attr("disabled", false);
                        } else {
                            alert("Error");
                        }
                    },
                    error: function() {
                        alert("ERROR");
                    }
                })
            });
        });
    </script>

</body>


<!-- /auth-login.html  , Tue, 25 Jan 2022 08:04:29 GMT -->

</html>