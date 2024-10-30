<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Payment;

$purchase_history_id = filter_input(INPUT_GET, 'purchase-history-serial', FILTER_SANITIZE_URL);
$purchase_history_id = (int) $purchase_history_id ? (int) $purchase_history_id : 0;
$purchase_history_details = Payment::get_purchase_history_by_id($purchase_history_id);
$user_details = get_userdata($purchase_history_details->payment_user_id);
$course_details = get_post($purchase_history_details->payment_course_id);
$instructor_name = esc_html(get_the_author_meta('display_name', $course_details->post_author));
?>


<style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }

    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
        font-size: 25px;
        line-height: 45px;
        color: #333;
    }

    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }

    .invoice-box table tr.item td {
        border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
        border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }

        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }

    /** RTL **/
    .invoice-box.rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .invoice-box.rtl table {
        text-align: right;
    }

    .invoice-box.rtl table tr td:nth-child(2) {
        text-align: left;
    }
</style>

<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td class="title">
                            <!-- <img src="https://www.sparksuite.com/images/logo.png" style="width: 100%; max-width: 300px" /> -->
                            <?php echo esc_html(get_option('ly_business_name', esc_html__('Learny LMS', BaseController::$text_domain))); ?>
                        </td>

                        <td>
                            <?php esc_html_e('Invoice', BaseController::$text_domain); ?> #: <?php echo esc_html(sprintf("%06d", $purchase_history_details->payment_id)); ?><br />
                            <?php echo esc_html_e('Payment Date', BaseController::$text_domain); ?> : <?php echo date('D, d-M-Y', $purchase_history_details->payment_date); ?><br />
                            <?php esc_html_e('Printed At', BaseController::$text_domain); ?> : <?php echo date('D, d-M-Y'); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <?php esc_html_e('Business Details', BaseController::$text_domain); ?><br />
                            <?php esc_html_e('Name', BaseController::$text_domain); ?> : <?php echo esc_html(get_option('ly_business_name', esc_html__('Learny LMS', BaseController::$text_domain))); ?>.<br />
                            <?php esc_html_e('Email', BaseController::$text_domain); ?> : <?php echo esc_html(get_option('ly_business_email', esc_html__('Learny LMS', BaseController::$text_domain))); ?><br />
                            <?php esc_html_e('Phone', BaseController::$text_domain); ?> : <?php echo esc_html(get_option('ly_business_phone', esc_html__('Learny LMS', BaseController::$text_domain))); ?>
                        </td>

                        <td>
                            <?php esc_html_e('Customer Details', BaseController::$text_domain); ?><br />
                            <?php esc_html_e('Name', BaseController::$text_domain); ?> : <?php echo esc_html($user_details->display_name); ?>.<br />
                            <?php esc_html_e('Email', BaseController::$text_domain); ?> : <?php echo esc_html($user_details->user_email); ?>.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td><?php esc_html_e('Payment Method', BaseController::$text_domain); ?></td>

            <td><?php esc_html_e('Status', BaseController::$text_domain); ?></td>
        </tr>

        <tr class="details">
            <td><?php echo ucfirst(esc_html($purchase_history_details->payment_type)); ?></td>

            <td>
                <?php echo esc_html__('Paid', BaseController::$text_domain); ?>
            </td>
        </tr>

        <tr class="heading">
            <td><?php esc_html_e('Item', BaseController::$text_domain); ?></td>

            <td><?php esc_html_e('Price', BaseController::$text_domain); ?></td>
        </tr>

        <tr class="item">
            <td>
                <?php echo esc_html($course_details->post_title); ?><br>
                <small> <?php esc_html_e('by', BaseController::$text_domain); ?> <strong><?php echo esc_html($instructor_name); ?></strong></small>
            </td>

            <td>
                <?php echo Helper::currency(esc_html($purchase_history_details->payment_amount)); ?>
            </td>
        </tr>
    </table>
</div>