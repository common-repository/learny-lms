"use strict";

var ajaxurl = data.adminAjaxUrl;

// CLOSING A MODAL
function closeModal() {
    jQuery(".learny-modal").modal('hide');
}

// PRESENTING RIGHT MODAL
function present_right_modal(page, header_title, param1, param2, param3) {

    // show the modal first
    jQuery("#learny-right-modal").modal('show', {
        backdrop: 'true'
    });
    // SHOW THE PLACEHOLDER
    jQuery(".learny-custom-modal-body").hide();
    jQuery("#learny-right-modal .learny-custom-modal-content").addClass(
        "learny-custom-modal-body-placeholder"
    );

    jQuery('#learny-right-modal .learny-custom-modal-header h2').html(header_title);
    jQuery('#learny-right-modal .learny-custom-modal-body').block({
        message: null,
        overlayCSS: {
            backgroundColor: '#ffffff'
        }
    });

    jQuery.post(
        ajaxurl, {
        'action': 'learny',
        'page': page,
        'task': 'load_modal_page',
        'param1': param1,
        'param2': param2,
        'param3': param3
    },
        function (response) {

            jQuery('#learny-right-modal .learny-custom-modal-body').html(response);
            jQuery('#learny-right-modal .learny-custom-modal-body').unblock();

            // HIDE THE PLACEHOLDER AND SHOW THE MODAL BODY
            jQuery(".learny-custom-modal-body").show();
            jQuery("#learny-right-modal .learny-custom-modal-content").removeClass(
                "learny-custom-modal-body-placeholder"
            );
        }
    )
}

// PRESENTING CONFIRMATION MODAL
function present_confirmation_modal(header_title, redirectUrl, param1, param2, param3, param4, param5, param6, param7, param8, param9) {

    closeModal();
    jQuery("#confirmation-for-update-modal").modal('show', {
        backdrop: 'true'
    });
    jQuery("#confirmation-for-update-modal .learny-custom-modal-body").html();

    jQuery('#confirmation-for-update-modal .learny-custom-modal-header h2').html(header_title);

    jQuery.post(
        ajaxurl, {
        'action': 'learny',
        'task': 'load_confirmation_modal_page',
        'url': redirectUrl,
        'param1': param1,
        'param2': param2,
        'param3': param3,
        'param4': param4,
        'param5': param5,
        'param6': param6,
        'param7': param7,
        'param8': param8,
        'param9': param9
    },
        function (response) {
            jQuery('#confirmation-for-update-modal .learny-custom-modal-body').html(response);
        }
    )

}