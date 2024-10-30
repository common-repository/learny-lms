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
        <?php esc_html_e('Instructor Payout Report', BaseController::$text_domain); ?>
    </h1>

    <hr class="wp-header-end">

    <!-- SHOW REVENUE LIST HERE -->
    <div id="instructor-payout-list-area">
        <?php include "list.php"; ?>
    </div>
</div>

<?php include Helper::learnyModal(); ?>