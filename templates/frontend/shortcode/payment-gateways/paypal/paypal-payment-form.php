<?php

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$course_id = !empty($course_id) ? $course_id : 0;
?>
<form action="<?php echo esc_url(\Learny\base\Helper::get_url("page-contains=checkout&payment-type=paypal&course-id=$course_id")); ?>" method="post" class="ly-payment-gateway-form ly-payment-gateway-paypal">
    <button type="submit" class="mxlms-payment-button mxlms-float-right"><?php esc_html_e('Pay By Paypal', BaseController::$text_domain); ?></button>
</form>