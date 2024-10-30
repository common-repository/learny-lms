<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Payouts;

$total_pending_amount  = Payouts::get_total_pending_amount(get_current_user_id());
$requested_withdrawals = Payouts::get_requested_withdrawals(get_current_user_id());

$current_user_role = Helper::get_current_user_role();
?>
<?php if (count((array)$requested_withdrawals) > 0) : ?>
    <div class="learny-alert learny-alert-warning" role="alert">
        <h4 class="learny-alert-heading"><?php esc_html_e('Oops!', BaseController::$text_domain); ?></h4>
        <p><strong><?php esc_html_e('You already requested a withdrawal', BaseController::$text_domain); ?></strong></p>
        <p><?php esc_html_e('If you want to make another', BaseController::$text_domain); ?>, <?php esc_html_e('You have to delete the requested one first', BaseController::$text_domain); ?></p>
    </div>
<?php elseif ($total_pending_amount == 0) : ?>
    <div class="learny-alert learny-alert-warning" role="learny-alert">
        <h4 class="learny-alert-heading"><?php esc_html_e('Oops!', BaseController::$text_domain); ?></h4>
        <p><strong><?php esc_html_e('You got nothing to withdraw', BaseController::$text_domain); ?></strong></p>
    </div>
<?php else : ?>

    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form request-withdrawal-form' enctype='multipart/form-data' autocomplete="off">
        <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_payout'; ?>">
        <input type="hidden" name="task" value="request_withdrawal">
        <input type="hidden" name="request_withdrawal_nonce" value="<?php echo wp_create_nonce('request_withdrawal_nonce'); ?>"> <!-- kind of csrf token-->

        <div class="learny-form-group">
            <label for="payout_amount"><?php esc_html_e('Withdrawal Amount', BaseController::$text_domain); ?><span class='learny-text-danger'>*</span></label>
            <input type="number" name="payout_amount" class="form-control" id="payout-amount" aria-describedby="payout_amount" placeholder="<?php esc_html_e('Withdrawal amount has to be less than ', BaseController::$text_domain); ?> <?php echo Helper::currency(esc_html($total_pending_amount)); ?>" min=0 max="<?php echo Helper::currency(esc_html($total_pending_amount)); ?>">
            <strong id="payout-amount-help" class="learny-text-danger"><?php esc_html_e('N.B : Withdrawal amount has to be less than ', BaseController::$text_domain); ?> <?php echo Helper::currency(esc_html($total_pending_amount)); ?></strong>
        </div>

        <div class="learny-custom-modal-action-footer">
            <div class="learny-custom-modal-actions">
                <button type="button" class="learny-btn learny-btn-secondary learny-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
                <button type="submit" class="learny-btn learny-btn-primary learny-btn-md"><?php esc_html_e("Submit", BaseController::$text_domain) ?></button>
            </div>
        </div>
    </form>
<?php endif; ?>

<script>
    "use strict";

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.request-withdrawal-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    });

    function validate() {
        var payout_amount = jQuery('#payout-amount').val();

        if (payout_amount === '') {

            learnyNotify("<?php esc_html_e('Invalid Amount', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.request-withdrawal-form').trigger('reset');
            closeModal();
            learnyNotify(response.message, 'success');
            learnyMakeAjaxCall('templates/backend/<?php echo esc_js($current_user_role); ?>/payout/list', 'instructor-payout-list-area');
        } else {
            learnyNotify(response.message, 'warning');
        }
    }
</script>