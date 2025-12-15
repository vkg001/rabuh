$(document).ready(function () {

    $(document).on('click', '#navigator', function () {
        var target = document.getElementById('top-header').getBoundingClientRect().top;
        window.scrollTo(0, target);
    });

    $(window).on("scroll", function () {
        $("#story-upload-progress").css("margin-top", window.scrollY + "px");
    });

    $("#upload-story-input").on("change", async function (event) {
        let allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];
        let th = $(this);
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
                let img_src = res;
                let img = $("#story-image-preview");
                th.parent().removeClass("d-flex").addClass("d-none");
                img.attr("src", img_src);
                img.parent().removeClass("d-none").addClass("d-flex");
            }

        } else {
            Swal.fire("Error", "Invalid File Format", "error");
        }
    });

    $(document).on("click", ".reset-story-image-upload", function () {
        let img = $("#story-image-preview");
        img.attr("src", "#");
        img.parent().removeClass("d-flex").addClass("d-none");
        let th = $("#upload-story-input");
        th.val("");
        th.parent().removeClass("d-none").addClass("d-flex");
    });

    $(document).on("click", ".upload-story", function () {
        let form = new FormData();
        if (!$("#upload-story-input")[0].files[0]) {
            Swal.fire("Image not selected", "", "error");
            return;
        }
        form.append("image", $("#upload-story-input")[0].files[0]);
        form.append("upload_story", true);
        $.ajax({
            url: "home_helper",
            method: "POST",
            contentType: false,
            processData: false,
            data: form,
            beforeSend: function () {
                $("#story-upload-progress").removeClass("d-none");
                $("body").css("overflow", "hidden");
            },
            success: function (data) {
                // console.log(data);
                $("#story-upload-progress").addClass("d-none");
                $("body").css("overflow", "auto").css("overflow-x", "clip");
                let res = $.parseJSON(data);
                if (res.success) {
                    $(".reset-story-image-upload").trigger("click");
                    Swal.fire(res.success, "", "success");
                    let current_data = $("#story-section-wrapper").html();
                    let new_story = $("#story-card-ui").html();
                    new_story = new_story.replace("//STORY_LINK//", res.story_link);
                    $("#story-section-wrapper").html("").append(new_story + current_data);
                } else {
                    Swal.fire(res.error, "", "error");
                }
            },
            error: function () {
                $("#story-upload-progress").addClass("d-none");
                $("body").css("overflow", "auto").css("overflow-x", "clip");
                Swal.fire("Error", "Something went wrong.", "error");
            }
        })
    });

    $(document).on("click", ".update-story-view", function story_views() {
        setTimeout(() => {
            let qr = $(".active[data-story-container]").data("story-container");
            $.ajax({
                url: "home_helper",
                method: "POST",
                data: {
                    update_story_view: qr,
                },
                success: function (data) {
                    let res = $.parseJSON(data);
                    if (res.error) {
                        Swal.fire("Story handler failed.", "Page will be auto-refreshed in 5 seconds", "error");
                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                    }
                }
            })
        }, 1000);
    });

    $('.carousel').carousel({
        interval: false,
    });

    $(document).on("click", "[data-activate-carousal]", function () {
        let target = $(this).data("activate-carousal");
        $("[data-story-container]").removeClass("active");
        $("[data-story-container='" + target + "']").addClass("active");
    });
})