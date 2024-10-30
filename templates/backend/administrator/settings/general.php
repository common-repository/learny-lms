<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$default_logo_url = $basecontroller->plugin_url . 'assets/admin/images/logo.png';
?>

<div class="learny-row">
    <div class="learny-col">
        <div class="learny-panel learny-settings-page-title">
            <div class="learny-panel-body">
                <div class="learny-page-title-area">
                    <span class="learny-page-title">
                        <?php esc_html_e('General Settings', BaseController::$text_domain); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form update-general-settings-form' enctype='multipart/form-data' autocomplete="off">

    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_general_settings">
    <input type="hidden" name="update_general_settings_nonce" value="<?php echo wp_create_nonce('update_general_settings_nonce'); ?>">

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_business_name"><?php echo esc_html_e('Business Name', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_business_name" id="ly_business_name" value="<?php echo esc_attr(get_option('ly_business_name'), 'Learny LMS'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_business_email"><?php echo esc_html_e('Business Email', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_business_email" id="ly_business_email" value="<?php echo esc_attr(get_option('ly_business_email'), 'business@learny.com'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_business_phone"><?php echo esc_html_e('Business Phone Number', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_business_phone" id="ly_business_phone" value="<?php echo esc_attr(get_option('ly_business_phone'), 'Learny business phone'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_purchase_code"><?php echo esc_html_e('Purchase Code', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_purchase_code" id="ly_purchase_code" value="<?php echo esc_attr(get_option('ly_purchase_code'), 'Learny purchase code'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_system_logo"><?php echo esc_html_e('System Logo', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <img src="<?php echo esc_url_raw(get_option('ly_system_logo_url', $default_logo_url)); ?>" alt="" class="learny-thumbnail-image-previewer" id="ly_system_logo_previewer">
            <input type="hidden" name="ly_system_logo_url" id="ly_system_logo_url" value="<?php echo esc_attr(get_option('ly_system_logo_url')); ?>">
            <p>
                <button type="button" id="learny-category-image-uploader" class="button" onclick="learnyMediaUploader('image', false, '<?php echo esc_html_e('Choose Category Thumbnail', BaseController::$text_domain); ?>', learnyHandleCategoryThumbnail);"><?php esc_html_e('Upload Image', BaseController::$text_domain); ?></button><br>
                <?php esc_html_e('Image size has to be this', BaseController::$text_domain); ?>
            </p>
        </div>
    </div>

    <div class="learny-form-field-row">
        <div class="learny-form-field">
            <button type="submit" class="button button-primary"><?php esc_html_e('Update General Settings', BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>
<script>
    "use strict";

    function learnyHandleCategoryThumbnail(systemLogo) {
        let srcInputId = "#ly_system_logo_url";
        let srcPreviewId = "#ly_system_logo_previewer";
        jQuery(srcInputId).val(systemLogo.url);
        jQuery(srcPreviewId).attr('src', systemLogo.url);
    }

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.update-general-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        function validate() {
            var lyFormValidity = true;
            jQuery('form.update-general-settings-form').find('input').each(function() {
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