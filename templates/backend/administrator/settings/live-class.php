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
                        <?php esc_html_e('Live Class Settings', BaseController::$text_domain); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form update-live-class-settings-form' enctype='multipart/form-data' autocomplete="off">

    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_live_class_settings">
    <input type="hidden" name="update_live_class_settings_nonce" value="<?php echo wp_create_nonce('update_live_class_settings_nonce'); ?>">

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_zoom_api_key"><?php echo esc_html_e('Zoom API Keys', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_zoom_api_key" id="ly_zoom_api_key" value="<?php echo esc_attr(get_option('ly_zoom_api_key', 'zoom-api-key')); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_zoom_secret_key"><?php echo esc_html_e('Zoom Secret Keys', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_zoom_secret_key" id="ly_zoom_secret_key" value="<?php echo esc_attr(get_option("ly_zoom_secret_key", "zoom-secret-key")) ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row">
        <div class="learny-form-field">
            <button class="button button-primary"><?php esc_html_e('Update Live Class Settings', BaseController::$text_domain); ?></button>
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
        jQuery('.update-live-class-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        function validate() {
            var lyFormValidity = true;
            jQuery('form.update-live-class-settings-form').find('input').each(function() {
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