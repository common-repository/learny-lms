<?php

/**
 * @package Learny
 */

namespace Learny\table;

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\table\Base_Table;
use Learny\base\modules\Payouts;

defined('ABSPATH') or die('You can not access the file directly');

class Payout extends Base_Table
{
    public $order = "asc";
    public $orderby = "payout_date_added";

    /**
     * DEFAULT CONSTRUCTOR
     *
     * @param array $args
     */

    public function __construct($args = array())
    {
        $columns = [
            'cb'              => '<input type="checkbox">',
            'instructor' => esc_html__('Instructor Name', BaseController::$text_domain),
            'payout_amount' => esc_html__('Payout Amount', BaseController::$text_domain),
            'status'    => esc_html__('Status', BaseController::$text_domain),
            'payout_requested_date'   => esc_html__('Payout Requested Date', BaseController::$text_domain),
            'action'  => esc_html__('Action', BaseController::$text_domain),
        ];
        $sortable_columns = [
            'instructor' => ['instructor', true],
            'payout_amount' => ['payout_amount', true],
            'status' => ['status', true],
            'payout_requested_date' => ['payout_requested_date', true]
        ];

        $page_size = 10;

        parent::__construct($args, $columns, $sortable_columns, $page_size);
    }


    public function get_data()
    {
        $payouts_history = Payouts::get_all();

        $payouts = array();

        foreach ($payouts_history as $key => $payout) {
            $instructor_details = get_userdata($payout->payout_instructor_id);
            $payouts[$key]['id'] = esc_html($payout->payout_id);
            $payouts[$key]['instructor'] = esc_html($instructor_details->display_name);
            $payouts[$key]['payout_amount'] = Helper::currency(esc_html($payout->payout_amount));
            $payouts[$key]['payout_status'] = esc_html($payout->payout_status);
            $payouts[$key]['status'] = $payout->payout_status ? "<span class='learny-badge learny-badge-success'>" . esc_html__('Paid', BaseController::$text_domain) . "</span>" : "<span class='learny-badge learny-badge-danger'>" . esc_html__('Unpaid', BaseController::$text_domain) . "</span>";
            $payouts[$key]['payout_requested_date'] = date('D, d-M-Y', esc_html($payout->payout_date_added));
        }

        return $payouts;
    }

    public function column_action($item)
    {
        $current_user_role = Helper::get_current_user_role();
        $redirect_to = esc_url_raw(admin_url('admin.php?page=learny-payout'));
        $payout_id = $item['id'];

        $delete_title = esc_html__('Delete payout', BaseController::$text_domain);
        $update_status_title = $item['payout_status'] ? esc_html__('Paid Payout', BaseController::$text_domain) : esc_html__('Mark payout As Paid', BaseController::$text_domain);
        $update_status_icon = $item['payout_status'] ? "dashicons-yes" : "dashicons-clock";

?>
        <div class="learny-table-action-btn">
            <?php if ($current_user_role == "administrator" && $item['payout_status'] == 0) : ?>
                <a href="javascript:void(0)" type='button' class='button' learny-tooltip="<?php echo esc_attr($update_status_title); ?>" onclick="present_confirmation_modal('<?php echo esc_js($update_status_title); ?>', null, 'toggle_status', '<?php echo esc_js($payout_id); ?>', 'payout', 'templates/backend/<?php echo esc_attr($current_user_role); ?>/payout/list', 'instructor-payout-list-area');">
                    <i class='dashicons <?php echo esc_attr($update_status_icon); ?>'></i>
                </a>
            <?php endif; ?>

            <?php if ($item['payout_status'] == 0) : ?>
                <a href="javascript:void(0)" type='button' class='button' learny-tooltip="<?php echo esc_attr($delete_title); ?>" onclick="present_confirmation_modal('<?php echo esc_js($delete_title); ?>', '<?php echo esc_url($redirect_to); ?>', 'delete', '<?php echo esc_js($payout_id); ?>', 'payout', 'templates/backend/<?php echo esc_attr($current_user_role); ?>/payout/list', 'instructor-payout-list-area');">
                    <i class='dashicons dashicons-no-alt'></i>
                </a>
            <?php endif; ?>
        </div>
<?php
    }
}
