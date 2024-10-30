"use strict";

/**
 * INIT NICE SELECT
 * @param {*} ids 
 */
function initNiceSelect(ids) {
    if (ids && ids.length) {
        let i;
        for (i = 0; i < ids.length; i++) {
            jQuery(ids[i]).niceSelect();
        }
    } else {
        jQuery('select').niceSelect();
    }
}

/**
 * INIT SELECT 2
 * @param {*} ids 
 */
function initSelect2(ids) {
    if (ids && ids.length) {
        let i;
        for (i = 0; i < ids.length; i++) {
            jQuery(ids[i]).select2({
                theme: "classic",
                width: '400px'
            });
        }
    } else {
        jQuery('.learny-select2').select2({
            theme: "classic",
            width: '400px'
        });
    }
}

/**
 * INIT DATE RANGE PICKER
 * @param {*} ids 
 */
function initDateRangePicker(id) {
    var start = moment().startOf('month');
    var end = moment().endOf('month');

    function cb(start, end) {
        jQuery(id + ' span').html(start.format('MMMM DD, YYYY') + ' - ' + end.format('MMMM DD, YYYY'));
        jQuery(id + ' input').val(start.format('MMMM DD, YYYY') + ' - ' + end.format('MMMM DD, YYYY'));
    }

    jQuery(id).daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
}

/**
 * INITIALIZING WP EDITOR
 */
function initWpEditor(ids) {
    if (ids && ids.length) {
        let i;
        for (i = 0; i < ids.length; i++) {
            // AT FIRT REMOVING THE WP EDITOR
            wp.editor.remove(ids[i]);

            // THEN INITIALIZE THE EDITOR
            wp.editor.initialize(
                ids[i],
                {
                    tinymce: {
                        wpautop: true,
                        plugins: 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
                        toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | wp_adv',
                        toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
                    },
                    quicktags: true,
                    mediaButtons: true,
                }
            );
        }
    }
}