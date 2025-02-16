<?php

/**
 * @package Learny
 */

namespace Learny\table;

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\table\Base_Table;

defined('ABSPATH') or die('You can not access the file directly');

class Student extends Base_Table
{

    public $order = "asc";
    public $orderby = "name";

    /**
     * DEFAULT CONSTRUCTOR
     *
     * @param array $args
     */
    public function __construct($args = array())
    {
        if ("administrator" == Helper::get_current_user_role()) {
            $columns = [
                'cb' => '<input type="checkbox">',
                'name' => esc_html__('Name', BaseController::$text_domain),
                'email' => esc_html__('Email', BaseController::$text_domain),
                'status' => esc_html__('Status', BaseController::$text_domain),
                'action' => esc_html__('Action', BaseController::$text_domain),
            ];
        } else {
            $columns = [
                'name' => esc_html__('Name', BaseController::$text_domain),
                'email' => esc_html__('Email', BaseController::$text_domain),
            ];
        }


        $sortable_columns = [
            'name' => ['name', true],
            'email' => ['email', true],
            'status' => ['status', true],
        ];

        $page_size = 10;

        parent::__construct($args, $columns, $sortable_columns, $page_size);
    }

    protected function get_views()
    {
        $all_student_title = esc_html__("All", BaseController::$text_domain);
        $all_student_url = esc_url_raw(admin_url('admin.php?page=learny-student'));
        $approved_student_title = esc_html__("Approved", BaseController::$text_domain);
        $approved_student_url = esc_url_raw(admin_url('admin.php?page=learny-student&filter_status=approved'));
        $pending_student_title = esc_html__("Pending", BaseController::$text_domain);
        $pending_student_url = esc_url_raw(admin_url('admin.php?page=learny-student&filter_status=pending'));
        $number_of_all_users = $this->get_numbers("all");
        $number_of_approved_users = $this->get_numbers(1);
        $number_of_pending_users = $this->get_numbers(0);
        $status_links = array(
            "all"       => "<a class='learny-black' href='$all_student_url'>$all_student_title ($number_of_all_users)</a>",
            "published" => "<a href='$approved_student_url'>$approved_student_title ($number_of_approved_users)</a>",
            "trashed"   => "<a href='$pending_student_url'>$pending_student_title ($number_of_pending_users)</a>",
        );

        return "administrator" == Helper::get_current_user_role() ? $status_links : array();
    }

    public function column_status($item)
    {
        return $item['status'] ? esc_html__('Approved', BaseController::$text_domain) : esc_html__('Pending', BaseController::$text_domain);
    }

    public function column_action($item)
    {
        $current_user_role = Helper::get_current_user_role();
        $redirect_to = esc_url_raw(admin_url('admin.php?page=learny-student'));
        $user_id = $item['id'];
        $delete_title = esc_html__('Delete student', BaseController::$text_domain);
        $update_status_title = $item['status'] ? esc_html__('Mark student As Pending', BaseController::$text_domain) : esc_html__('Mark student As Approved', BaseController::$text_domain);
        $update_status_icon = $item['status'] ? "dashicons-clock" : "dashicons-saved";
        $update_title = esc_html__('Update student', BaseController::$text_domain);
        $user_edit_url = esc_url_raw(get_edit_user_link($user_id));
?>
        <div class="learny-table-action-btn">
            <a href="javascript:void(0)" type='button' class='button' learny-tooltip="<?php echo esc_attr($update_status_title); ?>" onclick="present_confirmation_modal('<?php echo esc_js($update_status_title); ?>', null, 'toggle_status', '<?php echo esc_js($user_id); ?>', 'student', 'templates/backend/<?php echo esc_attr($current_user_role); ?>/student/list', 'learny-student-list-area');">
                <i class='dashicons <?php echo esc_attr($update_status_icon); ?>'></i>
            </a>
            <a href="<?php echo esc_url($user_edit_url); ?>" type='button' class='button' learny-tooltip="<?php echo esc_attr($update_title); ?>">
                <i class='dashicons dashicons-edit'></i>
            </a>
            <a href="javascript:void(0)" type='button' class='button' learny-tooltip="<?php echo esc_attr($delete_title); ?>" onclick="present_confirmation_modal('<?php echo esc_js($delete_title); ?>', '<?php echo esc_url($redirect_to); ?>', 'delete', '<?php echo esc_js($user_id); ?>', 'student', 'templates/backend/<?php echo esc_attr($current_user_role); ?>/student/list', 'learny-student-list-area');">
                <i class='dashicons dashicons-no-alt'></i>
            </a>
        </div>
<?php
    }


    public function get_data()
    {
        $user_obj = get_users(['role' => BaseController::$custom_roles['student']['role']]);

        $students = array();

        foreach ($user_obj as $key => $user) {
            $students[$key]['id'] = esc_html($user->ID);
            $students[$key]['email'] = esc_html($user->user_email);
            $students[$key]['name'] = esc_html($user->display_name);
            $students[$key]['status'] = get_user_meta($user->ID, 'ly_status', true) ? 1 : 0;
        }

        return $students;
    }

    public function get_numbers($status = "all")
    {
        $user_obj = get_users(
            [
                'role' => BaseController::$custom_roles['student']['role'],
                'meta_key' => 'ly_status',
                'meta_value' => $status == "all" ? "" : $status
            ]
        );

        return count($user_obj);
    }
}
