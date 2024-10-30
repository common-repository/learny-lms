"use strict";
jQuery(function ($) {
    jQuery('.ly_payment_gateway_radio').on('change', function () {
        let selectedPaymentGateway = jQuery(this).val();
        jQuery('.ly-payment-gateway-form').hide();
        jQuery('.ly-payment-gateway-' + selectedPaymentGateway).show();
    });

});
