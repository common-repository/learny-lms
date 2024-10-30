<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\table\Student;

$order = 'asc';
$orderby = 'name';

$student_table = new Student();
$students = $student_table->get_data();

function datatable_filter_status($item)
{
    $status = isset($_REQUEST['filter_status']) ? sanitize_text_field($_REQUEST['filter_status']) : 'all';
    if ('all' == $status) {
        return true;
    } else {
        $status = $_REQUEST['filter_status'] == "approved" ? 1 : 0;
        return ($status == $item['status']) ? true : false;
    }
}

// DOES THE SORTING PART
if (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby']) && isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $orderby = sanitize_text_field($_REQUEST['orderby']);
    $order   = sanitize_text_field($_REQUEST['order']);
    if ('asc' == $order) {
        usort($students, function ($item1, $item2) {
            return $item1[sanitize_text_field($_REQUEST['orderby'])] <=> $item2[sanitize_text_field($_REQUEST['orderby'])];
        });
    } else {
        usort($students, function ($item1, $item2) {
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
    $students = array_filter($students, "datatable_search_by_name");
}

// DOING THE SEARCH PART
if (isset($_REQUEST['filter_status']) && !empty($_REQUEST['filter_status'])) {
    $students = array_filter($students, 'datatable_filter_status');
}

?>

<h3><?php esc_html_e('Number of Result Shown', BaseController::$text_domain); ?> <?php echo count($students); ?></h3>

<form action="" method="GET">
    <input type="hidden" name="page" value="<?php echo esc_attr(sanitize_text_field($_REQUEST['page'])); ?>">
    <?php
    $student_table->set_data($students);
    $student_table->prepare_items();
    $student_table->search_box('search', 'search_id');
    $student_table->views();
    $student_table->display();
    ?>
</form>