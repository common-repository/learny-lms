<?php

defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
use Learny\base\modules\Category;

$parent_categories = Category::get_parent_categories();


// GET SELECTED CATEGORIES
$selected_categories_slugs = filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'category', FILTER_SANITIZE_URL)) : array();
$selected_subcategories_slugs = filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'subcategory', FILTER_SANITIZE_URL)) : array();
$selected_prices_slugs = filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'price', FILTER_SANITIZE_URL)) : array();
$selected_levels_slugs = filter_input(INPUT_GET, 'level', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'level', FILTER_SANITIZE_URL)) : array();
$selected_status_slugs = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'status', FILTER_SANITIZE_URL)) : array();
$selected_ratings = filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_URL) ? explode("--", filter_input(INPUT_GET, 'rating', FILTER_SANITIZE_URL)) : array();
$search_string = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_URL) ? filter_input(INPUT_GET, 'search', FILTER_SANITIZE_URL) : "";
$selected_sort = filter_input(INPUT_GET, 'sort-by', FILTER_SANITIZE_URL) ? filter_input(INPUT_GET, 'sort-by', FILTER_SANITIZE_URL) : "latest";
?>

<?php get_header(); ?>

<div class="learny-wrapper">

    <div class="learny-page-header">
        <h1>
            <?php esc_html_e('All Courses', BaseController::$text_domain); ?>
        </h1>
    </div>

    <div class="container-fluid">
        <div class="row justify-content-end mb-4">
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="learny-available-sortings" class="col-sm-3 col-form-label text-end"><?php esc_html_e('Sort By', BaseController::$text_domain); ?></label>
                    <div class="col-sm-9">
                        <select class="learny-available-sortings learny-filter-attribute form-control" name="sort" id="learny-available-sortings">
                            <option value="latest" <?php if ($selected_sort == "latest") echo "selected"; ?>><?php esc_html_e('Latest', BaseController::$text_domain); ?></option>
                            <option value="popular" <?php if ($selected_sort == "popular") echo "selected"; ?>><?php esc_html_e('Popular', BaseController::$text_domain); ?></option>
                            <option value="price-high" <?php if ($selected_sort == "price-high") echo "selected"; ?>><?php esc_html_e('Price High To Low', BaseController::$text_domain); ?></option>
                            <option value="price-low" <?php if ($selected_sort == "price-low") echo "selected"; ?>><?php esc_html_e('Price Low To High', BaseController::$text_domain); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <div class="col-lg-3">
                <form class="form-inline mb-2" method="GET">
                    <div class="learny-custom-search-from-group">
                        <input type="text" class="learny-search-input" name="search" placeholder="<?php echo esc_html_e("Course Name", BaseController::$text_domain); ?>" value="<?php echo esc_attr($search_string); ?>">
                        <button class="learny-custom-search-botton button" type="submit"><?php esc_html_e('Search', BaseController::$text_domain); ?></button>
                    </div>
                </form>
                <div class="accordion" id="accordionExample">

                    <input type="hidden" name="learny-filter-attirbute" class="learny-available-sortings" value="latest">

                    <div class="accordion-item">
                        <h2 class="accordion-header m-0" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                <?php esc_html_e('Category', BaseController::$text_domain); ?>
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="m-0 p-0">
                                    <?php foreach ($parent_categories as $key => $parent_category) : ?>
                                        <li>
                                            <input type="checkbox" id="<?php echo esc_html($parent_category->term_id); ?>" name="filter-cateogries" value="<?php echo esc_attr($parent_category->slug); ?>" class="learny-available-categories learny-filter-attribute" <?php if (in_array($parent_category->slug, $selected_categories_slugs)) echo "checked"; ?>>
                                            <label for="<?php echo esc_html($parent_category->term_id); ?>"><?php echo esc_html($parent_category->name); ?></label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header m-0" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                <?php esc_html_e('Sub Category', BaseController::$text_domain); ?>
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="m-0 p-0">
                                    <?php foreach ($parent_categories as $key => $parent_category) :
                                        $sub_categories = Category::get_subcategories($parent_category->term_id);
                                    ?>
                                        <?php foreach ($sub_categories as $key => $sub_category) : ?>
                                            <li>
                                                <input type="checkbox" id="<?php echo esc_html($sub_category->term_id); ?>" name="filter-cateogries" value="<?php echo esc_attr($sub_category->slug); ?>" class="learny-available-subcategories learny-filter-attribute" <?php if (in_array($sub_category->slug, $selected_subcategories_slugs)) echo "checked"; ?>>
                                                <label for="<?php echo esc_html($sub_category->term_id); ?>"><?php echo esc_html($sub_category->name); ?></label>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header m-0" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                <?php esc_html_e('Price', BaseController::$text_domain); ?>
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="m-0 p-0">
                                    <li>
                                        <input type="checkbox" id="free" name="filter-price" class="learny-available-prices learny-filter-attribute" value="free" <?php if (in_array("free", $selected_prices_slugs)) echo "checked"; ?>>
                                        <label for="free"><?php esc_html_e('Free', BaseController::$text_domain); ?></label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="paid" name="filter-price" class="learny-available-prices learny-filter-attribute" value="paid" <?php if (in_array("paid", $selected_prices_slugs)) echo "checked"; ?>>
                                        <label for="paid"><?php esc_html_e('Paid', BaseController::$text_domain); ?></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header m-0" id="filter-four">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapaseFour" aria-expanded="false" aria-controls="collapaseFour">
                                <?php esc_html_e('Level', BaseController::$text_domain); ?>
                            </button>
                        </h2>
                        <div id="collapaseFour" class="accordion-collapse collapse" aria-labelledby="filter-four" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="m-0 p-0">
                                    <li>
                                        <input type="checkbox" id="beginner" name="filter-level" class="learny-available-levels learny-filter-attribute" value="beginner" <?php if (in_array("free", $selected_levels_slugs)) echo "checked"; ?>>
                                        <label for="beginner"><?php esc_html_e('Beginner', BaseController::$text_domain); ?></label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="advanced" name="filter-level" class="learny-available-levels learny-filter-attribute" value="advanced" <?php if (in_array("advanced", $selected_levels_slugs)) echo "checked"; ?>>
                                        <label for="advanced"><?php esc_html_e('Advanced', BaseController::$text_domain); ?></label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="intermediate" name="filter-level" class="learny-available-levels learny-filter-attribute" value="intermediate" <?php if (in_array("intermediate", $selected_levels_slugs)) echo "checked"; ?>>
                                        <label for="intermediate"><?php esc_html_e('Intermediate', BaseController::$text_domain); ?></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header m-0" id="filter-five">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapaseFive" aria-expanded="false" aria-controls="collapaseFive">
                                <?php esc_html_e('Status', BaseController::$text_domain); ?>
                            </button>
                        </h2>
                        <div id="collapaseFive" class="accordion-collapse collapse" aria-labelledby="filter-five" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="m-0 p-0">
                                    <li>
                                        <input type="checkbox" id="trendy" name="filter-level" class="learny-available-status learny-filter-attribute" value="trendy" <?php if (in_array("trendy", $selected_status_slugs)) echo "checked"; ?>>
                                        <label for="trendy"><?php esc_html_e('Trendy Course', BaseController::$text_domain); ?></label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header m-0" id="filter-six">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapaseSix" aria-expanded="false" aria-controls="collapaseSix">
                                <?php esc_html_e('Ratings', BaseController::$text_domain); ?>
                            </button>
                        </h2>
                        <div id="collapaseSix" class="accordion-collapse collapse" aria-labelledby="filter-six" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <ul class="m-0 p-0">
                                    <li>
                                        <input type="checkbox" id="5" name="filter-level" class="learny-available-ratings learny-filter-attribute" value="5" <?php if (in_array("5", $selected_ratings)) echo "checked"; ?>>
                                        <label for="5">
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="4" name="filter-level" class="learny-available-ratings learny-filter-attribute" value="4" <?php if (in_array("4", $selected_ratings)) echo "checked"; ?>>
                                        <label for="4">
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="3" name="filter-level" class="learny-available-ratings learny-filter-attribute" value="3" <?php if (in_array("3", $selected_ratings)) echo "checked"; ?>>
                                        <label for="3">
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="2" name="filter-level" class="learny-available-ratings learny-filter-attribute" value="2" <?php if (in_array("2", $selected_ratings)) echo "checked"; ?>>
                                        <label for="2">
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="1" name="filter-level" class="learny-available-ratings learny-filter-attribute" value="1" <?php if (in_array("1", $selected_ratings)) echo "checked"; ?>>
                                        <label for="1">
                                            <i class="las la-star learny-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                            <i class="las la-star learny-not-rated"></i>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="learny-preloader"></div>
                <div class="learny-course-content learny-page-content" id="learny-course-content">
                    <?php include 'list.php'; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>

