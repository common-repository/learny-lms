<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$current_user_role = Helper::get_current_user_role();

?>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form instructor-add-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_instructor'; ?>" ly-required="true">
    <input type="hidden" name="task" value="add_instructor" ly-required="true">
    <input type="hidden" name="add_instructor_nonce" value="<?php echo wp_create_nonce('add_instructor_nonce'); ?>" ly-required="true"> <!-- kind of csrf token-->

    <div class="learny-form-group">
        <label for="ly_first_name"> <?php esc_html_e('First Name', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="text" name="ly_first_name" class="learny-form-control" id="ly_first_name" aria-describedby="ly_first_name" placeholder="<?php esc_html_e('Enter First Name', BaseController::$text_domain); ?>" ly-required="true">
    </div>

    <div class="learny-form-group">
        <label for="ly_last_name"> <?php esc_html_e('Last Name', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="text" name="ly_last_name" class="learny-form-control" id="ly_last_name" aria-describedby="ly_last_name" placeholder="<?php esc_html_e('Enter Last Name', BaseController::$text_domain); ?>" ly-required="true">
    </div>

    <div class="learny-form-group">
        <label for="ly_username"> <?php esc_html_e('Username', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="text" name="ly_username" class="learny-form-control" id="ly_username" aria-describedby="ly_username" placeholder="<?php esc_html_e('Enter Username', BaseController::$text_domain); ?>" ly-required="true">
    </div>

    <div class="learny-form-group">
        <label for="ly_email"> <?php esc_html_e('Email', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="email" name="ly_email" class="learny-form-control" id="ly_email" aria-describedby="ly_email" placeholder="<?php esc_html_e('Enter Email', BaseController::$text_domain); ?>" ly-required="true">
    </div>

    <div class="learny-form-group">
        <label for="ly_password"> <?php esc_html_e('Password', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="password" name="ly_password" class="learny-form-control" id="ly_password" aria-describedby="ly_password" placeholder="<?php esc_html_e('Enter Password', BaseController::$text_domain); ?>" ly-required="true">
    </div>

    <div class="learny-form-group">
        <label for="ly_bio"> <?php esc_html_e('Short Bio', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <textarea name="ly_bio" id="ly_bio" class="learny-form-control"></textarea>
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
        jQuery('.instructor-add-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var lyFormValidity = true;
        jQuery('form.instructor-add-form').find('input').each(function() {
            if (jQuery(this).attr('ly-required') && jQuery(this).val() === "") {
                learnyNotify("<?php esc_html_e('Fill all the required fields', BaseController::$text_domain) ?>", 'warning');
                lyFormValidity = false;
                return lyFormValidity;
            }
        });

        return lyFormValidity;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.instructor-add-form').trigger('reset');
            closeModal();
            learnyNotify(response.message, 'success');
            learnyMakeAjaxCall('templates/backend/<?php echo esc_js($current_user_role); ?>/instructor/list', 'learny-instructor-list-area');
            redirectTo('<?php echo esc_url_raw(admin_url('admin.php?page=learny-instructor')); ?>');
        } else {
            learnyNotify(response.message, 'warning');
        }
    }
</script>