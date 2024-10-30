<?php

use Learny\base\BaseController;

defined('ABSPATH') or die('You can not access this file directly');

?>
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link " aria-current="page" href="javascript:void(0)"><?php esc_html_e('Course Forum', BaseController::$text_domain); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="javascript:void(0)"><?php esc_html_e('Course Certificate', BaseController::$text_domain); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="javascript:void(0)"><?php esc_html_e('Notices', BaseController::$text_domain); ?></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="javascript:void(0)"><?php esc_html_e('Recent Events', BaseController::$text_domain); ?></a>
    </li>
</ul>