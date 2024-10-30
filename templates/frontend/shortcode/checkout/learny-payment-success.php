<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$course_id = filter_input(INPUT_GET, 'course-id', FILTER_SANITIZE_URL);
$course_details = get_post($course_id);
$student_dashboard_page = get_option('ly_dashboard_page', 0) ? esc_url_raw(get_permalink(get_option('ly_dashboard_page', 0))) : site_url();
?>
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1><?php esc_html_e('Congratulations!!!', BaseController::$text_domain); ?></h1>
            <h4>
                <?php esc_html_e('Your have purchased', BaseController::$text_domain); ?>
                "<?php echo esc_html($course_details->post_title); ?>"
                <?php esc_html_e('successfully', BaseController::$text_domain); ?>
            </h4>
            <button class="btn btn-success" onclick="redirectTo('<?php echo esc_url_raw($student_dashboard_page); ?>')"><?php esc_html_e('Go To My Courses', BaseController::$text_domain); ?></button>
        </div>
    </div>
</div>