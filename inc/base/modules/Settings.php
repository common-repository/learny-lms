<?php

/**
 * @package Learny
 */

namespace Learny\base\modules;

use Learny\base\BaseController;
use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');

class Settings extends BaseController
{
    // Method for registering form submission hook to this plugin
    public function register()
    {
        add_action('admin_post_' . self::$plugin_id . '_settings', array($this, 'post'));
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
            case 'update_general_settings':
                $this->update_general_settings();
                break;
            case 'update_instructor_settings':
                $this->update_instructor_settings();
                break;
            case 'update_payment_settings':
                $this->update_payment_settings();
                break;
            case 'update_page_settings':
                $this->update_page_settings();
                break;
            case 'update_api_settings':
                $this->update_api_settings();
                break;
            case 'update_live_class_settings':
                $this->update_live_class_settings();
                break;
        }
    }

    private function update_general_settings()
    {
        if (self::verify_nonce('update_general_settings_nonce') == true) {

            // VALIDATING EMAIL
            if (!Helper::validate_email(sanitize_text_field($_POST['ly_business_email']))) {
                echo json_encode(['status' => false, 'message' => esc_html__("Invalid Email", BaseController::$text_domain)]);
                return;
            }

            $data['ly_business_name']               = sanitize_text_field($_POST['ly_business_name']);
            $data['ly_business_email']              = sanitize_text_field($_POST['ly_business_email']);
            $data['ly_business_phone']              = sanitize_text_field($_POST['ly_business_phone']);
            $data['ly_purchase_code']              = sanitize_text_field($_POST['ly_purchase_code']);
            $data['ly_system_logo_url']              = sanitize_text_field($_POST['ly_system_logo_url']);
            self::update_option_values($data);
            echo json_encode(['status' => true, 'message' => esc_html__("General Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_page_settings()
    {
        if (self::verify_nonce('update_page_settings_nonce') == true) {

            $data['ly_checkout_page'] = sanitize_text_field($_POST['ly_checkout_page']);
            $data['ly_dashboard_page'] = sanitize_text_field($_POST['ly_dashboard_page']);
            $data['ly_course_player_page'] = sanitize_text_field($_POST['ly_course_player_page']);
            $data['ly_auth_page'] = sanitize_text_field($_POST['ly_auth_page']);
            self::update_option_values($data);
            echo json_encode(['status' => true, 'message' => esc_html__("Page Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_instructor_settings()
    {
        if (self::verify_nonce('update_instructor_settings_nonce') == true) {
            $data['ly_public_instructor']       = sanitize_text_field($_POST['ly_public_instructor']);
            $data['ly_instructor_application_note']   = sanitize_text_field($_POST['ly_instructor_application_note']);
            $data['ly_instructor_revenue_percentage'] = (sanitize_text_field($_POST['ly_instructor_revenue_percentage']) >= 0 && sanitize_text_field($_POST['ly_instructor_revenue_percentage']) <= 100) ? sanitize_text_field($_POST['ly_instructor_revenue_percentage']) : 0;
            $data['ly_admin_revenue_percentage'] = ($data['ly_instructor_revenue_percentage'] >= 0 && $data['ly_instructor_revenue_percentage'] <= 100) ? 100 - $data['ly_instructor_revenue_percentage'] : 100;
            self::update_option_values($data);
            echo json_encode(['status' => true, 'message' => esc_html__("Instructor Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_api_settings()
    {
        if (self::verify_nonce('update_api_settings_nonce') == true) {
            $data['ly_youtube_api_key']       = sanitize_text_field($_POST['ly_youtube_api_key']);
            $data['ly_vimeo_api_key']   = sanitize_text_field($_POST['ly_vimeo_api_key']);
            self::update_option_values($data);
            echo json_encode(['status' => true, 'message' => esc_html__("API Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_payment_settings()
    {
        if (self::verify_nonce('update_payment_settings_nonce') == true) {
            $system_payment_data['ly_system_currency'] = sanitize_text_field($_POST['ly_system_currency']);
            $system_payment_data['ly_currency_position'] = sanitize_text_field($_POST['ly_currency_position']);
            $system_payment_data['ly_paypal_active'] = sanitize_text_field($_POST['ly_paypal_active']);
            $system_payment_data['ly_paypal_mode'] = sanitize_text_field($_POST['ly_paypal_mode']);
            $system_payment_data['ly_paypal_sandbox_client_id'] = sanitize_text_field($_POST['ly_paypal_sandbox_client_id']);
            $system_payment_data['ly_paypal_sandbox_secret_key'] = sanitize_text_field($_POST['ly_paypal_sandbox_secret_key']);
            $system_payment_data['ly_paypal_live_client_id'] = sanitize_text_field($_POST['ly_paypal_live_client_id']);
            $system_payment_data['ly_paypal_live_secret_key'] = sanitize_text_field($_POST['ly_paypal_live_secret_key']);
            $system_payment_data['ly_stripe_active'] = sanitize_text_field($_POST['ly_stripe_active']);
            $system_payment_data['ly_stripe_mode'] = sanitize_text_field($_POST['ly_stripe_mode']);
            $system_payment_data['ly_stripe_sandbox_secret_key'] = sanitize_text_field($_POST['ly_stripe_sandbox_secret_key']);
            $system_payment_data['ly_stripe_sandbox_public_key'] = sanitize_text_field($_POST['ly_stripe_sandbox_public_key']);
            $system_payment_data['ly_stripe_live_secret_key'] = sanitize_text_field($_POST['ly_stripe_live_secret_key']);
            $system_payment_data['ly_stripe_live_public_key'] = sanitize_text_field($_POST['ly_stripe_live_public_key']);
            self::update_option_values($system_payment_data);
            echo json_encode(['status' => true, 'message' => esc_html__("Payment Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }

    private function update_live_class_settings()
    {
        if (self::verify_nonce('update_live_class_settings_nonce') == true) {
            $live_class_data['ly_zoom_api_key']       = sanitize_text_field($_POST['ly_zoom_api_key']);
            $live_class_data['ly_zoom_secret_key']   = sanitize_text_field($_POST['ly_zoom_secret_key']);
            self::update_option_values($live_class_data);
            echo json_encode(['status' => true, 'message' => esc_html__("Live Class Settings Updated Successfully", BaseController::$text_domain)]);
        }
    }


    public static function get_all_currencies()
    {
        global $wpdb;
        $table = self::$tables['currencies'];
        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table"));
        return $result;
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR UPDATING OPTION VALUES
     *
     * @param array $options
     * @return void
     */
    public static function update_option_values(array $options)
    {
        foreach ($options as $key => $value) {
            update_option($key, $value);
        }
    }
}
