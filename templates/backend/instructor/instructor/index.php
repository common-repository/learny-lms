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

    <hr class="wp-header-end">

    <!-- SHOW INSTRUCTOR LIST HERE -->
    <div id="learny-instructor-list-area">
        <?php include 'list.php'; ?>
    </div>
</div>

<?php include Helper::learnyModal(); ?>