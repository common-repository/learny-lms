<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Payment extends BaseController
{

    /**
     * THIS FUNCTION RETURNS ALL THE PURCHASE HISTORY DATA
     *
     * @return object
     */
    public static function get_all($starting_timestamp = "", $ending_timestamp = "")
    {
        $table = self::$tables['payment'];
        global $wpdb;

        if (!empty($starting_timestamp) && !empty($ending_timestamp)) {
            if (Helper::if_admin_logged_in()) {
                $payments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payment_date` BETWEEN %d AND %d", $starting_timestamp, $ending_timestamp));
            } else {
                $course_ids = Instructor::get_instructor_course_ids();
                $course_ids = implode(',', $course_ids);
                $payments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payment_date` BETWEEN %d AND %d AND `payment_course_id` IN (%1s)", $starting_timestamp, $ending_timestamp, $course_ids));
            }
        } else {
            if (Helper::if_admin_logged_in()) {
                $payments = $wpdb->get_results("SELECT * FROM $table");
            } else {
                $course_ids = Instructor::get_instructor_course_ids();
                $course_ids = implode(',', $course_ids);
                $payments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payment_course_id` IN (%s)", $course_ids));
            }
        }

        return $payments;
    }


    /**
     * GETTING USER WISE PURCHASE HISTORY
     *
     * @return object
     */
    public static function get_user_wise_purchase_history()
    {
        $table = self::$tables['payment'];
        global $wpdb;
        $user_id = get_current_user_id();
        $payments = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payment_user_id` = %d", $user_id));
        return $payments;
    }

    /**
     * GET SINGLE ROW OF A PURCHASE HISTORY
     *
     * @param integer $id
     * @return object
     */
    public static function get_purchase_history_by_id(int $id)
    {
        $table = self::$tables['payment'];
        global $wpdb;
        $payment = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE `payment_id` = %d", $id));
        return $payment;
    }


    /**
     * GETTING TOTAL OF A SPECIFIC INSTRUCTOR REVENUE
     *
     * @param string $instructor_id
     * @return int
     */
    public static function get_total_instructor_revenue($instructor_id = "")
    {
        $course_ids = Instructor::get_instructor_course_ids();

        $total_revenue_amount = 0;

        if (count($course_ids)) {
            $course_ids = implode(',', esc_sql($course_ids));
            global $wpdb;
            $table = self::$tables['payment'];

            $result = $wpdb->get_results("SELECT * FROM $table WHERE `payment_course_id` IN ($course_ids) ORDER BY `payment_date` ASC");

            foreach ($result as $row) {
                $total_revenue_amount = $total_revenue_amount + $row->payment_instructor_revenue;
            }

            return $total_revenue_amount;
        }

        return $total_revenue_amount;
    }
}
