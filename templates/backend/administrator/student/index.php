<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$current_user_role = Helper::get_current_user_role();
?>
<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Students', BaseController::$text_domain); ?>
    </h1>

    <a href="javascript:void(0)" class="page-title-action" onclick="present_right_modal( 'templates/backend/<?php echo esc_js($current_user_role); ?>/student/create', '<?php esc_html_e('Add New Student', BaseController::$text_domain); ?>' )">
        <i class="dashicons dashicons-plus-alt2 learny-btn-icon"></i> <?php esc_html_e('Add New Student', BaseController::$text_domain); ?>
    </a>

    <a href="<?php echo esc_url_raw(admin_url('admin.php?page=learny-student&filter_status=pending')); ?>" class="page-title-action">
        <i class="dashicons dashicons-admin-users learny-btn-icon"></i> <?php esc_html_e('Pending Student', BaseController::$text_domain); ?>
    </a>

    <hr class="wp-header-end">
    
    <!-- SHOW LEARNY STUDENT LIST HERE -->
    <div id="learny-student-list-area">
        <?php include 'list.php'; ?>
    </div>
</div>

<?php include Helper::learnyModal(); ?>