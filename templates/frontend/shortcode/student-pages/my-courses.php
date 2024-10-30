<?php

defined('ABSPATH') or die('You can not access this file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Enrolment;
use Learny\base\modules\Lesson;
use Learny\base\modules\Review;


$enrolments = Enrolment::get_enrolled_courses();

$enrolled_course_ids = [];
foreach ($enrolments as $key => $enrolment) {
    if (!in_array($enrolment->enrolment_course_id, $enrolled_course_ids)) {
        array_push($enrolled_course_ids, $enrolment->enrolment_course_id);
    }
}

$course_playing_page = esc_html(get_option('ly_course_player_page'));
$course_playing_page_url = esc_url_raw(get_the_permalink($course_playing_page));

if (count($enrolled_course_ids) > 0) :

    $the_query = new WP_Query(['post_type' => 'learny-courses', 'post__in' => $enrolled_course_ids]);
?>

    <div class="row">
        <h5>
            <?php esc_html_e('Total Number Of Course', BaseController::$text_domain); ?> : <?php echo esc_html($the_query->found_posts); ?>
        </h5>
        <?php
        $index = 1;
        if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();
                $course_id = get_the_ID();
                $slug = esc_html(get_post_field('post_name', $course_id));
                $own_review = Review::get_user_wise_course_review(get_current_user_id(), $course_id);
                $own_rating = !empty($own_review->review_rating) ? $own_review->review_rating : 0;
                $review_details = !empty($own_review->review_details) ? $own_review->review_details : "";
        ?>
                <div class="col-12">
                    <div class="row learny-internal-course-row">
                        <div class="col-md-2 learny-internal-course-thumbnail">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="learny-main" alt="">
                            </a>
                        </div>
                        <div class="col-md-5 learny-internal-course-details">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>" class="learny-internal-course-title"><?php the_title(); ?></a>
                            <br>
                            <div class="learny-internal-course-instructor-details">
                                <img src="<?php echo esc_url(get_avatar_url(get_the_author_meta('user_email'))); ?>" alt="">
                                <?php echo esc_html(get_the_author_meta('display_name')); ?>
                            </div>
                            <div class="learny-internal-course-rating-container-<?php echo esc_attr($course_id); ?>">
                                <span id="learny-course-review-area-<?php echo esc_attr($course_id); ?>">
                                    <label for=""><?php esc_html_e('Your Rating', BaseController::$text_domain); ?> :</label>
                                    <?php for ($i = 1; $i < 6; $i++) : ?>
                                        <?php if ($i <= $own_rating) : ?>
                                            <i class="las la-star learny-rated"></i>
                                        <?php else : ?>
                                            <i class="las la-star learny-not-rated"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                </span>
                                <a href="javascript:void(0)" learny-tooltip="<?php esc_html_e('Edit Rating', BaseController::$text_domain); ?>" onclick="toggleReviewSection('<?php echo esc_js($course_id); ?>')">
                                    <i class="las la-edit"></i>
                                </a>
                            </div>
                            <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form review-update-form' enctype='multipart/form-data' autocomplete="off">
                                <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_review'; ?>">
                                <input type="hidden" name="task" value="edit_review">
                                <input type="hidden" name="edit_review_nonce" value="<?php echo wp_create_nonce('edit_review_nonce'); ?>">
                                <!-- kind of 
                                csrf token-->
                                <input type="hidden" name="review_course_id" value="<?php echo esc_attr($course_id); ?>">

                                <div class="learny-internal-course-rating-update-area learny-d-none" id="learny-course-review-updating-area-<?php echo esc_attr($course_id); ?>">
                                    <label for="" class="new-rating-label"><?php esc_html_e('New Rating', BaseController::$text_domain); ?> :</label>
                                    <div class="rate">
                                        <input type="radio" name="rating_for_course_<?php echo esc_attr($course_id); ?>" id="star-5-course-id-<?php echo esc_attr($course_id); ?>" value="5" <?php if ($own_rating == 5) echo 'checked'; ?> />
                                        <label for="star-5-course-id-<?php echo esc_attr($course_id); ?>" title="text">5 stars</label>

                                        <input type="radio" name="rating_for_course_<?php echo esc_attr($course_id); ?>" id="star-4-course-id-<?php echo esc_attr($course_id); ?>" value="4" <?php if ($own_rating == 4) echo 'checked'; ?> />
                                        <label for="star-4-course-id-<?php echo esc_attr($course_id); ?>" title="text">4 stars</label>

                                        <input type="radio" name="rating_for_course_<?php echo esc_attr($course_id); ?>" id="star-3-course-id-<?php echo esc_attr($course_id); ?>" value="3" <?php if ($own_rating == 3) echo 'checked'; ?> />
                                        <label for="star-3-course-id-<?php echo esc_attr($course_id); ?>" title="text">3 stars</label>

                                        <input type="radio" name="rating_for_course_<?php echo esc_attr($course_id); ?>" id="star-2-course-id-<?php echo esc_attr($course_id); ?>" value="2" <?php if ($own_rating == 2) echo 'checked'; ?> />
                                        <label for="star-2-course-id-<?php echo esc_attr($course_id); ?>" title="text">2 stars</label>

                                        <input type="radio" name="rating_for_course_<?php echo esc_attr($course_id); ?>" id="star-1-course-id-<?php echo esc_attr($course_id); ?>" value="1" <?php if ($own_rating == 1) echo 'checked'; ?> />
                                        <label for="star-1-course-id-<?php echo esc_attr($course_id); ?>" title="text">1 stars</label>

                                    </div>
                                    <div class="review">
                                        <textarea name="review_for_course_<?php echo esc_attr($course_id); ?>" id="review-for-course-<?php echo esc_attr($course_id); ?>" class="" rows="10"><?php echo esc_html($review_details); ?></textarea>
                                    </div>
                                    <button class="btn btn-block btn-success btn-sm" type="submit"><?php esc_html_e('Submit', BaseController::$text_domain); ?></button>
                                    <button class="btn btn-block btn-secondary btn-sm" type="button" onclick="toggleReviewSection('<?php echo esc_js($course_id); ?>')"><?php esc_html_e('Cancel', BaseController::$text_domain); ?></button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3 learny-internal-course-details">
                            <?php
                            $watch_history = Enrolment::get_watch_history($course_id);
                            $learny_lessons = Lesson::get_number_of_lesson_by_course_id($course_id);
                            $course_progress = !empty($watch_history) && count($watch_history) > 0 ? (count($watch_history) / $learny_lessons) * 100 : 0;
                            $course_progress = round($course_progress);
                            ?>
                            <div class="d-block">
                                <?php esc_html_e('Number of lessons', BaseController::$text_domain); ?> : <?php echo esc_html($learny_lessons); ?>
                            </div>
                            <div class="d-block">
                                <?php esc_html_e('Last Updated At'); ?> : <?php the_modified_date("D, d-M-Y"); ?>
                            </div>
                            <div class="d-block mt-3">
                                <div class="progress learny-course-progress-bar">
                                    <div class="progress-bar" role="progressbar" style="width: <?php echo esc_attr($course_progress); ?>%;" aria-valuenow="<?php echo esc_attr($course_progress); ?>" aria-valuemin="0" aria-valuemax="100"><?php echo esc_html($course_progress); ?>%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 learny-internal-course-action text-center">
                            <button class="btn btn-block btn-success mt-5 btn-sm" type="button" onclick="redirectTo('<?php echo esc_url_raw($course_playing_page_url . '?course=' .  $slug); ?>')"><?php esc_html_e('Start Learning', BaseController::$text_domain); ?></button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else : ?>
            <?php esc_html_e('nothing found', BaseController::$text_domain); ?>
        <?php endif; ?>

        <?php wp_reset_query(); ?>
    </div>

<?php else : ?>
    <?php esc_html_e('nothing found', BaseController::$text_domain); ?>
<?php endif; ?>

<script>
    "use strict";

    var options = {
        beforeSubmit: validate,
        success: showResponse,
        resetForm: false
    };

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        jQuery('.review-update-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    }, false);

    function toggleReviewSection(courseId) {
        if (jQuery('#learny-course-review-area-' + courseId).hasClass('learny-d-none')) {
            jQuery('#learny-course-review-area-' + courseId).removeClass('learny-d-none');
            jQuery('#learny-course-review-updating-area-' + courseId).addClass('learny-d-none');
        } else {
            jQuery('#learny-course-review-area-' + courseId).addClass('learny-d-none');
            jQuery('#learny-course-review-updating-area-' + courseId).removeClass('learny-d-none');
        }
    }

    function validate() {
        return true;
    }

    function showResponse(response) {
        var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
        response = JSON.parse(response);
        if (response.status) {
            jQuery('.review-update-form').trigger('reset');
            toggleReviewSection(response.course_id);

            var starRatings = "";
            for (let index = 1; index < 6; index++) {
                if (index <= response.rating) {
                    starRatings = starRatings + "<i class='las la-star learny-rated'></i> ";
                } else {
                    starRatings = starRatings + "<i class='las la-star learny-not-rated'></i> ";
                }
            }

            jQuery("#learny-course-review-area-" + response.course_id).html(starRatings);
            jQuery("#review-for-course-" + response.course_id).text(response.review);

            learnyNotify(response.message, 'success');
        } else {
            learnyNotify(response.message, 'warning');
        }
    }
</script>