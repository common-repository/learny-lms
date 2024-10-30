<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$baseController = new BaseController();
$plugin_path = $baseController->plugin_path;


$current_user_role = Helper::get_current_user_role();

$report_type = (isset($_GET['report-type']) && sanitize_text_field($_GET['report-type']) == "instructor") ? "instructor-report" : "admin-report";
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php if ($report_type == "admin-report") : ?>
            <?php esc_html_e('Admin Revenue Report', BaseController::$text_domain); ?>
        <?php else : ?>
            <?php esc_html_e('Instructor Revenue Report', BaseController::$text_domain); ?>
        <?php endif; ?>
    </h1>

    <?php if ($report_type == "admin-report") : ?>
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=learny-report&report-type=instructor')); ?>" class="page-title-action">
            <i class="dashicons dashicons-buddicons-buddypress-logo learny-btn-icon"></i> <?php esc_html_e('Instructor Revenue Report', BaseController::$text_domain); ?>
        </a>
    <?php else : ?>
        <a href="<?php echo esc_url_raw(admin_url('admin.php?page=learny-report&report-type=admin')); ?>" class="page-title-action">
            <i class="dashicons dashicons-admin-users learny-btn-icon"></i> <?php esc_html_e('Admin Revenue Report', BaseController::$text_domain); ?>
        </a>
    <?php endif; ?>

    <hr class="wp-header-end">

    <!-- SHOW REVENUE LIST HERE -->
    <div id="learny-revenue-list-area">
        <?php include "$report_type.php"; ?>
    </div>
</div>