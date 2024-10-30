<?php

/**
 * @package Learny
 */

namespace Learny\base\shortcodes;

ob_start();

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Enqueue;
use Learny\base\Helper;
use Dompdf\Dompdf;
use Learny\base\modules\Payment;

class StudentDashboard extends BaseController
{
    protected $enqueue;
    protected $baseController;
    /**
     * DEFAULT CONSTRUCTOR
     */
    public function __construct()
    {
        parent::__construct();
        $this->enqueue = new Enqueue();
        $this->baseController = new BaseController();
    }


    /**
     * THIS FUNCTION IS FOR REGISTERING SHORTCODES
     *
     * @return void
     */
    public function register()
    {
        add_shortcode('learny-dashboard', array($this, 'render'));
    }


    /**
     * RENDER SHORTCODE VIEW
     *
     * @return void
     */
    public function render()
    {
        // CHECK IF THE USER IS LOGGED IN
        Auth::authenticate_login();

        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);
        if ($action == "download-invoice") {
            $purchase_history_id = filter_input(INPUT_GET, 'purchase-history-serial', FILTER_SANITIZE_URL);
            $purchase_history_id = (int) $purchase_history_id ? (int) $purchase_history_id : 0;

            $purchase_history_details = Payment::get_purchase_history_by_id($purchase_history_id);

            if (Helper::get_current_user_role() == "administrator" || $purchase_history_details->payment_user_id == get_current_user_id()) {

                $filePath = $this->baseController->plugin_path . 'templates/frontend/shortcode/student-pages/invoice.php';

                ob_start();
                require_once($filePath);
                $html = ob_get_clean();

                $filename = "my-invoice.pdf";

                // instantiate and use the dompdf class
                $dompdf = new Dompdf();

                $dompdf->loadHtml($html);

                // (Optional) Setup the paper size and orientation
                $dompdf->setPaper('A4', 'potrait');

                // Render the HTML as PDF
                $dompdf->render();

                ob_end_clean();
                // Output the generated PDF to Browser
                $dompdf->stream($filename, array("Attachment" => 0));
            } else {
                $template_path = Helper::view_path('student-pages/403');
                $this->enqueue->public_assets();

                ob_start();
                require_once($template_path);
                return ob_get_clean();
            }
        } else {
            $template_path = Helper::view_path('student-pages/index');
            $this->enqueue->public_assets();

            ob_start();
            require_once($template_path);
            return ob_get_clean();
        }
    }
}
