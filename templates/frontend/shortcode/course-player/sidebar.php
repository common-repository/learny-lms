<?php defined('ABSPATH') or die('You can not access the file directly'); ?>

<?php

use Learny\base\modules\Section;
use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Lesson;

$learny_sections = Section::get_sections($course_details->ID);

$selected_lesson_details = $selected_lesson->posts;
$selected_lesson_details = $selected_lesson_details[0];
$active_section_id = esc_html(get_post_meta($selected_lesson_details->ID, 'ly_section_id', true));
?>

<div class="accordion w-100" id="learny-course-player-sidebar-accordion">

    <?php

    $index = 0;
    while ($learny_sections->have_posts()) :
        $learny_sections->the_post();
        $section_title = get_the_title();
        $index++;
    ?>
        <div class="accordion-item" id="learny-section-<?php echo get_the_ID(); ?>">
            <h2 class="accordion-header" id="section-header-<?php echo esc_attr(get_the_ID()); ?>">
                <button class="accordion-button <?php if ($active_section_id != get_the_ID()) echo esc_attr('collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-section-id-section-header-<?php echo esc_attr(get_the_ID()); ?>" aria-expanded="true" aria-controls="collapse-section-id-section-header-<?php echo esc_attr(get_the_ID()); ?>">
                    <?php echo esc_html($index); ?>. <?php the_title(); ?>
                </button>
            </h2>
            <div id="collapse-section-id-section-header-<?php echo esc_attr(get_the_ID()); ?>" class="accordion-collapse collapse <?php if ($active_section_id == get_the_ID()) echo esc_attr('show'); ?>" aria-labelledby="section-header-<?php echo esc_attr(get_the_ID()); ?>" data-bs-parent="#learny-course-player-sidebar-accordion">
                <div class="accordion-body">
                    <ul>
                        <?php
                        $learny_lessons = Lesson::get_lessons_by_section_id(get_the_ID());
                        while ($learny_lessons->have_posts()) :
                            $learny_lessons->the_post();
                            $lesson_title = get_the_title();
                            $lesson_attachments = esc_html(get_post_meta(get_the_ID(), 'ly_attachment', true));
                            $lesson_attachments = !empty($lesson_attachments) ? explode(',', $lesson_attachments) : array();
                            $current_lesson_slug = esc_html(get_post_field('post_name', get_the_ID()));
                        ?>
                            <li class="learny-course-player-lesson-area w-100">
                                <span class="learny-course-player-checkbox" learny-tooltip="<?php esc_html_e('Mark As Complete', BaseController::$text_domain); ?>">
                                    <input type="checkbox" name="" class="learny-lesson-completion-checkbox" value="<?php echo esc_attr($course_details->ID) ?>-<?php echo esc_attr(get_the_ID()); ?>" <?php if (in_array(get_the_ID(), $watch_history)) echo "checked"; ?>>
                                </span>

                                <a href="<?php echo esc_url_raw(add_query_arg('lesson', $current_lesson_slug)); ?>" class="learny-course-player-lesson-title">
                                    <?php echo esc_html($lesson_title); ?>
                                </a>

                                <?php if ($current_lesson_slug == $lesson_slug) : ?>
                                    <span class="learny-course-player-currently-playing" learny-tooltip="<?php esc_html_e('Playing', BaseController::$text_domain); ?>">
                                        <i class="las la-play"></i>
                                    </span>
                                <?php endif; ?>


                                <?php if (count($lesson_attachments)) : ?>
                                    <a href="" class="learny-course-player-attachment-handler" learny-tooltip="<?php esc_html_e('Attachment', BaseController::$text_domain); ?>" id="dropdownMenuButton-<?php echo get_the_ID(); ?>" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="las la-download"></i>
                                    </a>
                                    <ul class="dropdown-menu learny-course-player-attachment-dropdown" aria-labelledby="dropdownMenuButton-<?php echo get_the_ID(); ?>">
                                        <?php if ($lesson_attachments && count($lesson_attachments)) : ?>
                                            <?php foreach ($lesson_attachments as $lesson_attachment) : ?>
                                                <?php if (!empty($lesson_attachment)) : ?>
                                                    <li>
                                                        <a class="dropdown-item" href="<?php echo esc_url_raw($lesson_attachment); ?>">
                                                            <small><i class="las la-download"></i> <?php echo basename($lesson_attachment); ?></small>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>

                                <!-- COURSE TYPE WITH DURATION -->
                                <div class="learny-course-player-lesson-info">
                                    <i class="lar la-play-circle"></i> <?php echo Helper::readable_time_for_humans(esc_html(get_post_meta(get_the_ID(), 'ly_video_duration', true))); ?>
                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>


<script>
    "use script";
    document.addEventListener('DOMContentLoaded', function() {
        let sectionId = '<?php echo 'learny-section-' . esc_js($active_section_id); ?>';
        scrollToSection(sectionId);
    }, false);


    function scrollToSection(sectionId) {
        var contactTopPosition = jQuery("#" + sectionId).position().top;
        jQuery("#learny-course-player-sidebar-accordion").scrollTop(contactTopPosition);
    }
</script>