<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\modules\Section;
use Learny\base\BaseController;
use Learny\base\AjaxPosts;

$course_id = AjaxPosts::$param1;

$learny_sections = Section::get_sections($course_id);
?>

<ul id="learny-sortable">
    <?php
    $index = 0;
    while ($learny_sections->have_posts()) :
        $index++;
        $learny_sections->the_post();
        $section_title = get_the_title();
    ?>

        <li class="learny-box learny-draggable-item" id="<?php echo esc_attr(get_the_ID()); ?>">
            <div class="learny-panel learny-sortable-panel-style">
                <div class="learny-panel-title">
                    <i class="dashicons dashicons-menu"></i> <?php echo esc_html($section_title); ?>
                </div>
            </div>
        </li>
    <?php endwhile; ?>
</ul>

<div class="learny-row learny-justify-content-center learny-mb-2">
    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form section-sort-form' enctype='multipart/form-data' autocomplete="off">
        <input type="hidden" name="action" value="<?php echo BaseController::$plugin_id . '_section'; ?>">
        <input type="hidden" name="task" value="sort_section">
        <input type="hidden" name="sort_section_nonce" value="<?php echo wp_create_nonce('sort_section_nonce'); ?>"> <!-- kind of csrf token-->
        <input type="hidden" name="section_serial" id="section-serial" value=''>

        <div class="learny-custom-modal-action-footer">
            <div class="learny-custom-modal-actions">
                <button type="button" class="learny-btn learny-btn-secondary learny-btn-md" data-dismiss="modal"><?php esc_html_e("Cancel", BaseController::$text_domain) ?></button>
                <button type="button" class="learny-btn learny-btn-primary learny-btn-md" id="update-sort-btn"><?php esc_html_e("Update Sort", BaseController::$text_domain); ?></button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    'use strict';

    jQuery(document).ready(function() {

        var options = {
            beforeSubmit: validate,
            success: showResponse,
            resetForm: false
        };
        jQuery('.section-sort-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

        jQuery("#update-sort-btn").on('click', updateSort);
    });

    function validate() {
        var section_serial = jQuery('#section-serial').val();

        if (section_serial === '') {

            learnyNotify("<?php esc_html_e('Update section failed', BaseController::$text_domain) ?>", 'warning');
            return false;
        }
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.section-sort-form').trigger('reset');
            closeModal();
            learnyNotify(response.message, 'success');
            learnyMakeAjaxCall('views/custom-meta-box/course/sub-section/sections', 'learny-section-list-area', '<?php echo esc_attr($course_id); ?>');
        } else {
            learnyNotify(response.message, 'warning');
        }
    }

    jQuery(function() {
        jQuery("#learny-sortable").sortable();
        jQuery("#learny-sortable").disableSelection();
    });


    function updateSort() {
        var containerArray = ['learny-sortable'];
        var itemArray = [];
        var itemJSON;
        for (var i = 0; i < containerArray.length; i++) {
            jQuery('#' + containerArray[i]).each(function() {
                jQuery(this).find('.learny-draggable-item').each(function() {
                    itemArray.push(this.id);
                });
            });
        }
        itemJSON = JSON.stringify(itemArray);
        jQuery("#section-serial").val(itemArray);

        // SUBMITTING THE FORM
        jQuery('.section-sort-form').submit();
    }
</script>