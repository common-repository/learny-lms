<?php

/**
 * @package Learny
 */

namespace Learny\table;

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Payment;
use Learny\table\Base_Table;

defined('ABSPATH') or die('You can not access the file directly');

class Report extends Base_Table
{

    public $order = "asc";
    public $orderby = "enrolled_course";
    public $reportType = "admin";
    public $starting_timestamp, $ending_timestamp;
    /**
     * DEFAULT CONSTRUCTOR
     *
     * @param array $args
     */
    public function __construct($args = array(), $reportType = "", $starting_timestamp = "", $ending_timestamp = "")
    {
        $this->reportType = $reportType;
        if ("admin" == $reportType) {
            $columns = [
                'cb'              => '<input type="checkbox">',
                'enrolled_course' => esc_html__('Enrolled Course', BaseController::$text_domain),
                'total_amount'    => esc_html__('Total Amount', BaseController::$text_domain),
                'admin_revenue'   => esc_html__('Admin Revenue', BaseController::$text_domain),
                'enrolment_date'  => esc_html__('Enrolment Date', BaseController::$text_domain),
            ];
            $sortable_columns = [
                'enrolled_course' => ['enrolled_course', true],
                'total_amount' => ['total_amount', true],
                'admin_revenue' => ['admin_revenue', true],
                'enrolment_date' => ['enrolment_date', true],
            ];
        } else {
            $columns = [
                'cb'              => '<input type="checkbox">',
                'enrolled_course' => esc_html__('Enrolled Course', BaseController::$text_domain),
                'instructor_name' => esc_html__('Instructor Name', BaseController::$text_domain),
                'total_amount'    => esc_html__('Total Amount', BaseController::$text_domain),
                'instructor_revenue'   => esc_html__('Instructor Revenue', BaseController::$text_domain),
                'enrolment_date'  => esc_html__('Enrolment Date', BaseController::$text_domain),
            ];
            $sortable_columns = [
                'enrolled_course' => ['enrolled_course', true],
                'instructor_name' => ['instructor_name', true],
                'total_amount' => ['total_amount', true],
                'instructor_revenue' => ['instructor_revenue', true],
                'enrolment_date' => ['enrolment_date', true]
            ];
        }

        $this->starting_timestamp = $starting_timestamp;
        $this->ending_timestamp   = $ending_timestamp;

        $page_size = 10;

        parent::__construct($args, $columns, $sortable_columns, $page_size);
    }


    public function get_data()
    {
        $revenue_reports = Payment::get_all($this->starting_timestamp, $this->ending_timestamp);

        $reports = array();

        foreach ($revenue_reports as $key => $revenue_report) {
            $course_details = get_post($revenue_report->payment_course_id);
            $reports[$key]['id'] = esc_html($revenue_report->payment_id);
            $reports[$key]['enrolled_course'] = esc_html($course_details->post_title);
            $reports[$key]['total_amount'] = Helper::currency(esc_html($revenue_report->payment_amount));
            $reports[$key]['admin_revenue'] = Helper::currency(esc_html($revenue_report->payment_admin_revenue));
            $reports[$key]['instructor_revenue'] = Helper::currency(esc_html($revenue_report->payment_instructor_revenue));
            $reports[$key]['instructor_name'] = esc_html(get_the_author_meta('display_name', esc_html($course_details->post_author)));
            $reports[$key]['enrolment_date'] = date('D, d-M-Y', esc_html($revenue_report->payment_date));
        }

        return $reports;
    }

    public function get_numbers($status = "all")
    {
        $user_obj = get_users(
            [
                'role' => BaseController::$custom_roles['student']['role'],
                'meta_key' => 'ly_status',
                'meta_value' => $status == "all" ? "" : $status
            ]
        );

        return count($user_obj);
    }
}
