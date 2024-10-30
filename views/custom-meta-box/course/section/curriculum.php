<?php
defined('ABSPATH') or die('You can not access the file directly');

use Learny\base\BaseController;
use Learny\base\Helper;

include Helper::learnyModal();

$course_id = isset($_GET['post']) ? sanitize_text_field($_GET['post']) : 0;
?>
<div id="learny-section-list-area">
    <?php if ($course_id == 0) : ?>
        <?php esc_html_e('After creating the course you will be able to manage the course curriculum', BaseController::$text_domain); ?>.
    <?php endif; ?>
</div>

<script>
    "use strict";

    jQuery(document).ready(function($) {
        showSectionList();
    });
</script>