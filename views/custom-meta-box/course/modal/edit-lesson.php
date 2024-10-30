<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\AjaxPosts;
use Learny\base\modules\Section;

$course_id = AjaxPosts::$param1;
$available_sections = Section::get_sections($course_id);
$lesson_id = AjaxPosts::$param2;
$lesson_details = get_post($lesson_id);

$ly_section_id = esc_html(get_post_meta($lesson_id, 'ly_section_id', true));
$ly_lesson_type = esc_html(get_post_meta($lesson_id, 'ly_lesson_type', true));
$ly_summary = esc_html(get_post_meta($lesson_id, 'ly_summary', true));
$attachment_urls = explode(',', esc_html(get_post_meta($lesson_id, 'ly_attachment', true)));

if (count($attachment_urls) == 1 && empty($attachment_urls[0])) {
    $attachment_urls = array();
}

?>
<form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form lesson-edit-form' enctype='multipart/form-data' autocomplete="off">
    <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_lesson'; ?>">
    <input type="hidden" name="task" value="edit_lesson">
    <input type="hidden" name="edit_lesson_nonce" value="<?php echo wp_create_nonce('edit_lesson_nonce'); ?>"> <!-- kind of csrf token-->
    <input type="hidden" name="learny_course_id" value="<?php echo esc_attr($course_id); ?>">
    <input type="hidden" name="lesson_id" value="<?php echo esc_attr($lesson_id); ?>">

    <div class="learny-selected-lesson-type learny-alert learny-alert-primary">
        <?php esc_html_e('Lesson Type', BaseController::$text_domain); ?> : <span><?php echo ucfirst(esc_html($ly_lesson_type)); ?></span>
        <!-- HELP BUTTON -->
        <a href="javascript:void(0)" learny-tooltip="<?php esc_html_e('Help', BaseController::$text_domain); ?>"><i class="dashicons dashicons-editor-help"></i></a>
    </div>

    <div class="learny-form-group">
        <label for="learny_lesson_title"> <?php esc_html_e('Lesson Title', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <input type="text" name="learny_lesson_title" class="learny-form-control" id="learny_lesson_title" aria-describedby="learny_lesson_title" value="<?php echo esc_attr($lesson_details->post_title); ?>" placeholder="<?php esc_html_e('Lesson Title', BaseController::$text_domain); ?>">
    </div>

    <div class="learny-form-group">
        <label for="learny_section_id"> <?php esc_html_e('Section', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <select name="learny_section_id" class="learny-wide" id="learny_section_id">
            <option value='' class="learny-disabled"> <?php esc_html_e("Select a section", BaseController::$text_domain) ?></option>
            <?php
            while ($available_sections->have_posts()) :
                $available_sections->the_post();
            ?>
                <option value="<?php echo esc_html(get_the_ID()); ?>" <?php if ($ly_section_id == get_the_ID()) echo 'selected'; ?>><?php echo esc_html(get_the_title()); ?></option>
            <?php endwhile; ?>
        </select>
    </div>

    <div id="learny-lesson-type-form-fields">
        <?php include $ly_lesson_type . '.php'; ?>
    </div>

    <div class="learny-form-group">
        <label for="lesson_summary"> <?php esc_html_e('Lesson Summary', BaseController::$text_domain); ?> <span class="learny-text-danger">*</span></label>
        <textarea name="lesson_summary" id="lesson_summary" class="learny-form-control"><?php echo esc_html($ly_summary); ?></textarea>
    </div>


    <div class="learny-form-group">
        <label for="ly_lesson_attachment"> <?php esc_html_e('Lesson Attachment', BaseController::$text_domain); ?></label>
        <div class="learny-row">
            <div class="learny-col-md-10">
                <input type="hidden" name="ly_lesson_attachment" class="learny-form-control" id="ly_lesson_attachment" aria-describedby="ly_lesson_attachment" placeholder="<?php esc_html_e('Lesson Attachment', BaseController::$text_domain); ?>" value="<?php echo esc_attr(implode(',', $attachment_urls)); ?>" readonly>

                <!-- THIS CONTAINER WILL SHOW THE LIST OF THE ATTACHMENTS -->
                <div class="learny-attachment-list">
                    <?php if (count($attachment_urls) == 0) : ?>
                        <span class="learny-text-muted"><?php esc_html_e('No Attachments Found', BaseController::$text_domain); ?></span>
                    <?php else : ?>
                        <ul>
                            <?php foreach ($attachment_urls as $key => $attachment_url) : ?>
                                <?php if (!empty($attachment_url)) : ?>
                                    <li>
                                        <?php echo basename(esc_url_raw($attachment_url)); ?>
                                        <a href="javascript:void(0)" class="learny-attachment-remove-btn" id="<?php echo esc_url_raw($attachment_url); ?>" onclick="learnyRemoveAttachment(this)"><i class="dashicons dashicons-dismiss"></i></a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <button type="button" class="button" onclick="learnyMediaUploader('*', true, '<?php echo esc_html_e('Choose Attachment', BaseController::$text_domain); ?>', learnyHandleLessonAttachments);"><i class="dashicons dashicons-paperclip"></i> <?php esc_html_e('Upload Attachment', BaseController::$text_domain); ?> </button>



    <!-- FORM ACTION -->
    <div class="learny-custom-modal-action-footer">
        <div class="learny-custom-modal-actions">
            <button type="button" class="learny-btn learny-btn-secondary learny-btn-md" data-dismiss="modal"><?php esc_html_e('Cancel', BaseController::$text_domain); ?></button>
            <button type="submit" class="learny-btn learny-btn-primary learny-btn-md"><?php esc_html_e('Submit', BaseController::$text_domain); ?></button>
        </div>
    </div>
</form>


<script>
    "use strict";

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.lesson-edit-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        initNiceSelect();
    });

    function validate() {
        var learny_lesson_title = jQuery('#learny_lesson_title').val();
        var learny_section_id = jQuery('#learny_section_id').val();

        if (learny_lesson_title === '' || learny_section_id === '') {
            learnyNotify("<?php esc_html_e('Fill all the required fields', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.lesson-edit-form').trigger('reset');
            closeModal();
            learnyNotify(response.message, 'success');
            learnyMakeAjaxCall('views/custom-meta-box/course/sub-section/sections', 'learny-section-list-area', '<?php echo esc_attr($course_id); ?>');
        } else {
            learnyNotify(response.message, 'warning');
        }
    }

    // TOGGLING LESSON TYPE WISE LESSON FIELD
    function toggleLessonFields(lessonType) {
        learnyMakeAjaxCall('views/custom-meta-box/course/modal/' + lessonType, 'learny-lesson-type-form-fields', '<?php echo esc_attr($course_id); ?>');
    }


    function learnyLoadAttachmentList() {

        jQuery(".learny-attachment-list").empty();

        if (jQuery('#ly_lesson_attachment').val()) {
            var attachmentUrls = jQuery('#ly_lesson_attachment').val().split(",");
            var sub_ul = jQuery('<ul/>');
            attachmentUrls.forEach(function(attachmentUrl) {
                var sub_li = jQuery('<li/>').html(learnyFilename(attachmentUrl) + '<a href="javascript:void(0)" class="learny-attachment-remove-btn" id = "' + attachmentUrl + '" onclick="learnyRemoveAttachment(this)"><i class="dashicons dashicons-dismiss"></i></a>');
                sub_ul.append(sub_li);
            });

            jQuery(".learny-attachment-list").append(sub_ul);
        }
    }

    function learnyHandleLessonAttachments(attachment) {
        jQuery("#ly_lesson_attachment").val(attachment.url);
        learnyLoadAttachmentList();
    }

    function learnyRemoveAttachment(elem) {
        var attachmentUrlList = jQuery('#ly_lesson_attachment').val().split(",");
        var attachmentUrls;
        var removingUrl = elem.id;

        attachmentUrlList.forEach(function(attachmentUrl) {
            if (removingUrl != attachmentUrl) {
                attachmentUrls = attachmentUrls ? attachmentUrls + ',' + attachmentUrl : attachmentUrl;
            }
        });

        jQuery("#ly_lesson_attachment").val(attachmentUrls);
        learnyLoadAttachmentList();
    }
</script>