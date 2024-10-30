"use strict";

jQuery(function ($) {

    const player = new Plyr('#player');
    $('#coursePreviewModal').on('hide.bs.modal', function () {
        player.pause();
    });


    // HANDLE THE PAGE REDIRECTION
    function redirectTo(url) {
        window.location.replace(url);
    }
});
