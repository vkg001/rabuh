$(document).ready(function () {

    $("#searchPeople").on('keyup', function (e) {
        if (e.which == 13) {
            $("#search_result").fadeOut('fast');
            return;
        }

        if ($(this).val().length < 2) {
            $("#search_result").fadeOut('fast');
            $("#search_result").html('');
            return;
        }

        var keyword = $(this).val();
        $.ajax({
            url: "signup_helper",
            method: "post",
            data: {
                searchKeyword: keyword,
            },
            success: function (data) {
                // alert(data);
                $("#search_result").html(data);
                $("#search_result").fadeIn('fast');
            },
            error: function (data) {
                alert("Error: " + data);
            }
        });
    });

    let fadeOutTimer = false;
    $(document).on('click', ".data_item", function () {
        let val = $(this).data('value');
        if (fadeOutTimer) {
            clearTimeout(fadeOutTimer);
        }
        $("#searchPeople").val(val);
        $("#searchPeople").trigger('focus').trigger("keyup");
    });

    $(document).on('blur', '#searchPeople', function () {
        fadeOutTimer = setTimeout(() => {
            $("#search_result").fadeOut('fast');
        }, 300);
    });

    $(document).on('focus', '#searchPeople', function () {
        if ($("#search_result").html().trim() != "") {
            $("#search_result").fadeIn('fast');
        }
    });


    $(document).on('click', ".notification-btn", function () {
        let target = $(".notification-container");
        if (target.hasClass("notification-anim-show")) {
            target.removeClass("notification-anim-show").addClass("notification-anim-hide");
            $(".notification-backdrop").fadeOut();
        } else {
            $(".notification-backdrop").fadeIn();
            target.removeClass("notification-anim-hide").addClass("notification-anim-show");
        }
    });

    $(document).on("click", ".notification-backdrop", function () {
        $(".notification-btn").trigger("click");
    });


    $(document).on('click', ".mark-read-btn", function () {
        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                mark_all_notif_read: true,
            },
            beforeSend: function () {
                $(".new-notification").removeClass("new-notification");
                $(".notification-mark").fadeOut().html("0");
            },
            success: function (data) {
                let res = $.parseJSON(data);
                if (!res.success) {
                    Swal.fire("Error", "Error Code: " + res.error, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong.", "error");
            }
        })
    });

    $(document).on('click', ".notification-tile", function () {
        let th = $(this);
        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                mark_notif_read: th.data("id"),
            },
            beforeSend: function () {
                let count = parseInt($(".notification-mark").html());
                $(".notification-mark").html(count - 1).hide();
                if (count == 1) {
                    $(".notification-mark").hide();
                }
                th.removeClass("new-notification");
            },
            success: function (data) {
                let res = $.parseJSON(data);
                if (!res.success) {
                    Swal.fire("Error", "Error Code: " + res.error, "error");
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong.", "error");
            }
        })
    });



    let notification_checker = false;
    let notif_request_send = false;

    notification_checker = setInterval(() => {
        if (!notif_request_send) {
            notif_request_send = true;
            $.ajax({
                url: "home_helper",
                method: "POST",
                data: {
                    get_new_notification: true,
                },
                success: function (data) {
                    notif_request_send = false;
                    try {
                        let res = $.parseJSON(data);
                        if (res.status == 200 && res.data != '') {
                            $("#notification-body-wrapper").prepend(res.data);
                            if (parseInt(res.total_unread) > 0) {
                                $(".notification-mark").html(parseInt(res.total_unread) + parseInt($(".notification-mark").html())).show();
                            }
                        }
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        }
    }, 2000);
})