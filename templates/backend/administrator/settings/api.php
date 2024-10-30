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
                        <?php esc_html_e('API Settings', BaseController::$text_domain); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form update-api-settings-form' enctype='multipart/form-data' autocomplete="off">

    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_api_settings">
    <input type="hidden" name="update_api_settings_nonce" value="<?php echo wp_create_nonce('update_api_settings_nonce'); ?>">

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_youtube_api_key"><?php echo esc_html_e('YouTube API Key', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_youtube_api_key" id="ly_youtube_api_key" value="<?php echo esc_attr(get_option('ly_youtube_api_key', 'youtube-api-key')); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_vimeo_api_key"><?php echo esc_html_e('Vimeo API Key', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_vimeo_api_key" id="ly_vimeo_api_key" value="<?php echo esc_attr(get_option('ly_vimeo_api_key', 'vimeo-api-key')); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row">
        <div class="learny-form-field">
            <button class="button button-primary"><?php esc_html_e('Update API Settings', BaseController::$text_domain); ?></button>
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
        jQuery('.update-api-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        function validate() {
            var lyFormValidity = true;
            jQuery('form.update-api-settings-form').find('input').each(function() {
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