<?php

/**
 * @package Learny LMS
 */

namespace Learny\base;

defined('ABSPATH') or die('You can not access the file directly');

class Enqueue extends BaseController
{

    // DEFAULT CONSTRUCTOR
    function __construct()
    {
        parent::__construct();
    }


    // Method for registering admin script enqueue hook to this plugin
    public function register()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
    }


    // Method to enqueue css and js specifically for this plugin so that they does not conflict with other plugins
    function enqueue_admin_assets($hook)
    {
        global $post_type;
        if (
            'learny-lms_page_' . $this->slugs['report']  == $hook ||
            'learny-lms_page_' . $this->slugs['payout']  == $hook ||
            'learny-lms_page_' . $this->slugs['student'] == $hook ||
            'learny-lms_page_' . $this->slugs['instructor'] == $hook ||
            'learny-lms_page_' . $this->slugs['settings'] == $hook ||
            'learny-courses' == $post_type
        ) {
            $this->enqueue_admin_styles();
            $this->enqueue_page_wise_admin_styles();

            $this->enqueue_admin_scripts();
            $this->enqueue_page_wise_admin_scripts();

            $this->pass_localize_data_to_admin_scripts();
        }
    }


    // Method to enqueue css and js specifically for this plugin so that they does not conflict with other plugins
    function enqueue_public_assets($hook)
    {
        global $post_type;
        if (
            'learny-courses' == $post_type
        ) {
            $this->public_assets();
        }
    }

    // THIS FUNCTION HELPS TO ENQUEUE THE PUBLIC ASSETS
    public function public_assets()
    {
        $this->enqueue_public_styles();
        $this->enqueue_page_wise_public_styles();
        $this->enqueue_public_scripts();
        $this->pass_localize_data_to_public_scripts();
    }


    /**
     * METHOD FOR ENQUEUEING ADMIN STYLES
     *
     * @return void
     */
    private function enqueue_admin_styles()
    {
        wp_enqueue_style('learny-grid-style', $this->plugin_url . 'assets/admin/css/learny-grid.css');
        wp_enqueue_style('toastr-style', $this->plugin_url . 'assets/admin/plugins/toastr/css/toastr.css');
        wp_enqueue_style('select2-style', $this->plugin_url . 'assets/admin/plugins/select2/css/select2.min.css');
        wp_enqueue_style('select2-custom-style', $this->plugin_url . 'assets/admin/plugins/select2/css/select2.custom.css');
        wp_enqueue_style('learny-modal-style', $this->plugin_url . 'assets/admin/css/learny-modal.css');
        wp_enqueue_style('learny-side-modal-style', $this->plugin_url . 'assets/admin/css/learny-side-modal.css');
        wp_enqueue_style('learny-form-input-style', $this->plugin_url . 'assets/admin/css/learny-form-input.css');
        wp_enqueue_style('learny-buttons-style', $this->plugin_url . 'assets/admin/css/learny-buttons.css');
        wp_enqueue_style('nice-select-style', $this->plugin_url . 'assets/admin/plugins/nice-select/css/nice-select.css');
        wp_enqueue_style('learny-sortable-style', $this->plugin_url . 'assets/admin/css/learny-sortable.css');
        wp_enqueue_style('learny-tooltip-style', $this->plugin_url . 'assets/common/css/learny-tooltip.css');
        wp_enqueue_style('learny-admin-main-style', $this->plugin_url . 'assets/admin/css/learny-main.css');
    }

    /**
     * ENQUEUE PAGE WISE STYLES
     *
     * @return void
     */
    private function enqueue_page_wise_admin_styles()
    {
        $page_name = isset($_GET['page']) && !empty($_GET['page']) ? sanitize_text_field($_GET['page']) : null;

        if ($page_name && $page_name == "learny-instructor" || $page_name == "learny-student" || $page_name == "learny-report" || $page_name == "learny-payout") {
            wp_enqueue_style('learny-admin-custom-style', $this->plugin_url . 'assets/admin/css/learny-custom.css');
        }
        if ($page_name == "learny-report") {
            wp_enqueue_style('learny-admin-daterangepicker-style', $this->plugin_url . 'assets/admin/css/learny-daterangepicker.css');
        }
    }

    /**
     * ENQUEUE PAGE WISE SCRIPTS
     *
     * @return void
     */
    private function enqueue_page_wise_admin_scripts()
    {
        $page_name = isset($_GET['page']) && !empty($_GET['page']) ? sanitize_text_field($_GET['page']) : null;

        if ($page_name == "learny-report") {
            wp_enqueue_script('learny-daterangepicker-script', $this->plugin_url . 'assets/admin/js/daterangepicker.min.js', array('jquery'), $this->version, true);
        }
    }

    /**
     * METHOD FOR ENQUEUEING ADMIN SCRIPTS
     *
     * @return void
     */
    private function enqueue_admin_scripts()
    {
        wp_enqueue_script('popper-script', $this->plugin_url . 'assets/admin/js/popper.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script('moment-script', $this->plugin_url . 'assets/admin/js/moment.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script('jquery-form', $this->plugin_url . 'assets/admin/js/jquery.form.js', array('jquery'), $this->version, true);
        wp_enqueue_script('select2-script', $this->plugin_url . 'assets/admin/plugins/select2/js/select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script('block-ui-script', $this->plugin_url . 'assets/admin/js/block-ui.js', array('jquery'), $this->version, true);
        wp_enqueue_script('toastr-script', $this->plugin_url . 'assets/admin/plugins/toastr/js/toastr.js', array('jquery'), $this->version, true);
        wp_enqueue_script('nice-select-script', $this->plugin_url . 'assets/admin/plugins/nice-select/js/nice-select.js', array('jquery'), $this->version, true);
        wp_enqueue_script('learny-modal-script', $this->plugin_url . 'assets/admin/js/learny-custom-modal.js', array('jquery'), $this->version, true);
        wp_enqueue_script('learny-modal-handler-script', $this->plugin_url . 'assets/admin/js/learny-modal-handler.js', array('jquery'), $this->version, true);
        wp_enqueue_script('learny-admin-main-script', $this->plugin_url . 'assets/admin/js/learny-main.js', array('jquery'), $this->version, true);
        wp_enqueue_script('learny-admin-init-script', $this->plugin_url . 'assets/admin/js/learny-init.js', array('jquery'), $this->version, true);
        wp_enqueue_media();
        wp_enqueue_editor();
    }


    /**
     * METHOD FOR ENQUEUEING ADMIN'S LOCALIZE SCRIPTS
     *
     * @return void
     */
    private function pass_localize_data_to_admin_scripts()
    {
        global $post;

        wp_localize_script('learny-admin-main-script', 'data', [
            'adminAjaxUrl'          => admin_url('admin-ajax.php'),
            'proBannerTitle'        => esc_html__('Learny LMS Pro is Available Now', BaseController::$text_domain),
            'proBannerDescription'  => esc_html__('Learn More About Learny LMS Pro', BaseController::$text_domain),
            'proBannerDismiss'      => esc_html__('Dismiss', BaseController::$text_domain)
        ]);
        wp_localize_script('learny-modal-handler-script', 'data', ['adminAjaxUrl' => admin_url('admin-ajax.php')]);
        if (isset($post->post_type) && $post->post_type == "learny-courses") {
            $course_id = isset($_GET['post']) && !empty($_GET['post']) ? esc_html($_GET['post']) : 0;
            wp_localize_script('learny-admin-main-script', 'learnyCourseDetails', ['courseId' => esc_js($course_id)]);
        }
    }


    /**
     * METHOD FOR ENQUEUEING PLUBLIC STYLESHEET
     *
     * @return void
     */
    private function enqueue_public_styles()
    {
        wp_enqueue_style('bootstrap-style', $this->plugin_url . 'assets/public/css/bootstrap.min.css');
        wp_enqueue_style('learny-course-list-style', $this->plugin_url . 'assets/public/css/course-list.css');
        wp_enqueue_style('line-awesome-style', $this->plugin_url . 'assets/public/css/line-awesome.min.css');
        wp_enqueue_style('learny-payment-style', $this->plugin_url . 'assets/public/css/payment.css');
        wp_enqueue_style('plyr-style', $this->plugin_url . 'assets/public/plugins/plyr/plyr.css');
        wp_enqueue_style('learny-tooltip-style', $this->plugin_url . 'assets/common/css/learny-tooltip.css');
        wp_enqueue_style('toastr-style', $this->plugin_url . 'assets/admin/plugins/toastr/css/toastr.css');
        wp_enqueue_style('learny-main-style', $this->plugin_url . 'assets/public/css/main.css');
    }


    /**
     * METHOD FOR ENQUEUEING PUBLIC STYLES
     *
     * @return void
     */
    private function enqueue_public_scripts()
    {
        wp_enqueue_script('bootstrap-script', $this->plugin_url . 'assets/public/js/bootstrap.min.js', array(), $this->version, true);
        wp_enqueue_script('jquery-form', $this->plugin_url . 'assets/admin/js/jquery.form.js', array('jquery'), $this->version, true);
        wp_enqueue_script('learny-public-main-script', $this->plugin_url . 'assets/admin/js/learny-main.js', array('jquery'), $this->version, true);
        wp_enqueue_script('block-ui-script-frontend', $this->plugin_url . 'assets/admin/js/block-ui.js', array('jquery'), $this->version, true);
        wp_enqueue_script('plyr-script', $this->plugin_url . 'assets/public/plugins/plyr/plyr.js', array('jquery'), $this->version, true);
        wp_enqueue_script('learny-custom-script-frontend', $this->plugin_url . 'assets/public/js/custom.js', array('jquery'), $this->version, true);
        wp_enqueue_script('toastr-script', $this->plugin_url . 'assets/admin/plugins/toastr/js/toastr.js', array('jquery'), $this->version, true);
        wp_enqueue_media();
    }

    /**
     * ENQUEUE PAGE WISE STYLES
     *
     * @return void
     */
    private function enqueue_page_wise_public_styles()
    {
        wp_enqueue_style('payment-style', $this->plugin_url . 'assets/public/payment-gateways/paypal/css/paypal-checkout.css');
        wp_enqueue_script('paypal-checkout', $this->plugin_url . 'assets/public/payment-gateways/paypal/js/paypal-checkout.js', array('jquery'));
        wp_enqueue_script('learny-payment-script', $this->plugin_url . 'assets/public/payment-gateways/paypal/js/payment.js', array('jquery'));
    }

    /**
     * METHOD FOR ENQUEUEING PUBLIC'S LOCALIZE SCRIPTS
     *
     * @return void
     */
    private function pass_localize_data_to_public_scripts()
    {
        wp_localize_script('learny-public-main-script', 'data', ['adminAjaxUrl' => admin_url('admin-ajax.php')]);
    }
}
