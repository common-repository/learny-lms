<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$page_contains = filter_input(INPUT_GET, 'page-contains', FILTER_SANITIZE_URL);

$current_page = "my-courses";

$pages = array(
    'my-courses'       => 'lab la-youtube',
    'my-wishlist'      => 'las la-bookmark',
    'purchase-history' => 'las la-history',
    'my-profile'       => 'las la-user-circle',
    'my-messages'      => 'las la-comments'
);

switch ($page_contains) {
    case "my-course":
        $current_page = "my-courses";
        break;
    case "my-wishlist":
        $current_page = "my-wishlist";
        break;
    case "purchase-history":
        $current_page = "purchase-history";
        break;
    case "my-profile":
        $current_page = "my-profile";
        break;
    case "my-messages":
        $current_page = "my-messages";
        break;
    default:
        $current_page = "my-courses";
        break;
}
$page_path = Helper::view_path("student-pages/$current_page");
?>

<div class="container-fluid overflow-hidden learny-shortcode-full-width">
    <div class="row vh-100 overflow-auto">
        <div class="col-12 col-sm-3 col-xl-2 px-0 bg-light d-flex sticky-top learny-student-sidenav">
            <?php include 'sidenav.php'; ?>
        </div>
        <div class="col d-flex flex-column h-sm-100">
            <main class="row overflow-auto">
                <div class="learny-student-page-header bg-light">
                    <h3> <i class="<?php echo esc_attr($pages[$current_page]); ?>"></i> <?php echo ucwords(esc_html__(str_replace('-', ' ', $current_page), BaseController::$text_domain)); ?></h3>
                </div>
                <div class="col">
                    <?php include $page_path; ?>
                </div>
            </main>
            <?php include 'footer.php'; ?>
        </div>
    </div>
</div>