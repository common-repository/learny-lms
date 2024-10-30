<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$learny_course_difficulty_level = esc_html(get_post_meta($post->ID, 'ly_course_difficulty_level', true));
$ly_course_language_made_in = esc_html(get_post_meta($post->ID, 'ly_course_language_made_in', true));
$learny_is_trendy_course        = esc_html(get_post_meta($post->ID, 'ly_is_trendy_course', true));
?>

<div class="learny-form-field-row learny-form-separator">
    <div class="learny-form-field-label">
        <label for="ly_course_difficulty_level"><?php echo esc_html_e('Course Difficulty Level', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <select name="ly_course_difficulty_level" id="ly_course_difficulty_level" class="learny-select2">
            <option value="<?php echo esc_html('beginner'); ?>" <?php if ($learny_course_difficulty_level == "beginner") echo esc_attr('selected'); ?>><?php echo ucwords(esc_html('beginner')); ?></option>
            <option value="<?php echo esc_html('intermediate'); ?>" <?php if ($learny_course_difficulty_level == "intermediate") echo esc_attr('selected'); ?>><?php echo ucwords(esc_html('intermediate')); ?></option>
            <option value="<?php echo esc_html('advanced'); ?>" <?php if ($learny_course_difficulty_level == "advanced") echo esc_attr('selected'); ?>><?php echo ucwords(esc_html('advanced')); ?></option>
        </select>
    </div>
</div>

<div class="learny-form-field-row learny-form-separator">
    <div class="learny-form-field-label">
        <label for="ly_course_language_made_in"><?php echo esc_html_e('Course Language', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <input type="text" name="ly_course_language_made_in" id="ly_course_language_made_in" value="<?php echo esc_attr($ly_course_language_made_in); ?>">
    </div>
</div>

<div class="learny-form-field-row">
    <div class="learny-form-field-label">
        <label for="ly_is_trendy_course"><?php echo esc_html_e('Is Trendy Course', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <input id="ly_is_trendy_course" name="ly_is_trendy_course" type="checkbox" <?php echo esc_attr($learny_is_trendy_course) ? esc_attr('checked') : ''; ?> value="1">
        <p class="learny-form-field-description">
            <?php esc_html_e("Check the box if it is a trendy course", BaseController::$text_domain); ?>
        </p>
    </div>
</div>