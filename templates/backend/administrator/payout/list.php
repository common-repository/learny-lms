<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\table\Payout;
use Learny\base\modules\Payouts;

$order = 'asc';
$orderby = 'payout_date_added';


$payout_table = new Payout();
$payouts = $payout_table->get_data();


// DOES THE SORTING PART
if (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby']) && isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $orderby = sanitize_text_field($_REQUEST['orderby']);
    $order   = sanitize_text_field($_REQUEST['order']);
    if ('asc' == $order) {
        usort($payouts, function ($item1, $item2) {
            return $item1[sanitize_text_field($_REQUEST['orderby'])] <=> $item2[sanitize_text_field($_REQUEST['orderby'])];
        });
    } else {
        usort($payouts, function ($item1, $item2) {
            return $item2[sanitize_text_field($_REQUEST['orderby'])] <=> $item1[sanitize_text_field($_REQUEST['orderby'])];
        });
    }
}

function datatable_search_by_name($item)
{
    $name = strtolower($item['instructor']);
    $search_name = strtolower(sanitize_text_field($_REQUEST['s']));
    if (strpos($name, $search_name) !== false) {
        return true;
    }
    return false;
}

// DOING THE SEARCH PART
if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
    $payouts = array_filter($payouts, "datatable_search_by_name");
}

?>

<h3><?php esc_html_e('Number of Result Shown', BaseController::$text_domain); ?> : <?php echo count($payouts); ?></h3>

<form action="" method="GET">
    <input type="hidden" name="page" value="<?php echo esc_attr(sanitize_text_field($_REQUEST['page'])); ?>">
    <?php
    $payout_table->set_data($payouts);
    $payout_table->prepare_items();
    $payout_table->search_box('search', 'search_id');
    $payout_table->views();
    $payout_table->display();
    ?>
</form>