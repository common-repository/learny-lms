<?php

/**
 * @package Learny
 */

namespace Learny\base\modules\gateways;

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Enrolment;


defined('ABSPATH') or die('You can not access the file directly');

class Paypal extends BaseController
{
    public static function paypal_payment($paymentID = "")
    {
        // LETS CHECK IF THE PAYMENT IS ALREADY HAPPENED OR NOT
        $table = self::$tables['payment'];
        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payment_paypal_pay_id` = %s", $paymentID));
        if ($result && count($result) > 0) {
            return false;
        }

        // IF IT IS A NEW PAYMENT, THEN DO IT
        $paypal_mode = esc_html(get_option('ly_paypal_mode', 'sandbox'));
        if ($paypal_mode == "sandbox") {
            $paypalClientID = esc_html(get_option('ly_paypal_sandbox_client_id', 'ly_paypal_sandbox_client_id'));
            $paypalSecret = esc_html(get_option('ly_paypal_sandbox_secret_key', 'ly_paypal_sandbox_secret_key'));
        } else {
            $paypalClientID = esc_html(get_option('ly_paypal_live_client_id', 'ly_paypal_live_client_id'));
            $paypalSecret = esc_html(get_option('ly_paypal_live_secret_key', 'ly_paypal_live_secret_key'));
        }

        if ($paypal_mode == 'sandbox') {
            $paypalURL = 'https://api.sandbox.paypal.com/v1/';
        } else {
            $paypalURL = 'https://api.paypal.com/v1/';
        }

        $auth = base64_encode($paypalClientID . ":" . $paypalSecret);
        $response = wp_remote_post(
            $paypalURL . 'oauth2/token',
            array(
                'method'      => 'POST',
                'timeout'     => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'blocking'    => true,
                'headers'     => array(
                    'Authorization' => "Basic $auth"
                ),
                'body'        => array(
                    'grant_type' => 'client_credentials'
                ),
                'cookies'     => array()
            )
        );

        if (wp_remote_retrieve_response_code($response) == 200) {
            $body  = json_decode(wp_remote_retrieve_body($response), true);
            $access_token = $body['access_token'];
            $payment_response = wp_remote_get(
                $paypalURL . 'payments/payment/' . $paymentID,
                array(
                    'method'  => 'GET',
                    'timeout' => 45,
                    'httpversion' => '1.0',
                    'headers' => array(
                        'Authorization' => 'Bearer ' . $access_token,
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/xml'
                    )
                )
            );

            if (wp_remote_retrieve_response_code($payment_response) == 200) {
                $payment_response_decoded = json_decode(wp_remote_retrieve_body($payment_response), true);
                // CHECK IF THE PAYMENT STATE IS APPROVED OR NOT
                if (isset($payment_response_decoded['state']) && $payment_response_decoded['state'] == 'approved') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function record_payment_data($course_id, $paymentID)
    {
        $table = self::$tables['payment'];
        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE `payment_paypal_pay_id` = %s", $paymentID));
        if ($result && count($result) > 0) {
            return false;
        } else {
            $course_details = get_post($course_id);
            $instructor_details = get_userdata($course_details->post_author);

            $data['payment_user_id'] = get_current_user_id();
            $data['payment_type'] = 'paypal';
            $data['payment_course_id'] = $course_id;
            $data['payment_amount'] = sanitize_text_field(get_post_meta($course_id, 'ly_course_price', true));
            $data['payment_date'] = strtotime(date('D, d-M-Y h:i:s'));
            $data['payment_paypal_pay_id'] = $paymentID;

            if ("administrator" == $instructor_details->roles) {
                $data['payment_admin_revenue'] = $data['payment_amount'];
                $data['payment_instructor_revenue'] = 0;
            } else {
                $instructor_revenue_percentage = esc_html(get_option('ly_instructor_revenue_percentage', 0));
                $data['payment_instructor_revenue'] = ceil(($data['payment_amount'] * $instructor_revenue_percentage) / 100);
                $data['payment_admin_revenue'] = $data['payment_amount'] - $data['payment_instructor_revenue'];
            }

            $wpdb->insert($table, $data);

            return Enrolment::enrol_after_payment($data['payment_user_id'], $data['payment_course_id']);
        }
    }
}
