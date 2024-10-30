<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

$learny_course_category_thumbnail = isset($term->term_id) ? esc_url_raw(get_term_meta($term->term_id, 'learny_course_category_image_thumbnail_url', true)) : esc_url_raw(Helper::get_thumbnail('category'));
?>
<tr class="form-field form-required term-name-wrap">
    <th scope="row">
        <label for="tag-name">
            <?php esc_html_e('Category Thumbnail', BaseController::$text_domain); ?>
        </label>
    </th>
    <td>
        <img src="<?php echo esc_url($learny_course_category_thumbnail); ?>" alt="" class="learny-thumbnail-image-previewer" id="learny-course-category-image-thumbnail-previewer">
        <input type="hidden" name="learny_course_category_image_thumbnail_url" id="learny-course-category-image-thumbnail-url" value="<?php echo esc_url($learny_course_category_thumbnail); ?>">
        <p>
            <button type="button" id="learny-category-image-uploader" class="button" onclick="learnyMediaUploader('image', false, '<?php echo esc_html_e('Choose Category Thumbnail', BaseController::$text_domain); ?>', learnyHandleCategoryThumbnail);"><?php esc_html_e('Upload Image', BaseController::$text_domain); ?></button><br>
            <?php esc_html_e('Help text for more info', BaseController::$text_domain); ?>
        </p>
    </td>
</tr>