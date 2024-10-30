<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$ly_iframe_src = isset($lesson_id) ? esc_html(get_post_meta($lesson_id, 'ly_iframe_src', true)) : "";
?>

<div class="learny-form-group">
    <label for="ly_iframe_src"> <?php esc_html_e('Iframe Source', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
    <input type="text" name="ly_iframe_src" class="learny-form-control" id="ly_iframe_src" aria-describedby="ly_iframe_src" placeholder="<?php esc_html_e('Iframe Source', BaseController::$text_domain); ?>" value="<?php echo esc_url_raw($ly_iframe_src); ?>">
</div>