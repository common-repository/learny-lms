<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$baseController = new BaseController();
$plugin_path = $baseController->plugin_path;


$current_user_role = Helper::get_current_user_role();

$allowed_sub_pages = array('create', 'edit');
$sub_page = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : "";

if (is_string($sub_page) && in_array($sub_page, $allowed_sub_pages)) {
    $include_file = "{$sub_page}.php";
    include $include_file;
    return;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Instructor', BaseController::$text_domain); ?>
    </h1>

    <a href="javascript:void(0)" class="page-title-action" onclick="present_right_modal( 'templates/backend/<?php echo esc_js($current_user_role); ?>/instructor/create', '<?php esc_html_e('Add New Instructor', BaseController::$text_domain); ?>' )">
        <i class="dashicons dashicons-plus-alt2 learny-btn-icon"></i> <?php esc_html_e('Add New Instructor', BaseController::$text_domain); ?>
    </a>

    <a href="<?php echo esc_url_raw(admin_url('admin.php?page=learny-instructor&filter_status=pending')); ?>" class="page-title-action">
        <i class="dashicons dashicons-admin-users learny-btn-icon"></i> <?php esc_html_e('Pending Instructor', BaseController::$text_domain); ?>
    </a>

    <a href="<?php echo esc_url_raw(admin_url('admin.php?page=learny-payout')); ?>" class="page-title-action">
        <i class="dashicons dashicons-megaphone learny-btn-icon"></i> <?php esc_html_e('Instructors Payouts', BaseController::$text_domain); ?>
    </a>

    <hr class="wp-header-end">

    <!-- SHOW INSTRUCTOR LIST HERE -->
    <div id="learny-instructor-list-area">
        <?php include 'list.php'; ?>
    </div>
</div>

<?php include Helper::learnyModal(); ?>