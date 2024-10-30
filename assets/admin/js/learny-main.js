let learnyMediaFrame;
var ajaxurl = data.adminAjaxUrl;
let spinner = '<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>';

let learnyProBannerTItle = data.proBannerTitle;
let learnyProBannerDescription = data.proBannerDescription;
let learnyProBannerDismiss = data.proBannerDismiss;

// show pro banner
let proBannerHtml = "<div id='learny-pro-banner' class='learny-pro-banner'><a class='learny-pro-banner-close' href='javascript:void(0)' onclick='learnyProBannerDismissal()' aria-label='" + learnyProBannerDismiss + "'> <i class='dashicons dashicons-dismiss'></i>" + learnyProBannerDismiss + "</a><div class='learny-pro-banner-content'><div class='learny-pro-banner-header'><h2>⭐️ " + learnyProBannerTItle + "!</h2><p><a href='https://codecanyon.net/item/learny-lms-wordpress-plugin/35682834' target='_blank'>" + learnyProBannerDescription + "</a></p></div></div></div>";

function learnyProBannerDismissal() {
    jQuery('#learny-pro-banner').fadeOut();
}

jQuery('#wpcontent').prepend(proBannerHtml);
/**
 * THIS FUNCTION IS BEING USED FOR WORDPRESS MEDIA UPLOADER
 * @param {*} mediaType 
 * @param {*} title 
 * @param {*} srcInputId 
 * @param {*} srcPreviewId 
 * @param {*} mediaId 
 * @param {*} multiple 
 * @returns 
 */
function learnyMediaUploader(mediaType, multiple, title, callback) {

    if (learnyMediaFrame) {
        learnyMediaFrame.open();
        return;
    }

    learnyMediaFrame = wp.media({
        title: title,
        multiple: multiple ? multiple : false,
        library: {
            type: mediaType,
        }
    });

    learnyMediaFrame.open();

    learnyMediaFrame.on('close', function () {

        var selection;
        var attachmentObj = {
            url: null,
            id: null
        }
        if (multiple) {
            selection = learnyMediaFrame.state().get('selection');
            selection.map(function (attachment) {
                attachment = attachment.toJSON();
                attachmentObj.url = attachmentObj.url ? attachmentObj.url + ',' + attachment.url : attachment.url;
                attachmentObj.id = attachmentObj.id ? attachmentObj.id + ',' + attachment.id : attachment.id;
            });
        } else {
            selection = learnyMediaFrame.state().get('selection').first().toJSON();
            attachmentObj.url = selection.url;
            attachmentObj.id = selection.id;
        }

        if (typeof callback === 'function') {
            callback(attachmentObj);
        }

    });
}

/**
 * Method for showing toastr notifications on different events
 * @param {*} message 
 * @param {*} type 
 */
function learnyNotify(message, type) {
    switch (type) {
        case "success":
            toastr.success(message, { timeOut: 60000 });
            break;
        case "warning":
            toastr.warning(message, { timeOut: 60000 });
            break;
        case "error":
            toastr.error(message, { timeOut: 60000 });
            break;
        default:
            toastr.info(message, { timeOut: 60000 });
            break;
    }
}

/**
 * AJAX CALL FOR A VIEW TYPE RESPONSE
 * @param {*} view_to_load 
 * @param {*} in_div 
 * @param {*} param1 
 * @param {*} param2 
 * @param {*} param3 
 * @param {*} param4 
 * @param {*} param5 
 */
function learnyMakeAjaxCall(
    view_to_load,
    in_div,
    param1,
    param2,
    param3,
    param4,
    param5,
    param6,
    param7,
    param8,
    param9,
    param10
) {
    // SHOW THE PLACEHOLDER
    jQuery(".learny-custom-modal-body").hide();
    jQuery("#learny-right-modal .learny-custom-modal-content").addClass(
        "learny-custom-modal-body-placeholder"
    );

    jQuery("#" + in_div).block({
        message: null,
        overlayCSS: {
            backgroundColor: "#f3f4f5",
        },
    });

    jQuery.post(
        ajaxurl,
        {
            action: "learny",
            page: view_to_load,
            response_div: in_div,
            task: "load_response",
            param1: param1,
            param2: param2,
            param3: param3,
            param4: param4,
            param5: param5,
            param6: param6,
            param7: param7,
            param8: param8,
            param9: param9,
            param10: param10,
        },
        function (response) {

            jQuery("#" + in_div).unblock();

            if (param2 == "append") jQuery("#" + in_div).append(response);

            else jQuery("#" + in_div).html(response);

            // HIDE THE PLACEHOLDER AND SHOW THE MODAL BODY
            setTimeout(function () {
                jQuery(".learny-custom-modal-body").show();
                jQuery("#learny-right-modal .learny-custom-modal-content").removeClass(
                    "learny-custom-modal-body-placeholder"
                );
            }, 500);

            // CHECK IF THE CURRENT PAGE HAS ANY PAGINATION LINK
            if (jQuery("a.page-numbers")[0]) {
                var niddle = "?filter";
                var currentUrl = window.location.href;
                var splittedUrl = currentUrl.split(niddle);

                var urlPrefixSplitted = splittedUrl[0].split('page/');

                var urlPrefix = urlPrefixSplitted[0] + 'page/2/' + niddle;
                var urlSuffix = splittedUrl[1];
                jQuery('a.page-numbers').attr('href', urlPrefix + urlSuffix);

            }
        }
    );
}



