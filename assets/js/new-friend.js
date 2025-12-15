$(document).ready(function () {
    $(document).on("click", ".send-friend-request", function () {
        let id = $(this).data("id");
        let th = $(this);

        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                send_friend_request: id,
            },
            beforeSend: function () {
                th.attr("disabled", true).css("cursor", "disabled");
            },
            success: function (data) {
                console.log(data);
                th.attr("disabled", false).css("cursor", "pointer");
                let res = $.parseJSON(data);
                if (res.error) {
                    Swal.fire("ERROR", res.error, "error");
                } else if (res.success) {
                    th.html('<i class="fas fa-user-check click-effect"></i> Sent').removeClass("send-friend-request").addClass("remove-friend-request");
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong. Please try again later or contact admin.", "error");
            }
        })
    });

    $(document).on("click", ".accept-friend_request", function () {
        let id = $(this).data("id");
        let th = $(this);
        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                accept_friend_request: id,
            },
            beforeSend: function () {
                th.attr("disabled", true).css("cursor", "disabled");
            },
            success: function (data) {
                console.log(data);
                th.attr("disabled", false).css("cursor", "pointer");
                let res = $.parseJSON(data);
                if (res.error) {
                    Swal.fire("ERROR", res.error, "error");
                } else if (res.success) {
                    th.html('Accepted').removeClass("accept-friend_request");
                    setTimeout(() => {
                        th.parent().parent().parent().fadeOut(1000);
                    }, 2000);
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong. Please try again later or contact admin.", "error");
            }
        })
    });

    $(document).on("click", ".deny-friend-request", function () {
        let id = $(this).data("id");
        let th = $(this);
        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                deny_friend_request: id,
            },
            beforeSend: function () {
                th.attr("disabled", true).css("cursor", "disabled");
            },
            success: function (data) {
                th.attr("disabled", false).css("cursor", "pointer");
                let res = $.parseJSON(data);
                if (res.error) {
                    Swal.fire("ERROR", res.error, "error");
                } else if (res.success) {
                    th.html('Denied').removeClass("deny-friend-request");
                    setTimeout(() => {
                        th.parent().parent().parent().fadeOut(1000);
                    }, 2000);
                }
            },
            error: function () {
                Swal.fire("Error", "Something went wrong. Please try again later or contact admin.", "error");
            }
        })
    });


    $(document).on("click", ".block-user-btn", function () {
        let target = $(this).data("target");
        let th = $(this);
        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                blockuser: target,
            },
            beforeSend: function () {
                th.html("Blocking...");
            },
            success: function (data) {
                let res;
                try {
                    res = $.parseJSON(data);
                } catch (error) {
                    console.log(error + "\n\nData:\n" + data);
                    Swal.fire("Blocking failed", "", "error");
                    return;
                }

                if (res.success) {
                    th.html(res.now);
                } else {
                    console.log(data);
                    Swal.fire("Blocking failed", "Please try again", "error");
                }
            },
            error: function () {
                Swal.fire("Something went wrong", "", "error");
                console.log("error");
            }
        })
    });

})