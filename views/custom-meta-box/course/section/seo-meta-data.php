<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$meta_description = esc_html(get_post_meta($post->ID, 'ly_course_meta_description', true));
?>

<div class="learny-form-field-row">
    <div class="learny-form-field-label">
        <label for="ly_course_meta_description"><?php echo esc_html_e('Meta Description', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <textarea name="ly_course_meta_description" id="ly_course_meta_description" rows="3"><?php echo esc_html($meta_description); ?></textarea>
        <p class="learny-form-field-description">
            <?php esc_html_e("Provide course meta description.", BaseController::$text_domain); ?>
        </p>
    </div>
</div>