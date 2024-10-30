<?php
defined('ABSPATH') or die('You can not access the file directly');
$redirect_url            = isset(Learny\base\AjaxPosts::$url) && !empty(Learny\base\AjaxPosts::$url) ? Learny\base\AjaxPosts::$url : null;
$task                    = Learny\base\AjaxPosts::$param1;
$id                      = Learny\base\AjaxPosts::$param2;
$submission_hook         = Learny\base\AjaxPosts::$param3;
$section_to_be_displayed = Learny\base\AjaxPosts::$param4;
$section_container      = Learny\base\AjaxPosts::$param5;
$parameter_to_return_1  = Learny\base\AjaxPosts::$param6;
$parameter_to_return_2  = Learny\base\AjaxPosts::$param7;
$parameter_to_return_3  = Learny\base\AjaxPosts::$param8;
?>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form confirmation-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(\Learny\base\BaseController::$plugin_id . '_' . esc_attr($submission_hook)); ?>">
    <input type="hidden" name="task" value="<?php echo esc_attr($task); ?>">
    <input type="hidden" name="confirmation_form_nonce" value="<?php echo esc_attr(wp_create_nonce('confirmation_form_nonce')); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="id" value="<?php echo esc_attr($id); ?>">

    <label for="warning" class="learny-confirmation-modal-label"><?php esc_html_e('Are you sure, You want to do this?', \Learny\base\BaseController::$text_domain) ?></label><br>
    <div class="learny-row learny-justify-content-center learny-m-3">
        <div class="learny-col-lg-4 learny-col-6">
            <button type="submit" class="learny-btn learny-btn-danger learny-btn-block" onclick="closeModal(jQuery(this).closest('.learny-modal').attr('id'))"><?php esc_html_e("Yes", \Learny\base\BaseController::$text_domain); ?></button>
        </div>

        <div class="learny-col-lg-4 learny-col-6">
            <button type="button" class="learny-btn learny-btn-secondary learny-btn-block" onclick="closeModal(jQuery(this).closest('.learny-modal').attr('id'))"><?php esc_html_e("Cancel", \Learny\base\BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>

<script>
    "use strict";

    jQuery(document).ready(function($) {
        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: true
        };
        jQuery('.confirmation-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {

    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        var redirect_url = "<?php echo esc_url($redirect_url); ?>";
        response = JSON.parse(response);
        if (response.status) {
            closeModal();
            learnyNotify(response.message, 'success');
            learnyMakeAjaxCall('<?php echo esc_js($section_to_be_displayed); ?>', '<?php echo esc_js($section_container); ?>', '<?php echo esc_js($parameter_to_return_1); ?>', '<?php echo esc_js($parameter_to_return_2); ?>', '<?php echo esc_js($parameter_to_return_3); ?>');

            if (redirect_url) {
                redirectTo(redirect_url);
            }

        } else {
            learnyNotify(response.message, 'warning');
        }
    }
</script>