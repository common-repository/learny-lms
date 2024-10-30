<?php

use Learny\base\Helper;

defined('ABSPATH') or die('You can not access the file directly');
?>
<?php if ($paypal_active) : ?>
    <label class="learny-payment-gateway-radio">
        <input type="radio" name="ly_payment_gateway" class="ly_payment_gateway_radio" value="paypal" />
        <div class="learny-payment-gateway-image" style="background-image: url('<?php echo esc_url($plugin_url . 'assets/public/images/paypal.png'); ?>')"></div>
    </label>
<?php endif; ?>

<?php include Helper::view_path('payment-gateways/paypal/paypal-payment-form'); ?>