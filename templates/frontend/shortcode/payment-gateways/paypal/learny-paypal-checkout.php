<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$course_id = !empty($course_id) ? $course_id : 0;

$course_details = get_post($course_id);
$is_free_course = esc_html(get_post_meta($course_id, 'ly_is_free_course', true));
$price = esc_html(get_post_meta($course_id, 'ly_course_price', true));

$logged_in_user_details = wp_get_current_user();

$paypal_mode = esc_html(get_option('ly_paypal_mode'));

$currency = esc_html(get_option('ly_system_currency'));

$currency_symbol = "$";
$currency_code = "USD";

if (isset($currency) && !empty($currency)) {
    $currency_data = explode('-', $currency);
    $currency_symbol = $currency_data[0];
    $currency_code = $currency_data[1];
}

if ($paypal_mode == "sandbox") {
    $paypalClientID = esc_html(get_option('ly_paypal_sandbox_client_id'));
    $paypalSecret = esc_html(get_option('ly_paypal_sandbox_secret_key'));
} else {
    $paypalClientID = esc_html(get_option('ly_paypal_live_client_id'));
    $paypalSecret = esc_html(get_option('ly_paypal_live_secret_key'));
}

$checkout_page = get_option('ly_checkout_page', 0) ? esc_url_raw(get_permalink(get_option('ly_checkout_page', 0))) : esc_url_raw(site_url());
?>

<div class="container">
    <div class="row text-center">
        <div class="col-lg-12">
            <div class="learny-package-details">
                <strong><?php esc_html_e('Course Name', BaseController::$text_domain); ?> | <?php echo esc_html($course_details->post_title); ?></strong> <br>
                <strong><?php esc_html_e('Student Name', BaseController::$text_domain); ?> | <?php echo esc_html($logged_in_user_details->display_name); ?></strong> <br>
                <strong><?php esc_html_e('Amount to pay', BaseController::$text_domain); ?> | <?php echo esc_html($currency_symbol) . '' . $price; ?></strong> <br>
                <div id="paypal-button" class="mt-4"></div><br>
                <button class="btn btn-secondary" onclick="redirectTo('<?php echo esc_js(Helper::get_url('course-id=' . $course_id)) ?>')"><?php esc_html_e('Back to checkout', BaseController::$text_domain); ?></button>
            </div>
        </div>
    </div>
</div>

<script>
    "use strict";

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            paypal.Button.render({
                env: '<?php echo esc_js($paypal_mode); ?>', // 'sandbox' or 'production'
                style: {
                    label: 'paypal',
                    size: 'medium', // small | medium | large | responsive
                    shape: 'rect', // pill | rect
                    color: 'blue', // gold | blue | silver | black
                    tagline: false
                },
                client: {
                    sandbox: '<?php echo esc_js($paypalClientID); ?>',
                    production: '<?php echo esc_js($paypalSecret); ?>'
                },

                commit: true, // Show a 'Pay Now' button

                payment: function(data, actions) {
                    return actions.payment.create({
                        payment: {
                            transactions: [{
                                amount: {
                                    total: '20',
                                    currency: '<?php echo esc_js($currency_code); ?>'
                                }
                            }]
                        }
                    });
                },

                onAuthorize: function(data, actions) {
                    // executes the payment
                    return actions.payment.execute().then(function() {
                        var redirectUrl = '<?php echo esc_url_raw(add_query_arg(array('page-contains' => 'payment', 'status' => 'success', 'payment-type' => 'paypal', 'course-id' => $course_id), esc_url($checkout_page))); ?>' + '&payment_id=' + data.paymentID + '&payment_token=' + data.paymentToken + '&payer_id=' + data.payerID;
                        window.location = redirectUrl;
                    });
                }

            }, '#paypal-button');
        }, 500);
    }, false);
</script>