<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$ly_video_url = isset($lesson_id) ? esc_html(get_post_meta($lesson_id, 'ly_video_url', true)) : "";
$ly_video_duration = isset($lesson_id) ? esc_html(get_post_meta($lesson_id, 'ly_video_duration', true)) : "00:00:00";
?>

<div class="learny-form-group">
    <label for="ly_video_url"> <?php esc_html_e('Video File', BaseController::$text_domain); ?></label>
    <div class="learny-row">
        <div class="learny-col-md-10">
            <input type="text" name="ly_video_url" class="learny-form-control" id="ly_video_url" aria-describedby="ly_video_url" placeholder="<?php esc_html_e('Video File', BaseController::$text_domain); ?>" value="<?php echo esc_url_raw($ly_video_url); ?>" readonly>
        </div>
        <div class="learny-col-md-1">
            <button type="button" class="learny-btn learny-btn-info learny-btn-block learny-attachment-btn" onclick="learnyMediaUploader('*', false, '<?php echo esc_html_e('Choose Video File', BaseController::$text_domain); ?>', learnyHandleVideoFile);"><i class="dashicons dashicons-video-alt3"></i></button>
        </div>
    </div>
</div>

<div class="learny-form-group">
    <label for="ly_video_duration"> <?php esc_html_e('Video Duration', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
    <input id="ly_video_duration" type="text" value="<?php echo esc_attr($ly_video_duration); ?>" name="ly_video_duration" class="learny-form-control" />
    <small class="learny-text-muted"><?php esc_html_e('The format will be hh:mm:ss', BaseController::$text_domain); ?></small>
</div>

<script>
    "use strict";

    function learnyHandleVideoFile(videoObj) {
        jQuery("#ly_video_url").val(videoObj.url);
    }
</script>