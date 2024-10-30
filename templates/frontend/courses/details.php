<?php

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\modules\Enrolment;
use Learny\base\modules\Lesson;
use Learny\base\modules\Section;
use Learny\base\modules\Wishlist;
use Learny\base\Helper;

$baseController = new BaseController();
$total_curriculum = 0;
$checkout_page_id = esc_html(get_option('ly_checkout_page', false));

$is_wishlisted = false;
?>

<?php get_header(); ?>

<div class="learny-wrapper">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <?php
            if (is_user_logged_in()) {
                $wishlist_checker = Wishlist::is_wishlisted(get_the_ID());
                if ($wishlist_checker && count((array)$wishlist_checker)) {
                    $is_wishlisted = true;
                }
            }
            ?>

            <div class="learny-page-header">
                <h1>
                    <?php the_title(); ?>
                </h1>
                <div class="learny-course-description-instructor-section">
                    <?php esc_html_e('by', BaseController::$text_domain); ?>
                    <br>
                    <img src="<?php echo esc_url(get_avatar_url(get_the_author_meta('user_email'))); ?>" alt=""> <?php echo esc_html(get_the_author_meta('display_name')); ?>
                </div>
            </div>

            <div class="container mt-4">
                <div class="row mb-5">
                    <div class="col-md-8 learny-course-description">

                        <!-- COURSE DESCRIPTION -->
                        <div class="learny-course-description-section-title">
                            <?php esc_html_e('Course Description', BaseController::$text_domain); ?>
                        </div>
                        <div class="learny-course-description-section-details">
                            <?php the_content(); ?>
                        </div>

                        <!-- COURSE REQUIREMENTS -->
                        <div class="learny-course-description-section-title">
                            <?php esc_html_e('What Are The Requirements', BaseController::$text_domain); ?>?
                        </div>
                        <div class="learny-course-description-section-details">
                            <ul>
                                <div class="row">
                                    <?php
                                    $course_requirements = explode(',', esc_html(get_post_meta(get_the_ID(), 'ly_course_requirements', true)));
                                    foreach ($course_requirements as $course_requirement) : ?>
                                        <li class="col-5">
                                            <?php echo esc_html($course_requirement); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </div>
                            </ul>
                        </div>

                        <!-- COURSE OUTCOMES -->
                        <div class="learny-course-description-section-title">
                            <?php esc_html_e('What will I learn', BaseController::$text_domain); ?>?
                        </div>
                        <div class="learny-course-description-section-details">
                            <ul>
                                <div class="row">
                                    <?php
                                    $course_outcomes = explode(',', esc_html(get_post_meta(get_the_ID(), 'ly_course_outcomes', true)));
                                    foreach ($course_outcomes as $course_outcome) : ?>
                                        <li class="col-5">
                                            <?php echo esc_html($course_outcome); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </div>
                            </ul>
                        </div>

                        <!-- COURSE CURRICULUM -->
                        <div class="learny-course-description-section-title">
                            <?php esc_html_e('Course Curriculum', BaseController::$text_domain); ?>
                        </div>
                        <div class="learny-course-description-section-details">
                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                <?php
                                $learny_sections = Section::get_sections(get_the_ID());
                                $index = -1;
                                while ($learny_sections->have_posts()) :
                                    $learny_sections->the_post();
                                    $section_title = get_the_title();
                                    $index++;
                                ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header m-0" id="panelsStayOpen-heading-<?php echo esc_html(get_the_ID()); ?>">
                                            <button class="accordion-button <?php if ($index > 0) echo 'collapsed'; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#section-id-<?php echo esc_html(get_the_ID()); ?>" aria-expanded="<?php echo ($index == 0) ? 'true' : 'false'; ?>" aria-controls="section-id-<?php echo esc_html(get_the_ID()); ?>">
                                                <?php echo esc_html($section_title); ?>
                                            </button>
                                        </h2>
                                        <div id="section-id-<?php echo esc_html(get_the_ID()); ?>" class="accordion-collapse collapse <?php echo ($index == 0) ? 'show' : ''; ?>" aria-labelledby="panelsStayOpen-heading-<?php echo esc_html(get_the_ID()); ?>">
                                            <div class="accordion-body">
                                                <ul>
                                                    <?php
                                                    $learny_lessons = Lesson::get_lessons_by_section_id(get_the_ID());
                                                    $total_curriculum += $learny_lessons->found_posts;
                                                    while ($learny_lessons->have_posts()) :
                                                        $learny_lessons->the_post();
                                                        $lesson_title = get_the_title();
                                                        $ly_lesson_type = esc_html(get_post_meta(get_the_ID(), 'ly_lesson_type', true));
                                                        $icon_class_name = "las la-play-circle";
                                                        if ($ly_lesson_type == "note") {
                                                            $icon_class_name = "las la-book-reader";
                                                        } elseif ($ly_lesson_type == "iframe") {
                                                            $icon_class_name = "lab la-safari";
                                                        }
                                                    ?>
                                                        <li>
                                                            <div class="learny-course-details-curriculum-section">
                                                                <span class="learny-course-details-lesson-title">
                                                                    <i class="<?php echo esc_html($icon_class_name); ?>"></i>
                                                                    <?php echo esc_html($lesson_title); ?>
                                                                </span>
                                                                <span class="learny-course-details-lesson-duration">
                                                                    <?php if ($ly_lesson_type != "iframe" && $ly_lesson_type != "note") : ?>
                                                                        <i class="las la-clock"></i> <?php echo esc_html(get_post_meta(get_the_ID(), 'ly_video_duration', true)); ?>
                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </li>
                                                        <?php wp_reset_postdata(); ?>
                                                    <?php endwhile; ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <?php wp_reset_postdata(); ?>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card learny-course-summary">
                            <div class="learny-course-preview-section">
                                <img class="card-img-top" src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" alt="Card image cap">

                                <a href="javascipt:void(0)" data-bs-toggle="modal" data-bs-target="#coursePreviewModal">
                                    <img class="card-img-top learny-play-overlay" src="<?php echo esc_url($baseController->plugin_url . '/assets/public/images/icon-play.svg'); ?>" alt="Preview player">
                                </a>
                            </div>
                            <div class="card-body ">
                                <ul>
                                    <li>
                                        <i class="las la-code-branch"></i>
                                        <?php

                                        $term_obj_list = get_the_terms(get_the_ID(), 'learny_category');
                                        $terms_string = is_countable($term_obj_list) && count($term_obj_list) ? join(', ', wp_list_pluck($term_obj_list, 'name')) : '-';
                                        echo esc_html($terms_string);
                                        ?>
                                    </li>
                                    <li>
                                        <i class="las la-laptop-code"></i> <?php esc_html_e('Number of lessons', BaseController::$text_domain); ?> : <?php echo esc_html($total_curriculum); ?>
                                    </li>
                                    <li>
                                        <i class="las la-calendar"></i> <?php esc_html_e('Last Updated ', BaseController::$text_domain); ?> : <?php echo get_the_modified_date(); ?>
                                    </li>
                                    <li>
                                        <i class="las la-language"></i> <?php echo esc_html(get_post_meta(get_the_ID(), 'ly_course_language_made_in', true)); ?>
                                    </li>
                                    <li>
                                        <?php
                                        $number_of_enrolled_student = Enrolment::get_number_of_enrolled_student(esc_html(get_the_ID()));
                                        $number_of_enrolled_student = isset($number_of_enrolled_student) && is_array($number_of_enrolled_student) && count($number_of_enrolled_student) ? esc_html(count($number_of_enrolled_student)) : 0;
                                        ?>
                                        <i class="las la-graduation-cap"></i> <?php echo esc_html($number_of_enrolled_student); ?> <?php esc_html_e('Students Enrolled', BaseController::$text_domain); ?>
                                    </li>
                                </ul>

                                <div class="learny-course-price">
                                    <?php if (esc_html(get_post_meta(get_the_ID(), 'ly_is_free_course', true))) : ?>
                                        <?php esc_html_e('Free', BaseController::$text_domain); ?>
                                    <?php else : ?>
                                        $<?php echo esc_html(get_post_meta(get_the_ID(), 'ly_course_price', true)); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="learny-course-btns">
                                    <?php

                                    $checkout_page_url = $checkout_page_id ? get_permalink($checkout_page_id) . '?course-id=' . get_the_ID() : "javascript:void(0)";

                                    ?>
                                    <?php if (Helper::has_purchased(get_the_ID())) : ?>

                                        <?php
                                        // CHECK WHETHER A USER IS LOGGED IN
                                        if (is_user_logged_in()) {
                                            $student_dashboard_page = esc_html(get_option('ly_dashboard_page', 0)) ? esc_url_raw(get_permalink(get_option('ly_dashboard_page', 0))) : esc_url_raw(site_url());
                                        }
                                        ?>
                                        <button class="button w-100" onclick="redirectTo('<?php echo !empty($student_dashboard_page) ? esc_url_raw($student_dashboard_page) : esc_url_raw($checkout_page_url); ?>')">
                                            <?php esc_html_e('Start Learning', BaseController::$text_domain); ?>
                                        </button>
                                    <?php else : ?>
                                        <button class="button w-100" onclick="redirectTo('<?php echo esc_js(esc_url($checkout_page_url)); ?>')">
                                            <?php esc_html_e('Buy Now', BaseController::$text_domain); ?>
                                        </button>
                                    <?php endif; ?>


                                    <form action="<?php echo admin_url(); ?>admin-post.php" method="post" class='learny-form wishlist-edit-form' enctype='multipart/form-data' autocomplete="off">
                                        <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_wishlist'; ?>">
                                        <input type="hidden" name="task" value="edit_wishlist">
                                        <!-- kind of csrf token-->
                                        <input type="hidden" name="edit_wishlist_nonce" value="<?php echo esc_attr(wp_create_nonce('edit_wishlist_nonce')); ?>">
                                        <input type="hidden" name="wishlist_course_id" value="<?php echo esc_attr(get_the_ID()); ?>">
                                        <button class="button w-100" id="learny-wishlist-btn">
                                            <?php echo esc_html($is_wishlisted) ? esc_html__('Added To Wishlist', BaseController::$text_domain) : esc_html__('Add To Wishlist', BaseController::$text_domain); ?>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

