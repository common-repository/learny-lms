<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\AjaxPosts;

// GET SELECTED CATEGORIES
if (AjaxPosts::$param1) {
    $selected_categories = AjaxPosts::$param1;
} else {
    if (filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL)) {
        $selected_categories = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL);
    } else {
        $selected_categories = "all";
    }
}

// GET SELECTED SUBCATEGORIES
if (AjaxPosts::$param2) {
    $selected_subcategories = AjaxPosts::$param2;
} else {
    if (filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL)) {
        $selected_subcategories = filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL);
    } else {
        $selected_subcategories = "all";
    }
}

// GET SELECTED PRICES
if (AjaxPosts::$param3) {
    $selected_prices = AjaxPosts::$param3;
} else {
    if (filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL)) {
        $selected_prices = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL);
    } else {
        $selected_prices = "all";
    }
}

// GET SELECTED LEVELS
if (AjaxPosts::$param4) {
    $selected_levels = AjaxPosts::$param4;
} else {
    if (filter_input(INPUT_GET, 'level', FILTER_SANITIZE_URL)) {
        $selected_levels = filter_input(INPUT_GET, 'level', FILTER_SANITIZE_URL);
    } else {
        $selected_levels = "all";
    }
}

// GET SELECTED STATUS
if (AjaxPosts::$param5) {
    $selected_status = AjaxPosts::$param5;
} else {
    if (filter_input(INPUT_GET, 'status', FILTER_SANITIZE_URL)) {
        $selected_status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_URL);
    } else {
        $selected_status = "all";
    }
}

// GET SELECTED RATINGS
if (AjaxPosts::$param6) {
    $selected_ratings = AjaxPosts::$param6;
} else {
    if (filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_URL)) {
        $selected_ratings = filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_URL);
    } else {
        $selected_ratings = "all";
    }
}


// GET SELECTED SORT
if (AjaxPosts::$param7) {
    $selected_sort_by = AjaxPosts::$param7;
} else {
    if (filter_input(INPUT_GET, 'sort-by', FILTER_SANITIZE_URL)) {
        $selected_sort_by = filter_input(INPUT_GET, 'sort-by', FILTER_SANITIZE_URL);
    } else {
        $selected_sort_by = "latest";
    }
}

if ($selected_sort_by == "popular") {

    $order_by_meta_key = "ly_course_rating";
    $order_by = "meta_value";
    $order_by_meta_value = "DESC";
} elseif ($selected_sort_by == "price-high") {
    $order_by_meta_key = "ly_course_price";
    $order_by = "meta_value";
    $order_by_meta_value = "DESC";
} elseif ($selected_sort_by == "price-low") {
    $order_by_meta_key = "ly_course_price";
    $order_by = "meta_value";
    $order_by_meta_value = "ASC";
} else {
    $order_by_meta_key = null;
    $order_by = "post_date";
    $order_by_meta_value = "DESC";
}




// GET SEARCH STRING
if (AjaxPosts::$param8) {
    $keywords = AjaxPosts::$param8;
} else {
    if (filter_input(INPUT_GET, 'search', FILTER_SANITIZE_URL)) {
        $keywords = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_URL);
    } else {
        $keywords = null;
    }
}

$keywords = !empty($keywords) ? $keywords : "";

$selected_categories = explode('--', $selected_categories);
$selected_subcategories = explode('--', $selected_subcategories);
$selected_prices = explode('--', $selected_prices);
$selected_levels = explode('--', $selected_levels);
$selected_ratings = explode('--', $selected_ratings);

$category_taxonomy_arg = array();
if ($selected_categories[0] !== "all") {
    foreach ($selected_categories as $key => $selected_category) {
        if (!in_array($selected_category, $category_taxonomy_arg)) {
            array_push($category_taxonomy_arg, $selected_category);
        }
    }
}

if ($selected_subcategories[0] !== "all") {
    foreach ($selected_subcategories as $key => $selected_subcategory) {
        if (!in_array($selected_subcategory, $category_taxonomy_arg)) {
            array_push($category_taxonomy_arg, $selected_subcategory);
        }
    }
}

