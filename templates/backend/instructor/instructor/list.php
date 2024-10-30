<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\table\Instructor;

$order = 'asc';
$orderby = 'name';

$instructor_table = new Instructor();
$instructors = $instructor_table->get_data();

function datatable_filter_status($item)
{
    $status = isset($_REQUEST['filter_status']) ? sanitize_text_field($_REQUEST['filter_status']) : 'all';
    if ('all' == $status) {
        return true;
    } else {
        $status = sanitize_text_field($_REQUEST['filter_status']) == "approved" ? 1 : 0;
        return ($status == $item['status']) ? true : false;
    }
}

// DOES THE SORTING PART
if (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby']) && isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $orderby = sanitize_text_field($_REQUEST['orderby']);
    $order   = sanitize_text_field($_REQUEST['order']);
    if ('asc' == $order) {
        usort($instructors, function ($item1, $item2) {
            return $item1[sanitize_text_field($_REQUEST['orderby'])] <=> $item2[sanitize_text_field($_REQUEST['orderby'])];
        });
    } else {
        usort($instructors, function ($item1, $item2) {
            return $item2[sanitize_text_field($_REQUEST['orderby'])] <=> $item1[sanitize_text_field($_REQUEST['orderby'])];
        });
    }
}

function datatable_search_by_name($item)
{
    $name = strtolower($item['name']);
    $search_name = strtolower(sanitize_text_field($_REQUEST['s']));
    if (strpos($name, $search_name) !== false) {
        return true;
    }
    return false;
}

// DOING THE SEARCH PART
if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
    $instructors = array_filter($instructors, "datatable_search_by_name");
}

// DOING THE SEARCH PART
if (isset($_REQUEST['filter_status']) && !empty($_REQUEST['filter_status'])) {
    $instructors = array_filter($instructors, 'datatable_filter_status');
}

?>

<h3><?php esc_html_e('Number of Result Shown', BaseController::$text_domain); ?> <?php echo count($instructors); ?></h3>

<form action="" method="GET">
    <input type="hidden" name="page" value="<?php echo esc_attr(sanitize_text_field($_REQUEST['page'])); ?>">
    <?php
    $instructor_table->set_data($instructors);
    $instructor_table->prepare_items();
    $instructor_table->search_box('search', 'search_id');
    $instructor_table->views();
    $instructor_table->display();
    ?>
</form>