<script>
    "use strict";
    // WRITE CODE AFTER DOM LOADED
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {

            jQuery(".learny-preloader").hide();
            jQuery('.learny-page-content').removeClass('learny-d-none');

            jQuery('.learny-filter-attribute').on("change", function() {
                filterCourses();
            });
        }, 500);
    }, false);

    function filterCourses() {
        let data = get_url();
        learnyMakeAjaxCall('templates/frontend/courses/list', 'learny-course-content', data['selectedCategories'], data['selectedSubCategories'], data['selectedPrices'], data['selectedLevels'], data['selectedStatus'], data['selectedRatings'], data['selectedSortBy'], data['searchString']);
    }

    function get_url() {
        var urlPrefix = '<?php echo esc_js(add_query_arg('filter', 'course')); ?>';
        var urlSuffix = "";
        var selectedCategories = "all";
        var selectedSubCategories = "all";
        var selectedPrices = "all";
        var selectedLevels = "all";
        var selectedStatus = "all";
        var selectedRatings = "all";

        var selectedSortBy = "latest";
        var searchString = "none";

        // Get selected category
        jQuery('.learny-available-categories:checked').each(function() {
            if (selectedCategories === "all") {
                selectedCategories = jQuery(this).attr('value');
            } else {
                selectedCategories = selectedCategories + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected subcategory
        jQuery('.learny-available-subcategories:checked').each(function() {
            if (selectedSubCategories === "all") {
                selectedSubCategories = jQuery(this).attr('value');
            } else {
                selectedSubCategories = selectedSubCategories + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected price
        jQuery('.learny-available-prices:checked').each(function() {
            if (selectedPrices === "all") {
                selectedPrices = jQuery(this).attr('value');
            } else {
                selectedPrices = selectedPrices + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected level
        jQuery('.learny-available-levels:checked').each(function() {
            if (selectedLevels === "all") {
                selectedLevels = jQuery(this).attr('value');
            } else {
                selectedLevels = selectedLevels + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected status
        jQuery('.learny-available-status:checked').each(function() {
            if (selectedStatus === "all") {
                selectedStatus = jQuery(this).attr('value');
            } else {
                selectedStatus = selectedStatus + "--" + jQuery(this).attr('value');
            }
        });

        // Get selected ratings
        jQuery('.learny-available-ratings:checked').each(function() {
            if (selectedRatings === "all") {
                selectedRatings = jQuery(this).attr('value');
            } else {
                selectedRatings = selectedRatings + "--" + jQuery(this).attr('value');
            }
        });

        // GET SEARCH STRING
        searchString = '<?php echo esc_js($search_string); ?>';

        // Get selected sort
        selectedSortBy = jQuery('.learny-available-sortings').val();


        urlSuffix = "&category=" + selectedCategories + "&subcategory=" + selectedSubCategories + "&price=" + selectedPrices + "&level=" + selectedLevels + "&status=" + selectedStatus + "&rating=" + selectedRatings + "&search=" + searchString + "&sort-by=" + selectedSortBy;

        var url = urlPrefix + urlSuffix;
        window.history.pushState("string", '<?php esc_html_e("Course Filter", BaseController::$text_domain); ?>', url);

        let returningArray = [];
        returningArray['selectedCategories'] = selectedCategories;
        returningArray['selectedSubCategories'] = selectedSubCategories;
        returningArray['selectedPrices'] = selectedPrices;
        returningArray['selectedLevels'] = selectedLevels;
        returningArray['selectedStatus'] = selectedStatus;
        returningArray['selectedRatings'] = selectedRatings;
        returningArray['selectedSortBy'] = selectedSortBy;
        returningArray['searchString'] = searchString;
        returningArray['url'] = url;
        return returningArray;
    }
</script>