<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$basecontroller = new BaseController();

$default_logo_url = $basecontroller->plugin_url . 'assets/admin/images/logo.png';

$allowed_sub_pages = array('general', 'instructor', 'payment', 'live-class', 'api', 'page');

$sub_page = "page";

if (isset($_GET['type']) && !empty($_GET['type'])) {
    $sub_page = sanitize_text_field($_GET['type']);
}
?>

<div class="wrap">
    <div class="learny-row">
        <div class="learny-col-xl-3 learny-col-md-5">
            <div class="ly-settings-sidebar">
                <div class="ly-settings-nav-header">
                    <img src="<?php echo esc_url_raw(get_option('ly_system_logo_url', $default_logo_url)); ?>" alt="">
                </div>
                <!--   This ly-settings-section should be at the top -->
                <div class="ly-settings-section">

                    <a href="<?php echo esc_attr(add_query_arg('type', 'page')); ?>">
                        <div class="ly-settings-item  <?php if ($sub_page == 'page') echo 'ly-settings-active' ?>">
                            <?php esc_html_e('Page Settings', BaseController::$text_domain); ?> <i class="dashicons dashicons-admin-site-alt2"></i>
                        </div>
                    </a>

                    <a href="<?php echo esc_attr(add_query_arg('type', 'general')); ?>">
                        <div class="ly-settings-item <?php if ($sub_page == 'general') echo 'ly-settings-active' ?>">
                            <?php esc_html_e('General Settings', BaseController::$text_domain); ?> <i class="dashicons dashicons-admin-settings"></i>
                        </div>
                    </a>

                    <a href="<?php echo esc_attr(add_query_arg('type', 'instructor')); ?>">
                        <div class="ly-settings-item <?php if ($sub_page == 'instructor') echo 'ly-settings-active' ?>">
                            <?php esc_html_e('Instructor Settings', BaseController::$text_domain); ?> <i class="dashicons dashicons-admin-users"></i>
                        </div>
                    </a>

                    <a href="<?php echo esc_attr(add_query_arg('type', 'api')); ?>">
                        <div class="ly-settings-item <?php if ($sub_page == 'api') echo 'ly-settings-active' ?>">
                            <?php esc_html_e('API Settings', BaseController::$text_domain); ?> <i class="dashicons dashicons-admin-plugins"></i>
                        </div>
                    </a>

                    <a href="<?php echo esc_attr(add_query_arg('type', 'payment')); ?>">
                        <div class="ly-settings-item  <?php if ($sub_page == 'payment') echo 'ly-settings-active' ?>">
                            <?php esc_html_e('Payment Settings', BaseController::$text_domain); ?> <i class="dashicons dashicons-money-alt"></i>
                        </div>
                    </a>
                    <!-- LIVE CLASS SETTINGS MENU HAS BEEN DISABLED RIGHT NOW. JUST COPY A MENU AND MAKE IT 'live-class' -->
                </div>
            </div>
        </div>
        <div class="learny-col-xl-8 learny-col-md-6">
            <div class="conent learny-settings-content">
                <?php is_string($sub_page) && in_array($sub_page, $allowed_sub_pages) ? include "$sub_page.php" : include "general.php"; ?>
            </div>
        </div>
    </div>
</div>