<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\AjaxPosts;

$course_id = AjaxPosts::$param1;
$ly_lesson_type = isset(AjaxPosts::$param2) ? AjaxPosts::$param2 : null;
?>

<div class="learny-row learny-ml-0 learny-mr-0">
    <div class="learny-col-4 learny-lesson-type-selection <?php if ($ly_lesson_type == "youtube") echo 'learny-active-lesson-type'; ?>" ly_lesson_type="youtube">
        <div class="learny-lesson-type-selection-img">
            <img src="<?php echo esc_url_raw($this->plugin_url . 'assets/admin/images/youtube.svg'); ?>" alt="">
        </div>
        <h4><?php esc_html_e('YouTube Video', BaseController::$text_domain); ?></h4>
    </div>
    <div class="learny-col-4 learny-lesson-type-selection <?php if ($ly_lesson_type == "vimeo") echo 'learny-active-lesson-type'; ?>" ly_lesson_type="vimeo">
        <div class="learny-lesson-type-selection-img">
            <img src="<?php echo esc_url_raw($this->plugin_url . 'assets/admin/images/vimeo.svg'); ?>" alt="">
        </div>
        <h4><?php esc_html_e('Vimeo Video', BaseController::$text_domain); ?></h4>
    </div>
    <div class="learny-col-4 learny-lesson-type-selection <?php if ($ly_lesson_type == "video-file") echo 'learny-active-lesson-type'; ?>" ly_lesson_type="video-file">
        <div class="learny-lesson-type-selection-img">
            <img src="<?php echo esc_url_raw($this->plugin_url . 'assets/admin/images/video.svg'); ?>" alt="">
        </div>
        <h4><?php esc_html_e('Video File', BaseController::$text_domain); ?></h4>
    </div>
    <div class="learny-col-4 learny-lesson-type-selection <?php if ($ly_lesson_type == "video-url") echo 'learny-active-lesson-type'; ?>" ly_lesson_type="video-url">
        <div class="learny-lesson-type-selection-img">
            <img src="<?php echo esc_url_raw($this->plugin_url . 'assets/admin/images/mp4.svg'); ?>" alt="">
        </div>
        <h4><?php esc_html_e('.MP4 Video', BaseController::$text_domain); ?></h4>
    </div>
    <div class="learny-col-4 learny-lesson-type-selection <?php if ($ly_lesson_type == "iframe") echo 'learny-active-lesson-type'; ?>" ly_lesson_type="iframe">
        <div class="learny-lesson-type-selection-img">
            <img src="<?php echo esc_url_raw($this->plugin_url . 'assets/admin/images/iframe.svg'); ?>" alt="">
        </div>
        <h4><?php esc_html_e('Iframe Embed', BaseController::$text_domain); ?></h4>
    </div>
</div>


<script>
    "use strict";

    jQuery('.learny-lesson-type-selection').on('click', function() {
        let ly_lesson_type = jQuery(this).attr('ly_lesson_type');
        present_right_modal('views/custom-meta-box/course/modal/add-lesson', '<?php esc_html_e('Add Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', ly_lesson_type);
    });
</script>