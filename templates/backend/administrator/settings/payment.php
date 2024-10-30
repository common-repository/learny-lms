<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\modules\Settings;

?>

<div class="learny-row">
    <div class="learny-col">
        <div class="learny-panel learny-settings-page-title">
            <div class="learny-panel-body">
                <div class="learny-page-title-area">
                    <span class="learny-page-title">
                        <?php esc_html_e('Payment Settings', BaseController::$text_domain); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form update-payment-settings-form' enctype='multipart/form-data' autocomplete="off">

    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_settings'; ?>">
    <input type="hidden" name="task" value="update_payment_settings">
    <input type="hidden" name="update_payment_settings_nonce" value="<?php echo wp_create_nonce('update_payment_settings_nonce'); ?>">

    <h3>
        <?php esc_html_e('System Currency Settings', BaseController::$text_domain); ?>
    </h3>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_system_currency"> <?php esc_html_e('System Currency', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_system_currency" class="learny-select2" id="ly_system_currency">
                <?php
                $currencies = Settings::get_all_currencies();
                foreach ($currencies as $key => $currency) : ?>
                    <option value='<?php echo esc_attr($currency->symbol); ?>-<?php echo esc_attr($currency->code); ?>' <?php if (get_option('ly_system_currency', '$-USD') == ($currency->symbol . '-' . $currency->code)) echo 'selected'; ?> class="learny-disabled">
                        <?php echo esc_html($currency->code); ?> - <?php echo esc_html($currency->symbol); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_currency_position"> <?php esc_html_e('Currency Position', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_currency_position" class="learny-og-select" id="ly_currency_position">
                <option value="left" <?php if (get_option('ly_currency_position', 'left') == "left") echo 'selected'; ?>><?php esc_html_e('Left', BaseController::$text_domain); ?></option>
                <option value="right" <?php if (get_option('ly_currency_position', 'left') == "right") echo 'selected'; ?>><?php esc_html_e('Right', BaseController::$text_domain); ?></option>
                <option value="left-space" <?php if (get_option('ly_currency_position', 'left') == "left-space") echo 'selected'; ?>><?php esc_html_e('Left Space', BaseController::$text_domain); ?></option>
                <option value="right-space" <?php if (get_option('ly_currency_position', 'left') == "right-space") echo 'selected'; ?>><?php esc_html_e('Right Space', BaseController::$text_domain); ?></option>
            </select>
        </div>
    </div>

    <h3>
        <?php esc_html_e('Paypal Settings', BaseController::$text_domain); ?>
    </h3>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_paypal_active"> <?php esc_html_e('Active Paypal', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_paypal_active" class="learny-og-select" id="ly_paypal_active">
                <option value="1" <?php if (get_option('ly_paypal_active', 1) == 1) echo 'selected'; ?>><?php esc_html_e('Yes', BaseController::$text_domain); ?></option>
                <option value="0" <?php if (get_option('ly_paypal_active', 1) == 0) echo 'selected'; ?>><?php esc_html_e('No', BaseController::$text_domain); ?></option>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_paypal_mode"> <?php esc_html_e('Paypal Mode', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_paypal_mode" class="learny-og-select" id="ly_paypal_mode">
                <option value="sandbox" <?php if (get_option('ly_paypal_mode', 'sandbox') == "sandbox") echo 'selected'; ?>><?php esc_html_e('Sandbox', BaseController::$text_domain); ?></option>
                <option value="live" <?php if (get_option('ly_paypal_mode', 'sandbox') == "live") echo 'selected'; ?>><?php esc_html_e('Live', BaseController::$text_domain); ?></option>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_paypal_sandbox_client_id"><?php echo esc_html_e('Client id (Sandbox)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_paypal_sandbox_client_id" id="ly_paypal_sandbox_client_id" value="<?php echo esc_attr(get_option('ly_paypal_sandbox_client_id'), 'paypal-sandbox-client-id'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_paypal_sandbox_secret_key"><?php echo esc_html_e('Secret key (Sandbox)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_paypal_sandbox_secret_key" id="ly_paypal_sandbox_secret_key" value="<?php echo esc_attr(get_option('ly_paypal_sandbox_secret_key'), 'paypal-sandbox-secret-key'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_paypal_live_client_id"><?php echo esc_html_e('Client id (Production)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_paypal_live_client_id" id="ly_paypal_live_client_id" value="<?php echo esc_attr(get_option('ly_paypal_live_client_id'), 'paypal-live-client-id'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_paypal_live_secret_key"><?php echo esc_html_e('Secret key (Production)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_paypal_live_secret_key" id="ly_paypal_live_secret_key" value="<?php echo esc_attr(get_option('ly_paypal_live_secret_key'), 'paypal-live-secret-key'); ?>" ly-required="true">
        </div>
    </div>

    <h3>
        <?php esc_html_e('Stripe Settings', BaseController::$text_domain); ?>
    </h3>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_stripe_active"> <?php esc_html_e('Active Stripe', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_stripe_active" class="learny-og-select" id="ly_stripe_active">
                <option value="1" <?php if (get_option('ly_stripe_active', 1) == 1) echo 'selected'; ?>><?php esc_html_e('Yes', BaseController::$text_domain); ?></option>
                <option value="0" <?php if (get_option('ly_stripe_active', 1) == 0) echo 'selected'; ?>><?php esc_html_e('No', BaseController::$text_domain); ?></option>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_stripe_mode"> <?php esc_html_e('Stripe Mode', BaseController::$text_domain); ?></label>
        </div>
        <div class="learny-form-field">
            <select name="ly_stripe_mode" class="learny-og-select" id="ly_stripe_mode">
                <option value="sandbox" <?php if (get_option('ly_stripe_mode', 'sandbox') == "sandbox") echo 'selected'; ?>><?php esc_html_e('Sandbox', BaseController::$text_domain); ?></option>
                <option value="live" <?php if (get_option('ly_stripe_mode', 'sandbox') == "live") echo 'selected'; ?>><?php esc_html_e('Live', BaseController::$text_domain); ?></option>
            </select>
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_stripe_sandbox_secret_key"><?php echo esc_html_e('Secret Key (Sandbox)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_stripe_sandbox_secret_key" id="ly_stripe_sandbox_secret_key" value="<?php echo esc_attr(get_option('ly_stripe_sandbox_secret_key'), 'stripe-sandbox-secret-key'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_stripe_sandbox_public_key"><?php echo esc_html_e('Public key (Sandbox)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_stripe_sandbox_public_key" id="ly_stripe_sandbox_public_key" value="<?php echo esc_attr(get_option('ly_stripe_sandbox_public_key'), 'stripe-sandbox-public-key'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_stripe_live_secret_key"><?php echo esc_html_e('Secret Key (Production)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_stripe_live_secret_key" id="ly_stripe_live_secret_key" value="<?php echo esc_attr(get_option('ly_stripe_live_secret_key'), 'stripe-live-secret-key'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row learny-form-separator">
        <div class="learny-form-field-label">
            <label for="ly_stripe_live_public_key"><?php echo esc_html_e('Public key (Production)', BaseController::$text_domain); ?>
        </div>
        <div class="learny-form-field">
            <input type="text" name="ly_stripe_live_public_key" id="ly_stripe_live_public_key" value="<?php echo esc_attr(get_option('ly_stripe_live_public_key'), 'stripe-live-public-key'); ?>" ly-required="true">
        </div>
    </div>

    <div class="learny-form-field-row">
        <div class="learny-form-field">
            <button class="button button-primary"><?php esc_html_e('Update Payment Settings', BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>

<script>
    "use strict";

    jQuery(document).ready(function() {
        initSelect2(['#ly_system_currency']);

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.update-payment-settings-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        function validate() {
            var lyFormValidity = true;
            jQuery('form.update-payment-settings-form').find('input').each(function() {
                if (jQuery(this).attr('ly-required') && jQuery(this).val() === "") {
                    learnyNotify("<?php esc_html_e('Fill all the required fields', BaseController::$text_domain) ?>", 'warning');
                    lyFormValidity = false;
                    return lyFormValidity;
                }
            });

            return lyFormValidity;
        }

        function showResponse(response) {
            var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
            response = JSON.parse(response);
            if (response.status) {
                closeModal();
                learnyNotify(response.message, 'success');

            } else {
                learnyNotify(response.message, 'warning');
            }
        }
    });
</script>