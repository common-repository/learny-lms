<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;

$vimeo_video_url = isset($lesson_id) ? esc_html(get_post_meta($lesson_id, 'ly_video_url', true)) : "";
$vimeo_video_duration = isset($lesson_id) ? esc_html(get_post_meta($lesson_id, 'ly_video_duration', true)) : "";
?>

<div class="learny-form-group">
    <label for="ly_video_url"> <?php esc_html_e('Vimeo Video URL', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
    <input type="text" name="ly_video_url" class="learny-form-control" id="ly_video_url" aria-describedby="ly_video_url" placeholder="<?php esc_html_e('Video URL', BaseController::$text_domain); ?>" onblur="validateVideoUrl(this.value)" value="<?php echo esc_attr($vimeo_video_url); ?>">
    <span class="learny-text-warning learny-ml-1 learny-analyzing-video-url learny-d-none"> <i class="dashicons dashicons-update-alt spin"></i> <?php esc_html_e('Analyzing Your Video URL', BaseController::$text_domain); ?> </span>
    <span class="learny-text-danger learny-ml-1 learny-invalid-video-url learny-d-none"> <i class="dashicons dashicons-warning"></i> <?php esc_html_e('Invalid Video URL', BaseController::$text_domain); ?> </span>
</div>

<div class="learny-form-group">
    <label for="ly_video_duration"> <?php esc_html_e('Video Duration', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
    <input type="text" name="ly_video_duration" class="learny-form-control" id="ly_video_duration" aria-describedby="ly_video_duration" placeholder="<?php esc_html_e('Video Duration', BaseController::$text_domain); ?>" value="<?php echo esc_attr($vimeo_video_duration); ?>" readonly>
</div>