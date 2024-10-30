<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\modules\Lesson;

$ly_lesson_types = ['video' => 'dashicons-youtube', 'quiz' => 'dashicons-editor-ol', 'note' => 'dashicons-clipboard', 'iframe' => 'dashicons-embed-post'];
$lesson = Lesson::get_lessons_by_section_id(get_the_ID());
$lesson_index = 0;
while ($lesson->have_posts()) :
    $lesson_index++;
    $lesson->the_post();
    $lesson_title = get_the_title();
    $ly_lesson_type = esc_html(get_post_meta(get_the_ID(), 'ly_lesson_type', true));

    $ly_lesson_type = array_key_exists($ly_lesson_type, $ly_lesson_types) ? $ly_lesson_type : 'video';
?>
    <div class="learny-lesson-area learny-mb-2" learny_lesson_id="<?php echo esc_attr(get_the_ID()); ?>">
        <div class="learny-lesson-title">
            <i class="dashicons <?php echo esc_attr($ly_lesson_types[$ly_lesson_type]); ?>"></i> <?php esc_html_e('Lesson', BaseController::$text_domain); ?> <?php echo esc_html($lesson_index); ?> : <strong><?php echo esc_html($lesson_title); ?></strong>
        </div>
        <div class="learny-lesson-action-btn" id="learny-lesson-action-btn-<?php echo esc_attr(get_the_ID()); ?>">
            <a href="javascript:void(0)" onclick="present_right_modal( 'views/custom-meta-box/course/modal/edit-lesson', '<?php esc_html_e('Edit Lesson', BaseController::$text_domain); ?>',  '<?php echo esc_js($course_id); ?>', '<?php echo esc_js(get_the_ID()); ?>' )" learny-tooltip="<?php esc_html_e('Edit Lesson', BaseController::$text_domain); ?>"><i class="dashicons dashicons-edit"></i></a>
            <a href="javascript:void(0)" onclick="present_confirmation_modal('<?php esc_html_e('Delete this lesson', BaseController::$text_domain); ?>', null, 'delete', '<?php echo esc_js(get_the_ID()); ?>', 'lesson', 'views/custom-meta-box/course/sub-section/sections', 'learny-section-list-area', '<?php echo esc_attr($course_id); ?>')" learny-tooltip="<?php esc_html_e('Delete Lesson', BaseController::$text_domain); ?>"><i class="dashicons dashicons-trash"></i></a>
        </div>
    </div>
<?php endwhile; ?>

<script>

</script>