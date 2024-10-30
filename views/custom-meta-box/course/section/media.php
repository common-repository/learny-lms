<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$ly_course_preview_provider = esc_html(get_post_meta($post->ID, 'ly_course_preview_provider', true));
$ly_course_preview_url = esc_html(get_post_meta($post->ID, 'ly_course_preview_url', true));
?>

<div class="learny-form-field-row learny-form-separator">
    <div class="learny-form-field-label">
        <label for="ly_course_preview_provider"><?php echo esc_html_e('Course Preview Provider', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <select name="ly_course_preview_provider" id="ly_course_preview_provider" class="learny-select2">
            <option value="<?php echo esc_html('youtube'); ?>" <?php if ($ly_course_preview_provider == "youtube") echo esc_attr('selected'); ?>>
                <?php esc_html_e('YouTube', BaseController::$text_domain); ?>
            </option>
            <option value="<?php echo esc_html('vimeo'); ?>" <?php if ($ly_course_preview_provider == "vimeo") echo esc_attr('selected'); ?>>
                <?php esc_html_e('Vimeo', BaseController::$text_domain); ?>
            </option>
            <option value="<?php echo esc_html('html5'); ?>" <?php if ($ly_course_preview_provider == "html5") echo esc_attr('selected'); ?>>
                <?php esc_html_e('HTML5', BaseController::$text_domain); ?>
            </option>
        </select>
    </div>
</div>

<div class="learny-form-field-row">
    <div class="learny-form-field-label">
        <label for="ly_course_preview_url"><?php esc_html_e('Course Preview URL', BaseController::$text_domain); ?>
    </div>
    <div class="learny-form-field">
        <input type="text" name="ly_course_preview_url" id="ly_course_preview_url" value="<?php echo esc_attr($ly_course_preview_url); ?>">
    </div>
</div>