<?php include 'preview.php'; ?>

<?php get_footer(); ?>

<script>
    "use strict";

    var options = {
        beforeSubmit: validate,
        success: showResponse,
        resetForm: false
    };

    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        jQuery('.wishlist-edit-form').on('submit', function() {
            jQuery(this).ajaxSubmit(options);
            return false;
        });
    }, false);

    function validate() {

        jQuery('#learny-wishlist-btn').prepend(spinner);
        return true;
    }

    function showResponse(response) {
        if (response) {
            response = JSON.parse(response);
            if (response.status) {
                jQuery('.wishlist-edit-form').trigger('reset');

                if (response.isAdded) {
                    jQuery('#learny-wishlist-btn').text('<?php esc_html_e('Added To Wishlist', BaseController::$text_domain) ?>');
                } else {
                    jQuery('#learny-wishlist-btn').text('<?php esc_html_e('Add To Wishlist', BaseController::$text_domain) ?>');
                }
                learnyNotify(response.message, 'success');
            } else {
                learnyNotify(response.message, 'warning');
            }
        } else {
            jQuery('#learny-wishlist-btn').text('<?php esc_html_e('Add To Wishlist', BaseController::$text_domain) ?>');
            learnyNotify('<?php esc_html_e('Make sure to login first', BaseController::$text_domain) ?>', 'warning');
        }
    }
</script>