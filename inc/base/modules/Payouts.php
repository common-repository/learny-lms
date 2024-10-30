<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Payouts extends BaseController
{

    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_payout', array($this, 'post'));
    }

    // Main method for handling all the post data submitted during a form submission
    public function post()
    {
        $task = sanitize_text_field($_POST['task']);
        $this->handle_posts($task);
    }

    // Method for handling form submission according to task of the form
    public function handle_posts($task)
    {
        switch ($task) {
            case 'request_withdrawal':
                $this->request_withdrawal();
                break;
            case 'toggle_status':
                $this->toggle_status();
                break;
            case 'payout_settings':
                $this->payout_settings();
                break;
            case 'delete':
                $this->delete_withdrawal();
                break;
        }
    }

    /**
     * REQUESTING A WITHDRAWAL
     *
     * @return void
     */
    public static function request_withdrawal()
    {
        if (self::verify_nonce('request_withdrawal_nonce') == true) {

            // VALIDATING AMOUNT
            if (!Helper::validate_positive_number(sanitize_text_field($_POST['payout_amount']))) {
                echo json_encode(['status' => false, 'message' => esc_html__("Invalid Payout Amount", BaseController::$text_domain)]);
                return;
            }

            $instructor_id  = sanitize_text_field(get_current_user_id());
            $total_pending_amount = self::get_total_pending_amount($instructor_id);
            $data['payout_amount']  = sanitize_text_field($_POST['payout_amount']);
            $data['payout_instructor_id']  = sanitize_text_field($instructor_id);
            $data['payout_date_added']  = strtotime(date('D, d-M-Y'));
            $data['payout_status']  = 0;
            if ($total_pending_amount >= $data['payout_amount']) {
                global $wpdb;
                $wpdb->insert(self::$tables['payout'], $data);
                echo json_encode(['status' => true, 'message' => esc_html__("Payout requested successfully", BaseController::$text_domain)]);
            } else {
                echo json_encode(['status' => false, 'message' => esc_html__("Invalid Payout Request", BaseController::$text_domain)]);
            }
        }
    }

    /**
     * DELETING A WITHDRAWAL
     *
     * @return void
     */
    public static function delete_withdrawal()
    {
        if (self::verify_nonce('confirmation_form_nonce') == true) {
            global $wpdb;
            $table = self::$tables['payout'];
            $instructor_id = get_current_user_id();
            $checker = ['payout_instructor_id' => $instructor_id, 'payout_status' => 0];
            $wpdb->delete($table, $checker);
            echo json_encode(['status' => true, 'message' => esc_html__("Requested Withdrawal Deleted Successfully", BaseController::$text_domain)]);
        }
    }

    /**
     * THIS FUNCTION RETURNS ALL THE PURCHASE HISTORY DATA
     *
     * @return object
     */
    public static function get_all()
    {
        $table = self::$tables['payout'];
        global $wpdb;

        if (Helper::if_admin_logged_in()) {
            $payouts = $wpdb->get_results("SELECT * FROM $table ORDER BY `payout_date_added` DESC");
        } else {
            $instructor_id = get_current_user_id();
            $payouts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payout_instructor_id` = %d ORDER BY `payout_date_added` DESC", $instructor_id));
        }

        return $payouts;
    }

    /**
     * GET SINGLE ROW OF A PAYOUT
     *
     * @param integer $id
     * @return object
     */
    public static function get_payout_by_id(int $id)
    {
        $table = self::$tables['payout'];
        global $wpdb;
        $payout = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE `payout_id` = %d ORDER BY `payout_date_added` DESC", $id));
        return $payout;
    }

    // PAYOUT SETTINGS
    public static function payout_settings()
    {
        // Add paypal keys
        $paypal_info = array(
            'production_client_id' => sanitize_text_field($_POST['paypal_client_id']),
            'production_secret_key' => sanitize_text_field($_POST['paypal_secret_key'])
        );
        $data['paypal_keys'] = json_encode($paypal_info);
        global $wpdb;
        $wpdb->update(self::$tables['users'], $data, array('id' => get_current_user_id()));
        echo json_encode(['status' => true, 'message' => esc_html__("Payout Settings Updated Successfully", BaseController::$text_domain)]);
    }

    // GET TOTAL PENDING AMOUNT OF AN INSTRUCTOR
    public static function get_total_pending_amount($instructor_id = "")
    {
        $total_revenue = Payment::get_total_instructor_revenue($instructor_id);
        $total_payouts = Payouts::get_total_payout_amount($instructor_id);
        $total_pending_amount = $total_revenue - $total_payouts;
        return $total_pending_amount;
    }


    public static function get_total_payout_amount($instructor_id)
    {
        $total_payout_amount = 0;
        global $wpdb;
        $table = self::$tables['payout'];
        $payouts = array();
        $payouts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payout_instructor_id` = %d AND `payout_status` = 1 ORDER BY `payout_date_added` DESC", $instructor_id));
        foreach ($payouts as $payout) {
            $total_payout_amount = $total_payout_amount + $payout->payout_amount;
        }

        return $total_payout_amount;
    }

    // GET REQUESTED WITHDRAWALS OF AN INSTRUCTOR
    public static function get_requested_withdrawals($instructor_id = "")
    {
        global $wpdb;
        $table = self::$tables['payout'];
        $payouts = array();
        $payouts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payout_instructor_id` = %d AND `payout_status` = 0 ORDER BY `payout_date_added` DESC", $instructor_id));
        return $payouts[0];
    }

    /**
     * TOGGLING WITHDRAWAL REQUEST
     *
     * @return void
     */
    public static function toggle_status()
    {
        global $wpdb;
        $table = self::$tables['payout'];

        if (self::verify_nonce('confirmation_form_nonce') == true) {
            $payout_id = sanitize_text_field($_POST['id']);
            $data['payout_last_modified'] = strtotime(date('D, d-M-Y'));
            $data['payout_status'] = 1;
            $wpdb->update($table, $data, array('payout_id' => $payout_id));

            echo json_encode(['status' => true, 'message' => esc_html__("Payout Updated Successfully", BaseController::$text_domain)]);
        }
    }
}
