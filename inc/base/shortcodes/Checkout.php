<?php

/**
 * @package Learny
 */

namespace Learny\base\shortcodes;

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Enqueue;
use Learny\base\Helper;
use Learny\base\modules\gateways\Paypal;

class Checkout extends BaseController
{
    protected $enqueue;

    /**
     * DEFAULT CONSTRUCTOR
     */
    public function __construct()
    {
        parent::__construct();
        $this->enqueue = new Enqueue();
    }


    /**
     * THIS FUNCTION IS FOR REGISTERING SHORTCODES
     *
     * @return void
     */
    public function register()
    {
        add_shortcode('learny-checkout', array($this, 'render'));
    }


    /**
     * RENDER SHORTCODE VIEW
     *
     * @return void
     */
    public function render()
    {
        // CHECK IF THE USER IS LOGGED IN
        Auth::authenticate_login();

        $this->enqueue->public_assets();

        $page_contains = filter_input(INPUT_GET, 'page-contains', FILTER_SANITIZE_URL);
        if ($page_contains == "payment") {
            return $this->payment();
        } else {
            return $this->checkout();
        }
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR RENDERING CHECKOUT PAGES
     *
     * @return void
     */
    public function checkout()
    {
        $payment_type = filter_input(INPUT_GET, 'payment-type', FILTER_SANITIZE_URL);

        switch ($payment_type) {
            case "paypal":
                $template_path = Helper::view_path('payment-gateways/paypal/learny-paypal-checkout');
                break;
            case "stripe":
                $template_path = Helper::view_path('payment-gateways/stripe/learny-stripe-checkout');
                break;
            default:
                $template_path = Helper::view_path('checkout/learny-checkout');
                break;
        }

        ob_start();
        require_once($template_path);
        return ob_get_clean();
    }

    /**
     * THIS FUNCTION IS RESPONSIBLE FOR RENDERING PAYMENT PAGES
     *
     * @return void
     */
    public function payment()
    {
        $payment_type = filter_input(INPUT_GET, 'payment-type', FILTER_SANITIZE_URL);

        switch ($payment_type) {
            case "paypal":
                $paymentID = filter_input(INPUT_GET, 'payment_id', FILTER_SANITIZE_URL);
                $course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
                $status = Paypal::paypal_payment($paymentID);
                if ($status) {
                    Paypal::record_payment_data($course_id, $paymentID);
                }
                $template_path = $status ? Helper::view_path('checkout/learny-payment-success') : Helper::view_path('checkout/learny-payment-error');
                break;
            case "stripe":
                $template_path = Helper::view_path('payment-gateways/stripe/learny-stripe-checkout');
                break;
            default:
                $template_path = Helper::view_path('checkout/learny-checkout');
                break;
        }

        ob_start();
        require_once($template_path);
        return ob_get_clean();
    }
}
