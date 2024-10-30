<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\AjaxPosts;
use Learny\base\modules\Section;

$course_id = AjaxPosts::$param1;

$learny_sections = Section::get_sections($course_id);
?>

<div class="learny-form-field-row learny-text-center">
    <button class="button button-primary learny-mr-1" type="button" onclick="present_right_modal( 'views/custom-meta-box/course/modal/add-section', '<?php esc_html_e('Add Section', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>' )">
        <i class="dashicons dashicons-networking"></i> <?php esc_html_e('Add Section', BaseController::$text_domain); ?>
    </button>

    <!-- IF SECTION EXISTS SHOW THESE BUTTONS -->
    <?php if ($learny_sections->found_posts > 0) : ?>
        <button class="button button-primary learny-mr-1" type="button" onclick="present_right_modal( 'views/custom-meta-box/course/modal/lesson-types', '<?php esc_html_e('Select Lesson Type', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>' )">
            <i class="dashicons dashicons-youtube"></i> <?php esc_html_e('Add Lesson', BaseController::$text_domain); ?>
        </button>
        <!-- <button class="button button-primary learny-mr-1" type="button" onclick="present_right_modal( 'views/custom-meta-box/course/modal/quiz', '<?php esc_html_e('Add Quiz', BaseController::$text_domain); ?>' )">
            <i class="dashicons dashicons-editor-spellcheck"></i> <?php esc_html_e('Add Quiz', BaseController::$text_domain); ?>
        </button> -->
        <button class="button button-primary learny-mr-1" type="button" onclick="present_right_modal('views/custom-meta-box/course/modal/add-lesson', '<?php esc_html_e('Add Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', 'note')">
            <i class="dashicons dashicons-clipboard"></i> <?php esc_html_e('Add Note', BaseController::$text_domain); ?>
        </button>
        <button class="button button-primary learny-mr-1" type="button" onclick="present_right_modal( 'views/custom-meta-box/course/modal/sort-section', '<?php esc_html_e('Sort Sections', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>' )">
            <i class="dashicons dashicons-sort"></i> <?php esc_html_e('Sort Section', BaseController::$text_domain); ?>
        </button>
    <?php endif; ?>

    <!-- HELP ICON -->
    <a href="javascript:void(0)" class="learny-help-icon" learny-tooltip="<?php esc_html_e('Help', BaseController::$text_domain); ?>"><i class="dashicons dashicons-editor-help"></i></a>
</div>

<?php
$index = 0;
while ($learny_sections->have_posts()) :
    $index++;
    $learny_sections->the_post();
    $section_title = get_the_title();
?>
    <div class="learny-section-area" learny_section_id="<?php echo esc_attr(get_the_ID()); ?>">
        <div class="learny-section-title">
            <?php esc_html_e('Section', BaseController::$text_domain); ?> <?php echo esc_html($index); ?> : <strong><?php echo esc_html($section_title); ?></strong>
        </div>
        <div class="learny-section-action-btn" id="learny-section-action-btn-<?php echo esc_attr(get_the_ID()); ?>">
            <button class="button" type="button" learny-tooltip="<?php esc_html_e('Sort Lessons', BaseController::$text_domain); ?>" onclick="present_right_modal( 'views/custom-meta-box/course/modal/sort-lesson', '<?php esc_html_e('Sort Lesson', BaseController::$text_domain); ?>', '<?php echo esc_js($course_id); ?>', '<?php echo esc_js(get_the_ID()); ?>' )">
                <i class="dashicons dashicons-sort"></i>
            </button>
            <button class="button" type="button" learny-tooltip="<?php esc_html_e('Edit Section', BaseController::$text_domain); ?>" onclick="present_right_modal( 'views/custom-meta-box/course/modal/edit-section', '<?php esc_html_e('Edit Section', BaseController::$text_domain); ?>',  '<?php echo esc_js($course_id); ?>', '<?php echo esc_js(get_the_ID()); ?>' )">
                <i class="dashicons dashicons-edit"></i>
            </button>
            <button class="button" type="button" learny-tooltip="<?php esc_html_e('Delete Section', BaseController::$text_domain); ?>" onclick="present_confirmation_modal('<?php esc_html_e('Delete this section', BaseController::$text_domain); ?>', null, 'delete', '<?php echo esc_js(get_the_ID()); ?>', 'section', 'views/custom-meta-box/course/sub-section/sections', 'learny-section-list-area', '<?php echo esc_attr($course_id); ?>')">
                <i class="dashicons dashicons-trash"></i>
            </button>
        </div>

        <?php include 'lesson.php'; ?>

    </div>
<?php endwhile; ?>

<?php include Helper::learnyModal(); ?>