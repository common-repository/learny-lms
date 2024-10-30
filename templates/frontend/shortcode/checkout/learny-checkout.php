<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$baseController = new BaseController();
$plugin_url = $baseController->plugin_url;
$paypal_active = esc_html(get_option('ly_paypal_active', false));
$plugin_path = $baseController->plugin_path;

$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$course_id = !empty($course_id) ? $course_id : 0;

$course_details = $course_id > 0 ? get_post($course_id) : array();
$is_free_course = esc_html(get_post_meta($course_id, 'ly_is_free_course', true));
$price = esc_html(get_post_meta($course_id, 'ly_course_price', true));

?>
<?php if ($course_id > 0 && count((array)$course_details) > 0) : ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 mb-2">
                <h4><?php esc_html_e('Order Details', BaseController::$text_domain); ?></h4>
                <h5>
                    <strong><?php esc_html_e('Course Title', BaseController::$text_domain); ?></strong> : <?php echo esc_html($course_details->post_title); ?>
                </h5>
                <h5>
                    <strong><?php esc_html_e('Course Price', BaseController::$text_domain); ?></strong> : <?php echo esc_html($is_free_course) ? esc_html_e('Free', BaseController::$text_domain) : Helper::currency($price); ?>
                </h5>
            </div>
            <div class="col-12">
                <h4><?php esc_html_e('Available Payment Gateways', BaseController::$text_domain); ?></h4>
                <?php include 'learny-payment-gateways.php'; ?>
                <button class="btn btn-secondary mt-2 float-end" onclick="redirectTo('<?php echo esc_js(get_permalink($course_id)) ?>')"><?php esc_html_e('Back to Courses', BaseController::$text_domain); ?></button>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <h4><?php esc_html_e('Invalid Course', BaseController::$text_domain); ?></h4>
            </div>
        </div>
    </div>
<?php endif; ?>


<script>
    "use strict";

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        jQuery('.ly_payment_gateway_radio').on('change', function() {
            let paymentGateway = this.value;
            jQuery('.ly_payment_gateway_form').hide();
            jQuery('.c' + paymentGateway).show();
        });
    }, false);
</script>