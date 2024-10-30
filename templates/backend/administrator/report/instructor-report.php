<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\table\Report;

$order = 'asc';
$orderby = 'enrolled_course';

$starting_timestamp = null;
$ending_timestamp = null;

if (isset($_GET['daterange']) && !empty($_GET['daterange'])) {
    $date_range                   = sanitize_text_field($_GET['daterange']);
    $date_range                   = explode(" - ", $date_range);

    $starting_timestamp = strtotime($date_range[0] . ' 00:00:00');
    $ending_timestamp   = strtotime($date_range[1] . ' 23:59:59');
} else {
    $first_day_of_month = "1 " . date("M") . " " . date("Y") . ' 00:00:00';
    $last_day_of_month = date("t") . " " . date("M") . " " . date("Y") . ' 23:59:59';
    $starting_timestamp   = strtotime($first_day_of_month);
    $ending_timestamp     = strtotime($last_day_of_month);
}

$report_table = new Report([], 'instructor', $starting_timestamp, $ending_timestamp);
$reports = $report_table->get_data();


// DOES THE SORTING PART
if (isset($_REQUEST['orderby']) && !empty($_REQUEST['orderby']) && isset($_REQUEST['order']) && !empty($_REQUEST['order'])) {
    $orderby = sanitize_text_field($_REQUEST['orderby']);
    $order   = sanitize_text_field($_REQUEST['order']);
    if ('asc' == $order) {
        usort($reports, function ($item1, $item2) {
            return $item1[sanitize_text_field($_REQUEST['orderby'])] <=> $item2[sanitize_text_field($_REQUEST['orderby'])];
        });
    } else {
        usort($reports, function ($item1, $item2) {
            return $item2[sanitize_text_field($_REQUEST['orderby'])] <=> $item1[sanitize_text_field($_REQUEST['orderby'])];
        });
    }
}

function datatable_search_by_name($item)
{
    $name = strtolower($item['enrolled_course']);
    $search_name = strtolower(sanitize_text_field($_REQUEST['s']));
    if (strpos($name, $search_name) !== false) {
        return true;
    }
    return false;
}

// DOING THE SEARCH PART
if (isset($_REQUEST['s']) && !empty($_REQUEST['s'])) {
    $reports = array_filter($reports, "datatable_search_by_name");
}

?>

<h3><?php esc_html_e('Number of Result Shown', BaseController::$text_domain); ?> : <?php echo count($reports); ?></h3>

<form action="<?php echo esc_url_raw(admin_url('admin.php')); ?>" method="GET">
    <input type="hidden" name="page" value="learny-report">
    <input type="hidden" name="report-type" value="instructor">

    <div class="learny-row learny-justify-content-center">
        <div class="learny-col-md-3">
            <div id="reportrange" class="learny-report-daterange-picker">
                <i class="dashicons dashicons-calendar-alt"></i> <span><?php echo date('F d, Y', esc_html($starting_timestamp)) . ' - ' . date('F d, Y', esc_html($ending_timestamp)); ?></span> <i class="dashicons dashicons-arrow-down"></i>
                <input type="hidden" name="daterange" id="daterange" value="<?php echo date('F d, Y', esc_html($starting_timestamp)) . ' - ' . date('F d, Y', esc_html($ending_timestamp)); ?>">
            </div>
        </div>
        <div class="learny-col-md-1">
            <button class="button-primary" type="submit"><?php esc_html_e('Filter', BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>

<form action="" method="GET">
    <input type="hidden" name="page" value="<?php echo esc_attr(sanitize_text_field($_REQUEST['page'])); ?>">
    <?php
    $report_table->set_data($reports);
    $report_table->prepare_items();
    $report_table->search_box('search', 'search_id');
    $report_table->views();
    $report_table->display();
    ?>
</form>

<script type="text/javascript">
    "use strict";

    jQuery(function() {
        initDateRangePicker('#reportrange');

    });
</script>