<?php

defined('ABSPATH') or die("You can not access this file directly");

use Learny\base\modules\Video;
use Learny\base\Helper;
use Learny\base\BaseController;

if ($selected_lesson) :
    $selected_lesson = $selected_lesson
?>
    <?php
    while ($selected_lesson->have_posts()) :
        $selected_lesson->the_post();
        $ly_lesson_type = esc_html(get_post_meta(get_the_ID(), 'ly_lesson_type', true));
    ?>

        <?php if ($ly_lesson_type == "iframe") : ?>
            <div class="learny-course-player-iframe-container">
                <iframe src="<?php echo esc_url(get_post_meta(get_the_ID(), 'ly_iframe_src', true)); ?>" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
            </div>
        <?php elseif ($ly_lesson_type == "youtube") : ?>
            <!------------- PLYR.IO FOR YOUTUBE------------>
            <div class="plyr__video-embed" id="player">
                <iframe height="500" src="<?php echo esc_url(get_post_meta(get_the_ID(), 'ly_video_url', true)); ?>?origin=https://plyr.io&amp;iv_load_policy=3&amp;modestbranding=1&amp;playsinline=1&amp;showinfo=0&amp;rel=0&amp;enablejsapi=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
            </div>
            <!------------- PLYR.IO FOR YOUTUBE------------>
        <?php elseif ($ly_lesson_type == "vimeo") : ?>
            <!------------- PLYR.IO FOR VIMEO------------>
            <?php
            $video_details = Video::get_video_details(esc_url(get_post_meta(get_the_ID(), 'ly_video_url', true)));
            $video_id = $video_details['video_id']; ?>
            <div class="plyr__video-embed" id="player">
                <iframe height="500" src="https://player.vimeo.com/video/<?php echo esc_html($video_id); ?>?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
            </div>
            <!------------- PLYR.IO FOR VIMEO------------>
        <?php elseif ($ly_lesson_type == "video-url") : ?>
            <!------------- PLYR.IO FOR VIDEO URL------------>
            <video poster="" id="player" controls">
                <?php if (Helper::get_extension(esc_url(get_post_meta(get_the_ID(), 'ly_video_url', true))) == 'mp4') : ?>
                    <source src="<?php echo esc_url(esc_url(get_post_meta(get_the_ID(), 'ly_video_url', true))); ?>" type="video/mp4">
                <?php elseif (Helper::get_extension(esc_url(get_post_meta(get_the_ID(), 'ly_video_url', true))) == 'webm') : ?>
                    <source src="<?php echo esc_url(esc_url(get_post_meta(get_the_ID(), 'ly_video_url', true))); ?>" type="video/webm">
                <?php else : ?>
                    <h4><?php esc_html_e('Unsupported File', BaseController::$text_domain); ?></h4>
                <?php endif; ?>
            </video>
            <!------------- PLYR.IO FOR VIDEO URL------------>
        <?php elseif ($ly_lesson_type == "note") : ?>
            <div class="learny-course-player-iframe-container">
                <?php echo the_content(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
<?php endif; ?>