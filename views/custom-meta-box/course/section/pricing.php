<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$ly_course_price    = esc_html(get_post_meta($post->ID, 'ly_course_price', true));
$ly_is_free_course  = esc_html(get_post_meta($post->ID, 'ly_is_free_course', true));
?>

<div class="learny-form-field-row learny-form-separator">
    <div class="learny-form-field-label">
        <label for="ly_is_free_course"><?php echo esc_html_e('Is Free Course', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <input id="ly_is_free_course" name="ly_is_free_course" type="checkbox" <?php echo esc_attr($ly_is_free_course) ? esc_attr('checked') : ''; ?> value="1">
        <p class="learny-form-field-description">
            <?php esc_html_e("Check the box if it is a Free course", BaseController::$text_domain); ?>
        </p>
    </div>
</div>

<div class="learny-form-field-row">
    <div class="learny-form-field-label">
        <label for="ly_course_price"><?php echo esc_html_e('Course Price', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <input type="text" name="ly_course_price" id="ly_course_price" value="<?php echo esc_attr($ly_course_price); ?>">
    </div>
</div>