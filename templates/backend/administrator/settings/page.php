<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$available_pages = get_pages();
?>

<div class="learny-row">
    <div class="learny-col">
        <div class="learny-panel learny-settings-page-title">
            <div class="learny-panel-body">
                <div class="learny-page-title-area">
                    <span class="learny-page-title">
                        <?php esc_html_e('Page Settings', BaseController::$text_domain); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form update-page-settings-form' enctype='multipart/form-data' autocomplete="off">

    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_page_settings">
    <input type="hidden" name="update_page_settings_nonce" value="<?php echo wp_create_nonce('update_page_settings_nonce'); ?>">

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_auth_page"><?php echo esc_html_e('Login and Registration Page', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <select name="ly_auth_page" class="learny-og-select" ly-required="true">
                <option value=""><?php esc_html_e('Choose A Page', BaseController::$text_domain); ?></option>
                <?php
                $ly_auth_page = esc_html(get_option('ly_auth_page', ''));
                foreach ($available_pages as $page_data) :
                    $id = $page_data->ID;
                    $title = $page_data->post_title;
                ?>
                    <option value='<?php echo esc_attr($id); ?>' <?php if ($ly_auth_page == $id) echo 'selected'; ?>><?php echo esc_html($title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_dashboard_page"><?php echo esc_html_e('Student Dashboard Page', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <select name="ly_dashboard_page" class="learny-og-select" ly-required="true">
                <option value=""><?php esc_html_e('Choose A Page', BaseController::$text_domain); ?></option>
                <?php
                $ly_dashboard_page = esc_html(get_option('ly_dashboard_page', ''));
                foreach ($available_pages as $page_data) :
                    $id = $page_data->ID;
                    $title = $page_data->post_title;
                ?>
                    <option value='<?php echo esc_attr($id); ?>' <?php if ($ly_dashboard_page == $id) echo 'selected'; ?>><?php echo esc_html($title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_course_player_page"><?php echo esc_html_e('Course Player Page', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <select name="ly_course_player_page" class="learny-og-select" ly-required="true">
                <option value=""><?php esc_html_e('Choose A Page', BaseController::$text_domain); ?></option>
                <?php
                $ly_course_player_page = esc_html(get_option('ly_course_player_page', ''));
                foreach ($available_pages as $page_data) :
                    $id = $page_data->ID;
                    $title = $page_data->post_title;
                ?>
                    <option value='<?php echo esc_attr($id); ?>' <?php if ($ly_course_player_page == $id) echo 'selected'; ?>><?php echo esc_html($title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_checkout_page"><?php echo esc_html_e('Checkout Page', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <select name="ly_checkout_page" class="learny-og-select" ly-required="true">
                <option value=""><?php esc_html_e('Choose A Page', BaseController::$text_domain); ?></option>
                <?php
                $ly_checkout_page = esc_html(get_option('ly_checkout_page', ''));
                foreach ($available_pages as $page_data) :
                    $id = $page_data->ID;
                    $title = $page_data->post_title;
                ?>
                    <option value='<?php echo esc_attr($id); ?>' <?php if ($ly_checkout_page == $id) echo 'selected'; ?>><?php echo esc_html($title); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row">
        <div class="learny-form-field">
            <button class="button button-primary"><?php esc_html_e('Update Page Settings', BaseController::$text_domain); ?></button>
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
        jQuery('.update-page-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        function validate() {
            var lyFormValidity = true;
            jQuery('form.update-page-settings-form').find('input, select').each(function() {
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