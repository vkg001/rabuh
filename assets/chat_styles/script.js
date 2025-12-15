$(document).ready(function () {

    let typing_tracker = false;
    let typing_status_set = false;
    let search_bar_visible = false;
    let active_chat_id = false;
    let chat_refresh_token = false;
    let separator_status = false;


    // CHAT HANDLER
    $(document).on("click", ".chat-list-item", function () {
        let initializer_active = false;
        let item = $(this).data("msg-container");
        let loader = $(this).data("msg-loader");
        let th = $(this);
        let new_msg_loader = false;
        let self = $(this).data("self");
        let is_response_handled = true;

        if (separator_status) {
            $(".new-messages-separator").fadeOut();
            clearTimeout(separator_status);
        }

        active_chat_id = true;

        if (search_bar_visible) {
            $(".search-friend").trigger("click");
        }

        typing_status_set = false;

        if (th.data("init") == '0') {
            initializer_active = true;
        } else {
            new_msg_loader = true;
        }

        chat_refresh_token = setInterval(() => {
            if (is_response_handled) {
                if (active_chat_id) {
                    is_response_handled = false;
                    $.ajax({
                        url: "home_helper",
                        method: "POST",
                        data: {
                            get_chat: true,
                            qr: self,
                        },
                        beforeSend: function () {
                            if (new_msg_loader) {
                                $(th.data("refresher")).show();
                            }
                        },
                        success: function (data) {
                            let res;
                            let new_content_trigger = false;
                            is_response_handled = true;
                            try {
                                res = $.parseJSON(data);
                            } catch (error) {
                                console.log(data);
                                clearInterval(chat_refresh_token);
                                return;
                            }

                            if (res.invalid_qr) {
                                clearInterval(chat_refresh_token);
                                Swal.fire("Session Expired", "", "error");
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                                return;
                            }

                            if (res.blocked) {
                                $(th.data("msg-wrapper")).parent().parent().children(".input").addClass("d-none");
                                $(th.data("msg-wrapper")).parent().parent().children(".input-blocked").removeClass("d-none");
                            } else {
                                $(th.data("msg-wrapper")).parent().parent().children(".input").removeClass("d-none");
                                $(th.data("msg-wrapper")).parent().parent().children(".input-blocked").addClass("d-none");
                            }

                            if (res.success) {
                                if (initializer_active) {
                                    $(th.data("msg-wrapper")).html(res.all_messages);
                                    new_content_trigger = true;
                                    th.data("init", "1");
                                    initializer_active = false;
                                } else if (new_msg_loader) {
                                    new_msg_loader = false;
                                    $(th.data("msg-wrapper")).append(res.new_messages);

                                    $(th.data("refresher")).hide();
                                    $(th.data("refresher") + "-msg").show();
                                    new_content_trigger = true;

                                    setTimeout(() => {
                                        $(th.data("refresher") + "-msg").fadeOut();
                                    }, 2000);
                                } else {
                                    if (res.new_messages.trim() != '') {
                                        new_content_trigger = true;
                                        $(th.data("msg-wrapper")).append(res.new_messages);
                                    }
                                }

                                if (res.new_msg_separator) {
                                    separator_status = setTimeout(() => {
                                        $(".new-messages-separator").fadeOut();
                                    }, 60000);
                                }

                                if (new_content_trigger) {
                                    setTimeout(() => {
                                        let element = document.getElementById(item);
                                        element.scrollTop = element.scrollHeight;
                                        $("#" + loader).fadeOut();
                                        $("#" + item).css("opacity", "1");
                                    }, 500);
                                }

                            } else {
                                Swal.fire("Something went wrong.", "", "error");
                                clearInterval(chat_refresh_token);
                                console.log(data);
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            }
                        }
                    })
                } else {
                    console.log("Chat change");
                    clearInterval(chat_refresh_token);
                }
            }
        }, 500);

    });


    // CHAT HELPER
    $(document).on("click", ".close-chat-btn", function () {
        active_chat_id = false;
    })



    // MESSAGE SENDER
    let msg_controller = 0;
    $(document).on("keydown", ".new-msg-input-box", async function (e) {
        let inner = msg_controller;
        let th = $(this);
        if (e.keyCode === 13) {

            // checking nudes
            if (th.data("media") && $(th.data("media"))[0].files[0]) {
                const res = await isNormalImage($(th.data("media"))[0].files[0], true);
                if (res === false) {
                    Swal.fire({
                        icon: "error",
                        title: "Inappropriate Content",
                        text: "Rabuh identified uploaded image as explicit content. Please contact us if its a mistake.",
                    });
                    
                    return;
                } else{
                    form.append("media", $(th.data("media"))[0].files[0]);
                    $(".send-msg-from-modal").trigger("click");
                }
            }



            let item = th.data("container");
            let mfor = th.data("for");
            let msg = th.val();


            let tile = '<div class="message parker pending-msg" id="msg-controller-' + msg_controller + '">' + msg + '</div>';
            msg_controller++;

            if (separator_status) {
                $(".new-messages-separator").fadeOut();
                clearTimeout(separator_status);
            }

            let form = new FormData();
            form.append("send_message", mfor);
            form.append("message", msg);

            if (th.data("modal")) {
                $(th.data("modal")).trigger("click");
            }

            $.ajax({
                url: "home_helper",
                method: "POST",
                processData: false,
                contentType: false,
                data: form,
                beforeSend: function () {
                    $("#" + item).append(tile);
                    th.val('');
                    let element = document.getElementById(item);
                    element.scrollTop = element.scrollHeight;
                },
                success: function (data) {
                    let res;
                    try {
                        res = $.parseJSON(data);
                    } catch (error) {
                        console.log(data);
                        $("#msg-controller-" + inner).addClass("bg-danger").removeClass("pending-msg");
                        Swal.fire("Session Expired");
                        return;
                    }

                    if (res.blocked) {
                        th.parent().parent().children("input-blocked").removeClass("d-none");
                        th.parent().addClass("d-none");
                    } else {
                        th.parent().parent().children("input-blocked").addClass("d-none");
                        th.parent().removeClass("d-none");
                    }

                    if (res.success) {
                        $("#msg-controller-" + inner).remove();
                    } else {
                        $("#msg-controller-" + inner).addClass("bg-danger").removeClass("pending-msg");
                    }
                },
                error: function () {
                    Swal.fire("Session Expired");
                    console.log("error");
                }
            })
        }
    });


    // UI COMPONENT
    $(document).on("click", ".search-friend", function () {
        if ($("#search-bar-chat").hasClass("search-box-friend-anim-show")) {
            $("#search-bar-chat").removeClass("search-box-friend-anim-show").addClass("search-box-friend-anim-collapse");
            search_bar_visible = false;
        } else {
            $("#search-bar-chat").addClass("search-box-friend-anim-show").removeClass("search-box-friend-anim-collapse").trigger("focus");
            search_bar_visible = true;
        }
    });


    // INDEPENDENT SEARCH USING HTML ONLY
    let original_chat_avatars = $("#chat-list").html();
    $(document).on("keyup", "#search-bar-chat", function () {
        let keyword = $(this).val().trim().toLowerCase();
        if (keyword.length < 3) {
            $("#chat-list").html(original_chat_avatars);
            return;
        }
        $("#chat-list").html(original_chat_avatars);
        let new_results = "";
        let user_found = false;
        $(".chat-avatar-names").each(function () {
            let parent = $(this).data("parent");
            let target = $(this).data("fullname").toLowerCase();
            let th = $(this);

            if (target.search(keyword) > -1) {
                user_found = true;
                new_results += "<span id='" + parent.substr(1, parent.length) + "'>" + $(parent).html() + "</span>";
            }
        });
        if (!user_found) {
            new_results = "<h4>No user found</h4>";
        }
        $("#chat-list").html(new_results);
    });


    // CHAT AVATAR REFRESHER
    let is_response_processed = true;
    let typing_controller = [];

    let chat_refresher = setInterval(() => {
        if (is_response_processed) {
            is_response_processed = false;
            $.ajax({
                url: "home_helper",
                method: "POST",
                data: {
                    refresh_chat: true,
                },
                success: function (data) {
                    // clearInterval(chat_refresher);
                    let res;
                    is_response_processed = true;
                    try {
                        res = $.parseJSON(data);
                    } catch (error) {
                        console.log(error);
                        console.log(data);
                        clearInterval(chat_refresher);
                        Swal.fire("Session Expired", "", "error");
                        // setTimeout(() => {
                        //    location.reload();
                        // }, 2000);
                        return;
                    }

                    if (res.success) {
                        if (res.chat_list) {
                            let chat_list = res.chat_list;
                            if (chat_list.have_update !== false && chat_list.data) {
                                original_chat_avatars = chat_list.data;
                                $("#chat-list").html(chat_list.data);
                                if (typing_controller) {
                                    clearTimeout(typing_controller);
                                }

                                for (val in chat_list.typing) {
                                    let element = document.getElementById(chat_list.message_box[val]);
                                    val = chat_list.typing[val];

                                    if (typing_controller[val]) {
                                        clearTimeout(typing_controller[val]);
                                    }

                                    typing_controller[val] = setTimeout(() => {
                                        $(val).fadeOut();
                                    }, 1100);

                                    $(val).fadeIn();
                                    element.scrollTop = element.scrollHeight;
                                }
                            }
                        }


                        if (res.last_seens) {
                            for (let key in res.last_seens) {
                                let id = "";
                                for (let id_t in res.last_seens[key]) {
                                    if (id == '') {
                                        id = id_t;
                                    }
                                }
                                $(id).html(res.last_seens[key][id]);
                            }
                        }
                    }
                }
            });
        }
    }, 1000);


    // TYPING TEXT ON CHAT AVATAR
    $(document).on("input", ".new-msg-input-box", function () {
        if (typing_tracker) {
            clearTimeout(typing_tracker);
        }

        let target = $(this).data("for");

        typing_tracker = setTimeout(() => {
            typing_status_set = false;
            $.ajax({
                url: "home_helper",
                method: "POST",
                data: {
                    stop_typing: true,
                },
            });
        }, 3000);

        if (!typing_status_set) {
            typing_status_set = true;
            $.ajax({
                url: "home_helper",
                method: "POST",
                data: {
                    start_typing: target,
                },
            })
        }
    });




    $(".modal-content-temp").each(function () {
        $("#modal-content-dump").append($(this).html());
        $(this).html("");
    });

    $(document).on("change", ".preview-image", async function (event) {
        let th = $(this);
        let allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];
        let preview_on = th.data("preview-on");
        let self = th.data("self");
        let curr_ext = $(this).val().split(".").pop().toLowerCase();

        if ($.inArray(curr_ext, allowed_ext) !== false) {

            const res = await isNormalImage(event);
            if (res === false) {
                Swal.fire({
                    icon: "error",
                    title: "Inappropriate Content",
                    text: "Rabuh identified uploaded image as explicit content. Please contact us if its a mistake.",
                });
                return;
            } else {
                let src = res;
                th.parent().hide();
                th.parent().parent().children(".send-media-wrapper").removeClass("d-none");
                $(preview_on).attr("src", src);
            }

        }
    });

    $(document).on("click", ".send-msg-from-modal", function () {
        let target = $($(this).data("msg"));
        $($(this).data("media")).val("");
        $($(this).data("media")).parent().show();

        $("#media-preview-" + $(this).data("self")).attr("src", "");
        $("#media-preview-" + $(this).data("self")).parent().addClass("d-none");
    });

    $(document).on("click", ".triple-dots-menu", function () {
        let th = $(this).siblings(".triple-dots-menu-content");
        if (th.hasClass("d-none")) {
            th.removeClass("d-none");
        } else {
            th.addClass("d-none");
        }
    });

});