$level_meta_arg = array();
if ($selected_levels[0] !== "all") {
    $array = [];
    foreach ($selected_levels as $key => $selected_level) {
        if (!in_array($selected_level, $array)) {
            array_push($array, $selected_level);
        }
    }
    $level_meta_arg = array(
        'key' => 'ly_course_difficulty_level',
        'value' => $array,
        'compare' => 'IN'
    );
}

$status_meta_arg = array();
if ($selected_status !== "all") {
    $status_meta_arg = array(
        'key' => 'ly_is_trendy_course',
        'value' => 1,
    );
}

$rating_meta_arg = array();
if ($selected_ratings[0] !== "all") {
    $array = [];
    foreach ($selected_ratings as $key => $selected_rating) {
        if (!in_array($selected_rating, $array)) {
            array_push($array, $selected_rating);
        }
    }
    $rating_meta_arg = array(
        'key' => 'ly_course_rating',
        'value' => $array,
        'compare' => 'IN'
    );
}



$is_free_meta = null;
$price_meta = array();
if (count($selected_prices) == 1 && $selected_prices[0] != "all") {
    $is_free_meta = $selected_prices[0] == "free" ? 1 : 0;

    $price_meta = array(
        'key' => ($is_free_meta == 0 || $is_free_meta == 1) ? 'ly_is_free_course' : null,
        'value' => ($is_free_meta == 0 || $is_free_meta == 1) ? $is_free_meta : null,
    );
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
if (count($category_taxonomy_arg)) {
    $args = array(
        'post_type' => 'learny-courses',
        's' => $keywords,
        'posts_per_page' => esc_html(get_option('posts_per_page')),
        'paged' => $paged,
        'tax_query' => array(
            array(
                'taxonomy' => 'learny_category',
                'field' => 'slug',
                'terms' => $category_taxonomy_arg
            )
        ),
        'meta_query' => array(
            $price_meta,
            $level_meta_arg,
            $status_meta_arg,
            $rating_meta_arg
        ),
        'meta_key' => $order_by_meta_key,
        'orderby' => $order_by,
        'order' => $order_by_meta_value
    );
} else {
    $args = array(
        'post_type' => 'learny-courses',
        's' => $keywords,
        'posts_per_page' => esc_html(get_option('posts_per_page')),
        'paged' => $paged,
        'meta_query' => array(
            $price_meta,
            $level_meta_arg,
            $status_meta_arg,
            $rating_meta_arg
        ),
        'meta_key' => $order_by_meta_key,
        'orderby' => $order_by,
        'order' => $order_by_meta_value
    );
}


$the_query = new WP_Query($args);
?>

<div class="row">
    <?php if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="learny-course-card">
                    <div class="learny-course-card-img">
                        <a href="<?php echo esc_url(get_the_permalink()); ?>">
                            <img src="<?php echo esc_url(get_the_post_thumbnail_url()); ?>" class="learny-main" alt="">
                        </a>
                    </div>
                    <div class="learny-course-card-content">
                        <h4><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h4>
                        <div class="learny-course-instructor">
                            <img src="<?php echo esc_url(get_avatar_url(get_the_author_meta('user_email'))); ?>" alt="">
                            <span>
                                <?php echo esc_html(get_the_author_meta('display_name')); ?>
                            </span>
                        </div>
                    </div>
                    <div class="learny-course-card-footer">
                        <div class="learny-course-rating">
                            <?php for ($i = 1; $i < 6; $i++) : ?>
                                <?php if ($i <= esc_html(get_post_meta(get_the_ID(), 'ly_course_rating', true))) : ?>
                                    <i class="las la-star learny-rated"></i>
                                <?php else : ?>
                                    <i class="las la-star learny-not-rated"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                        <div class="learny-coures-price">
                            <h6>
                                <?php if (get_post_meta(get_the_ID(), 'ly_is_free_course', true)) : ?>
                                    <?php esc_html_e('Free', BaseController::$text_domain); ?>
                                <?php else : ?>
                                    <?php echo Helper::currency(esc_html(get_post_meta(get_the_ID(), 'ly_course_price', true))); ?>
                                <?php endif; ?>
                            </h6>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <div id="pagination" class="text-start mt-2">
        <?php
        $big = rand(9999999, 999999999); // need an unlikely integer

        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $the_query->max_num_pages
        ));
        ?>
    </div>

</div>