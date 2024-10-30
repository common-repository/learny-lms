<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$outcomes = esc_html(get_post_meta($post->ID, 'ly_course_outcomes', true));
$requirements = esc_html(get_post_meta($post->ID, 'ly_course_requirements', true));
?>

<div class="learny-form-field-row learny-form-separator">
    <div class="learny-form-field-label">
        <label for="ly_course_requirements"><?php echo esc_html_e('Course Requirements', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <textarea name="ly_course_requirements" id="ly_course_requirements" rows="3"><?php echo esc_html($requirements); ?></textarea>
        <p class="learny-form-field-description">
            <?php esc_html_e("Use a comma (,) to separate several course requirements.", BaseController::$text_domain); ?>
        </p>
    </div>
</div>

<div class="learny-form-field-row">
    <div class="learny-form-field-label">
        <label for="ly_course_outcomes"><?php echo esc_html_e('Course Outcomes', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <textarea name="ly_course_outcomes" id="ly_course_outcomes" rows="3"><?php echo esc_html($outcomes); ?></textarea>
        <p class="learny-form-field-description">
            <?php esc_html_e("Use a comma (,) to separate several course outcomes.", BaseController::$text_domain); ?>
        </p>
    </div>
</div>