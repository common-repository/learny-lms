<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$note = $lesson_details->post_content;
?>

<div class="learny-form-group">
    <label for="ly_video_url"> <?php esc_html_e('Note', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
    <textarea name="note" id="note" rows="20" placeholder="<?php esc_html_e('Write note here', BaseController::$text_domain); ?>"><?php echo esc_textarea($note); ?></textarea>
</div>

<script>
    "use strict";
    initWpEditor(['note']);
</script>