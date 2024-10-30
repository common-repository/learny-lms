<?php
defined('ABSPATH') or die('You can not access this file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Lesson;
use Learny\base\modules\Payment;

$purchase_histories = Payment::get_user_wise_purchase_history(); ?>

<div class="row">
    <h5>
        <?php esc_html_e('Total Number Of Course', BaseController::$text_domain); ?> : <span class="learny-total-result-number"><?php echo count((array)$purchase_histories); ?></span>
    </h5>

    <?php foreach ($purchase_histories as $key => $purchase_history) :
        $the_query = new WP_Query(['post_type' => 'learny-courses', 'p' => $purchase_history->payment_course_id]);
        if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();
                $course_id = get_the_ID();
                $slug = esc_html(get_post_field('post_name', $course_id));
                $course_rating = esc_html(get_post_meta($course_id, 'ly_course_rating', true));
                $course_rating = $course_rating > 0 ? $course_rating : 0;
    ?>
                <div class="col-12">
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
                        </div>
                        <div class="col-md-4 learny-internal-course-details">
                            <div class="d-block">
                                <strong><?php esc_html_e('Paid Amount', BaseController::$text_domain); ?></strong> : <?php echo esc_html(Helper::currency($purchase_history->payment_amount)); ?>
                            </div>
                            <div class="d-block">
                                <strong><?php esc_html_e('Payment gateway'); ?></strong> : <?php echo ucfirst($purchase_history->payment_type); ?>
                            </div>
                            <div class="d-block">
                                <strong><?php esc_html_e('Payment Date'); ?></strong> : <?php echo date('D, d-M-Y', $purchase_history->payment_date); ?>
                            </div>
                            <div class="d-block mt-2">
                                <a href="<?php echo add_query_arg(array('action' => 'download-invoice', 'purchase-history-serial' => $purchase_history->payment_id)); ?>" class="btn btn-sm btn-primary btn-block">
                                    <i class="las la-print"></i> <?php echo esc_html_e('Download Invoice', BaseController::$text_domain); ?>
                                </a>
                            </div>
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
    <?php endforeach; ?>
</div>