/**
 * METHOD FOR GENERIC AJAX CALLS
 * @param {*} task 
 * @param {*} blockingArea 
 * @param {*} param1 
 * @param {*} param2 
 * @param {*} param3 
 * @param {*} param4 
 * @param {*} param5 
 * @returns 
 */
function learnyMakeGenericAjaxCall(task, blockingArea, param1, param2, param3, param4, param5) {
    // SHOW THE PLACEHOLDER
    jQuery(".learny-custom-modal-body").hide();
    jQuery("#learny-right-modal .learny-custom-modal-content").addClass(
        "learny-custom-modal-body-placeholder"
    );

    jQuery("#" + blockingArea).block({
        message: null,
        overlayCSS: {
            backgroundColor: "#f3f4f5",
        },
    });

    return new Promise(function (resolve, reject) {
        jQuery.post(
            ajaxurl,
            {
                action: "learny",
                task: task,
                param1: param1,
                param2: param2,
                param3: param3,
                param4: param4,
                param5: param5,
            },
            function (response) {

                // HIDE THE PLACEHOLDER
                jQuery(".learny-custom-modal-body").show();
                jQuery("#learny-right-modal .learny-custom-modal-content").removeClass(
                    "learny-custom-modal-body-placeholder"
                );

                resolve(response);
            }
        );
    });
}


/**
 * SHOW SECTION ACTION BUTTONS ON HOVER
 */
jQuery(document).on("mouseenter", ".learny-section-area", function () {
    var ly_section_id = jQuery(this).attr('learny_section_id');
    jQuery('.learny-section-action-btn').css('display', 'none');
    jQuery('#learny-section-action-btn-' + ly_section_id).css('display', 'inline-block');
});


/**
 * HIDE SECTION ACTION BUTTONS ON HOVER
 */
jQuery(document).on("mouseleave", ".learny-section-area", function () {
    jQuery('.learny-section-action-btn').css('display', 'none');
});


/**
 * SHOW LESSON ACTION BUTTONS ON HOVER
 */
jQuery(document).on("mouseenter", ".learny-lesson-area", function () {
    var lesson_id = jQuery(this).attr('learny_lesson_id');
    jQuery('.learny-lesson-action-btn').css('display', 'none');
    jQuery('#learny-lesson-action-btn-' + lesson_id).css('display', 'inline-block');
});


/**
 * HIDE LESSON ACTION BUTTONS ON HOVER
 */
jQuery(document).on("mouseleave", ".learny-lesson-area", function () {
    jQuery('.learny-lesson-action-btn').css('display', 'none');
});


/** 
 * YOUTUB AND VIMEO VIDEO URL VALIDATION STARTS
 */

function validateVideoUrl(url) {
    jQuery(".learny-analyzing-video-url").removeClass('learny-d-none');
    learnyMakeGenericAjaxCall('video_validity_and_duration', 'ly_video_duration', url).then(function (response) {
        console.log(response);
        // Run this when your request was successful
        response = jQuery.parseJSON(response);
        if (response.status) {
            jQuery("#ly_video_duration").val(response.duration);
            jQuery(".learny-invalid-video-url").addClass('learny-d-none');
        } else {
            jQuery("#ly_video_duration").val("00:00:00");
            jQuery(".learny-invalid-video-url").removeClass('learny-d-none');
        }
    }).catch(function (err) {
        // Run this when promise was rejected via reject()
        console.log(err);
        jQuery(".learny-invalid-video-url").removeClass('learny-d-none');
    });

    jQuery(".learny-analyzing-video-url").addClass('learny-d-none');
}

/**
 * GET FILE NAME FROM URL
 * @param {*} path 
 * @returns 
 */
function learnyFilename(path) {
    path = path.substring(path.lastIndexOf("/") + 1);
    return (path.match(/[^.]+(\.[^?#]+)?/) || [])[0];
}

// HANDLE THE PAGE REDIRECTION
function redirectTo(url) {
    window.location.replace(url);
}

// image picker for category thumbnail
function learnyHandleCategoryThumbnail(thumbnail) {
    let srcInputId = "#learny-course-category-image-thumbnail-url";
    let srcPreviewId = "#learny-course-category-image-thumbnail-previewer";
    jQuery(srcInputId).val(thumbnail.url);
    jQuery(srcPreviewId).attr('src', thumbnail.url);
}

// SHOWING SECTION LIST IN COURSE EDIT PAGE
function showSectionList() {
    let courseId = learnyCourseDetails.courseId;
    courseId = parseInt(courseId);
    if (parseInt(courseId) > 0) {
        learnyMakeAjaxCall('views/custom-meta-box/course/sub-section/sections', 'learny-section-list-area', courseId);
    }
}