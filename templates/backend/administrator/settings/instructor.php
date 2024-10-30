<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
?>



<div class="learny-row">
    <div class="learny-col">
        <div class="learny-panel learny-settings-page-title">
            <div class="learny-panel-body">
                <div class="learny-page-title-area">
                    <span class="learny-page-title">
                        <?php esc_html_e('Instructor Settings', BaseController::$text_domain); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form update-instructor-settings-form' enctype='multipart/form-data' autocomplete="off">

    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_instructor_settings">
    <input type="hidden" name="update_instructor_settings_nonce" value="<?php echo wp_create_nonce('update_instructor_settings_nonce'); ?>">

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_public_instructor"> <?php esc_html_e('Public Instructor', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_public_instructor" class="learny-og-select" id="ly_public_instructor">
                <option value='1' <?php if (esc_attr(get_option('ly_public_instructor', 0)) == 1) echo "selected"; ?> class="learny-disabled"> <?php esc_html_e("Enable", BaseController::$text_domain) ?></option>
                <option value='0' <?php if (esc_attr(get_option('ly_public_instructor', 0)) == 0) echo "selected"; ?> class="learny-disabled"> <?php esc_html_e("Disable", BaseController::$text_domain) ?></option>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_instructor_application_note"><?php echo esc_html_e('Instructor Application Note', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <textarea name="ly_instructor_application_note" id="ly_instructor_application_note" rows="5"><?php echo esc_textarea(get_option('ly_instructor_application_note', "write some application note here")); ?></textarea>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_instructor_revenue_percentage"><?php echo esc_html_e('Instructor Revenue Percentage', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="number" name="ly_instructor_revenue_percentage" id="ly_instructor_revenue_percentage" onkeyup="calculateAdminRevenuePercentage(this.value)" onchange="calculateAdminRevenuePercentage(this.value)" value="<?php echo esc_attr(get_option('ly_instructor_revenue_percentage', 0)); ?>">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_admin_revenue_percentage"><?php echo esc_html_e('Admin Revenue Percentage', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="number" name="ly_admin_revenue_percentage" id="ly_admin_revenue_percentage" value="<?php echo esc_attr(get_option('ly_admin_revenue_percentage', 100)); ?>">
        </div>
    </div>

    <div class="learny-form-field-row">
        <div class="learny-form-field">
            <button class="button button-primary"><?php esc_html_e('Update Instructor Settings', BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>


<script>
    "use strict";

    function calculateAdminRevenuePercentage(instructor_revenue_percentage) {
        if (instructor_revenue_percentage <= 100) {
            var admin_revenue_percentage = 100 - instructor_revenue_percentage;
            jQuery('#ly_admin_revenue_percentage').val(admin_revenue_percentage);
        } else {
            jQuery('#ly_admin_revenue_percentage').val(0);
        }
    }

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.update-instructor-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        function validate() {
            var lyFormValidity = true;
            jQuery('form.update-instructor-settings-form').find('input').each(function() {
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
                closeModal();
                learnyNotify(response.message, 'success');

            } else {
                learnyNotify(response.message, 'warning');
            }
        }
    });
</script>