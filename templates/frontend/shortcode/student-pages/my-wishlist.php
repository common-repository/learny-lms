<?php
defined('ABSPATH') or die('You can not access this file directly');

use Learny\base\BaseController;
use Learny\base\modules\Lesson;
use Learny\base\modules\Wishlist;

$wishlists = Wishlist::get_wishlisted_courses();

$wishlisted_course_ids = [];
foreach ($wishlists as $key => $wishlist) {
    if (!in_array($wishlist->wishlist_course_id, $wishlisted_course_ids)) {
        array_push($wishlisted_course_ids, $wishlist->wishlist_course_id);
    }
}

if (count($wishlisted_course_ids) > 0) :

    $the_query = new WP_Query(['post_type' => 'learny-courses', 'post__in' => $wishlisted_course_ids]);
?>

    <div class="row">
        <h5>
            <?php esc_html_e('Total Number Of Course', BaseController::$text_domain); ?> : <span class="learny-total-result-number"><?php echo esc_html($the_query->found_posts); ?></span>
        </h5>
        <?php
        $index = 1;
        if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();
                $course_id = get_the_ID();
                $slug = esc_html(get_post_field('post_name', $course_id));
                $course_rating = esc_html(get_post_meta($course_id, 'ly_course_rating', true));
                $course_rating = $course_rating > 0 ? $course_rating : 0;
        ?>
                <div class="col-12 learny-wishlist-course-row" id="learny-wishlisted-course-<?php echo esc_attr($course_id); ?>">
                    <div class="row learny-internal-course-row">
                        <div class="col-md-2 learny-internal-course-thumbnail">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="learny-main" alt="">
                            </a>
                        </div>
                        <div class="col-md-6 learny-internal-course-details">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>" class="learny-internal-course-title"><?php the_title(); ?></a>
                            <br>
                            <div class="learny-internal-course-instructor-details">
                                <img src="<?php echo esc_url(get_avatar_url(get_the_author_meta('user_email'))); ?>" alt="">
                                <?php echo esc_html(get_the_author_meta('display_name')); ?>
                            </div>
                            <div class="learny-internal-course-rating-container-<?php echo esc_attr($course_id); ?>">
                                <span id="learny-course-review-area-<?php echo esc_attr($course_id); ?>">
                                    <label for=""><?php esc_html_e('Course Rating', BaseController::$text_domain); ?> :</label>
                                    <?php for ($i = 1; $i < 6; $i++) : ?>
                                        <?php if ($i <= $course_rating) : ?>
                                            <i class="las la-star learny-rated"></i>
                                        <?php else : ?>
                                            <i class="las la-star learny-not-rated"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 learny-internal-course-details">
                            <?php
                            $learny_lessons = Lesson::get_number_of_lesson_by_course_id($course_id);
                            ?>
                            <div class="d-block">
                                <?php esc_html_e('Number of lessons', BaseController::$text_domain); ?> : <?php echo esc_html($learny_lessons); ?>
                            </div>
                            <div class="d-block">
                                <?php esc_html_e('Last Updated At'); ?> : <?php the_modified_date("D, d-M-Y"); ?>
                            </div>
                        </div>
                        <div class="col-md-1 learny-internal-course-action">
                            <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form wishlist-edit-form' enctype='multipart/form-data' autocomplete="off">
                                <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_wishlist'; ?>">
                                <input type="hidden" name="task" value="edit_wishlist">
                                <!-- kind of csrf token-->
                                <input type="hidden" name="edit_wishlist_nonce" value="<?php echo wp_create_nonce('edit_wishlist_nonce'); ?>">
                                <input type="hidden" name="wishlist_course_id" value="<?php echo esc_attr($course_id); ?>">
                                <button class="button w-100" id="learny-wishlist-btn">
                                    <i class="las la-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <div class="learny-empty-row-placeholder">
                <?php esc_html_e('Nothing found', BaseController::$text_domain); ?>
            </div>
        <?php endif; ?>

        <?php wp_reset_query(); ?>
    </div>

<?php else : ?>
    <?php esc_html_e('Nothing found', BaseController::$text_domain); ?>
<?php endif; ?>

<script>
    "use strict";

    var options = {
        beforeSubmit: validate,
        success: showResponse,
        resetForm: false
    };

    // ON READY
    document.addEventListener('DOMContentLoaded', function() {

        jQuery('.wishlist-edit-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });

    }, false);


    // LOAD WISHLISTED COURSES
    function learnyLoadWishlistedCourses(courseId) {
        jQuery('#learny-wishlisted-course-' + courseId).remove();
        var totalNumberShowing = jQuery('.learny-total-result-number').text();
        totalNumberShowing = parseInt(totalNumberShowing);
        if (totalNumberShowing > 0) {
            totalNumberShowing--;

            jQuery('.learny-total-result-number').text(totalNumberShowing);
        } else {
            jQuery(".learny-empty-row-placeholder").show();
        }
    }


    // VALIDATING WISHLIST FORM
    function validate() {

        jQuery('#learny-wishlist-btn').prepend(spinner);
        return true;
    }

    // SHOWING RESPONSE OF WISHLIST
    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.wishlist-edit-form').trigger('reset');
            learnyNotify(response.message, 'success');
            learnyLoadWishlistedCourses(response.course_id);
        } else {
            learnyNotify(response.message, 'warning');
        }
    }
</script>