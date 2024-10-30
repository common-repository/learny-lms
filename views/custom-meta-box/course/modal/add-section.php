<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\AjaxPosts;
?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form section-add-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_section'; ?>">
    <input type="hidden" name="task" value="add_section">
    <input type="hidden" name="add_section_nonce" value="<?php echo wp_create_nonce('add_section_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="learny_course_id" value="<?php echo esc_attr(AjaxPosts::$param1); ?>">

    <div class="learny-form-group">
        <label for="learny_section_title"> <?php esc_html_e('Section Title', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="text" name="learny_section_title" class="form-control" id="learny_section_title" aria-describedby="learny_section_title" placeholder="<?php esc_html_e('Section Title', BaseController::$text_domain); ?>">
    </div>

    <!-- FORM ACTION -->
    <div class="learny-custom-modal-action-footer">
        <div class="learny-custom-modal-actions">
            <button type="button" class="learny-btn learny-btn-secondary learny-btn-md" data-dismiss="modal"><?php esc_html_e('Cancel', BaseController::$text_domain); ?></button>
            <button type="submit" class="learny-btn learny-btn-primary learny-btn-md"><?php esc_html_e('Submit', BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>


<script>
    "use strict";

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.section-add-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var learny_section_title = jQuery('#learny_section_title').val();

        if (learny_section_title === '') {

            learnyNotify("<?php esc_html_e('You must enter section title', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.section-add-form').trigger('reset');
            closeModal();
            learnyNotify(response.message, 'success');
            learnyMakeAjaxCall('views/custom-meta-box/course/sub-section/sections', 'learny-section-list-area', '<?php echo esc_attr(AjaxPosts::$param1); ?>');
        } else {
            learnyNotify(response.message, 'warning');
        }
    }
</script>