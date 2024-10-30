<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Video;
?>
<!-- Modal -->
<div class="modal fade learny-course-preview-modal" id="coursePreviewModal" tabindex="-1" aria-labelledby="coursePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="coursePreviewModalLabel"><?php esc_html_e('Course Preview', BaseController::$text_domain); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                $preview_video_provider = esc_html(get_post_meta(get_the_ID(), 'ly_course_preview_provider', true));
                $preview_video_url = esc_html(get_post_meta(get_the_ID(), 'ly_course_preview_url', true));
                ?>
                <?php if (strtolower(esc_html($preview_video_provider)) == 'youtube') : ?>
                    <!------------- PLYR.IO ------------>
                    <div class="plyr__video-embed" id="player">
                        <iframe height="500" src="<?php echo esc_url($preview_video_url); ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                    </div>
                    <!------------- PLYR.IO ------------>

                    <!-- If the video is vimeo video -->
                <?php elseif (strtolower(esc_html($preview_video_provider)) == 'vimeo') :
                    $video_details = Video::get_video_details($preview_video_url);
                    $video_id = $video_details['video_id']; ?>
                    <!------------- PLYR.IO ------------>
                    <div class="plyr__video-embed" id="player">
                        <iframe height="500" src="https://player.vimeo.com/video/<?php echo esc_html($video_id); ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                    </div>
                    <!------------- PLYR.IO ------------>
                <?php else : ?>
                    <!------------- PLYR.IO ------------>
                    <video poster="" id="player" controls">
                        <?php if (Helper::get_extension($preview_video_url) == 'mp4') : ?>
                            <source src="<?php echo esc_url($preview_video_url); ?>" type="video/mp4">
                        <?php elseif (Helper::get_extension($preview_video_url) == 'webm') : ?>
                            <source src="<?php echo esc_url($preview_video_url); ?>" type="video/webm">
                        <?php else : ?>
                            <h4><?php esc_html_e('Unsupported File', BaseController::$text_domain); ?></h4>
                        <?php endif; ?>
                    </video>
                    <!------------- PLYR.IO ------------>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>