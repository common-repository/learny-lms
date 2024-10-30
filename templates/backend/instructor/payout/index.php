<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$baseController = new BaseController();
$plugin_path = $baseController->plugin_path;
$current_user_role = Helper::get_current_user_role();
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php esc_html_e('Instructor Revenue Report', BaseController::$text_domain); ?>
    </h1>

    <a href="javascript:void(0)" class="page-title-action" onclick="present_right_modal( 'templates/backend/<?php echo esc_js($current_user_role); ?>/payout/create', '<?php esc_html_e('Request New Payout', BaseController::$text_domain); ?>' )">
        <i class="dashicons dashicons-plus-alt2 learny-btn-icon"></i> <?php esc_html_e('Request New Payout', BaseController::$text_domain); ?>
    </a>

    <hr class="wp-header-end">

    <!-- SHOW REVENUE LIST HERE -->
    <div id="instructor-payout-list-area">
        <?php include "list.php"; ?>
    </div>
</div>

<?php include Helper::learnyModal(); ?>