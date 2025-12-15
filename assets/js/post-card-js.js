$(document).ready(function () {
    $(document).on('click', ".post-like-btn", function () {
        let btn = $(this);
        let icon = btn.children(".fa-thumbs-up");
        let like_text = btn.children(".like-text");
        if (btn.data("liked") == 0) {
            btn.addClass("green-text");
            like_text.html("Liked");
            icon.addClass("fas").removeClass("far").addClass("show-like-animation");
        } else {
            btn.removeClass("green-text");
            like_text.html("Like");
            icon.addClass("far").removeClass("fas").removeClass("show-like-animation");
        }
        btn.data("liked", 1 - btn.data("liked"));
    });

    $(document).on('dblclick', ".double-tap-like", function () {
        let like_btn = $(this).siblings(".like-btn-popup");
        like_btn.addClass("show-like-animation");
        like_btn.css("z-index", "1");
        setTimeout(function () {
            like_btn.addClass("fas").removeClass("far");
        }, 500);
        setTimeout(() => {
            like_btn.css("z-index", "-1");
            like_btn.removeClass("show-like-animation").removeClass("fas").addClass("far");
        }, 1500);

        $(this).parent().siblings(".col-sm-12").children(".post-action-row").children(".post-like-btn").trigger("click");
    });


    let zoom_timeout = false;
    $(document).on("mouseenter", ".zoom-on-hover", function () {
        if (zoom_timeout) clearTimeout(zoom_timeout);
        let target = $(this);
        zoom_timeout = setTimeout(function () {
            target.addClass("profile-zoom");
        }, 1000);
    });

    $(document).on("mouseleave", ".zoom-on-hover", function () {
        if (zoom_timeout) clearTimeout(zoom_timeout);
        $(this).removeClass("profile-zoom");
    });

    $(document).on('click', "[data-focus-on]", function () {
        let target = $(this).data("focus-on");
        $(target).trigger("focus");
    });

    $(document).on('click', ".post-like-btn", function () {
        let qr = $(this).data("qr");
        let st = $(this).data("liked");


        if (st == '1') {
            $($(this).data("up-c")).html(parseInt($($(this).data("up-c")).html()) + 1);
        } else {
            $($(this).data("up-c")).html(parseInt($($(this).data("up-c")).html()) - 1);
        }


        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                likePost: qr,
            },
            success: function (data) {
                let res = $.parseJSON(data);
                if (res.error) {
                    console.log(data);
                    setTimeout(() => {
                        location.reload();
                    }, 3000);
                }
            },
            error: function () {
                console.log("Like failure");
                setTimeout(() => {
                    location.reload();
                }, 3000);
            }
        });
    });

    $(document).on('click', '.post-comment-btn', function () {
        let cmt_wrapper = $(this).parent().parent().children(".top-comments");
        let th = $(this).parent().children(".comment-box-wrapper").children(".comment-box");
        let cmt = th.val();
        let qr = th.data("qr");
        let ui = $("#comment-card-ui").html();

        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                post_comment: cmt,
                qr: qr,
            },
            beforeSend: function () {
                cmt_wrapper.append('<div class="comment_loader" style="color: #21c87a;">Adding Comment please wait ... &nbsp;&nbsp;&nbsp;<div class="spinner-border" style="height: 1.2rem; width: 1.2rem;"></div></div>');
            },
            success: function (data) {
                let res = $.parseJSON(data);
                $(".comment_loader").fadeOut();
                if (res.success) {
                    th.val("");
                    cmt_wrapper.append(ui.replace("//COMMENT_TEXT//", cmt));
                } else {
                    cmt_wrapper.append('<div class="comment_loader" style="color: red;">Some error occured while adding comment.</div>');
                }
            },
            error: function () {
                Swal.fire("Something went wrong", "", "error");
            }
        })
    });

    $(document).on("mouseenter", ".post-wrapper", function() {
        console.log("Post seen trigger...");
        let th = $(this);
        if (th.data("seen") == '1') {
            return;
        }

        let self = th.data("self");
        $.ajax({
            url: "home_helper",
            method: "POST",
            data: {
                post_view: self,
            },
            beforeSend: function() {
                th.data("seen", 1);

            },
            success: function (data) {
                console.log({"Seen response" : data});
                let res;
                try {
                    res = $.parseJSON(data);
                } catch (error) {
                    console.log(error);
                    console.log(data);
                    Swal.fire("Session Expired", "", "error");
                    return;
                }

                if (!res.success) {
                    console.log(data);
                    Swal.fire("Session Expired", "", "error");
                }
            },
            error: function () {
                Swal.fire("Something went wrong", "", "error");
                console.log("error");
            }
        });
    });
});