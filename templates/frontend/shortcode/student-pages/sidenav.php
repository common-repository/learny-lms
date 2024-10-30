<?php
defined('ABSPATH') or die('You can not access this file directly');

use Learny\base\BaseController;
use Learny\base\Helper;
?>

<div class="d-flex flex-sm-column flex-row flex-grow-1 align-items-center align-items-sm-start pt-2 text-dark">
    <a href="javascript:void(0)" class="d-flex align-items-center mb-md-0 me-md-auto text-dark text-decoration-none px-3 learny-student-sidebar-header">
        <span class="fs-4">L<span class="d-none d-sm-inline">earny</span></span>
    </a>
    <ul class="nav nav-pills flex-sm-column flex-row flex-nowrap flex-shrink-1 flex-sm-grow-0 flex-grow-1 mb-sm-auto mb-0 justify-content-center align-items-center align-items-sm-start w-100" id="menu">
        <?php foreach ($pages as $page_title => $page_icon) : ?>
            <li class="nav-item w-100 <?php if ($current_page == $page_title) echo 'active'; ?>">
                <a href="<?php echo Helper::get_url('page-contains=' . $page_title); ?>" class="nav-link px-sm-0 px-2">
                    <i class="<?php echo esc_attr($pages[$page_title]); ?> fs-4"></i><span class="ms-1 d-none d-sm-inline"><?php echo ucwords(esc_html__(str_replace('-', ' ', $page_title), BaseController::$text_domain)); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>