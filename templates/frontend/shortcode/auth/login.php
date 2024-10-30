<?php

use Learny\base\BaseController;

defined('ABSPATH') or die('You can not access it directly');
$student_dashboard_page = get_option('ly_dashboard_page', 0) ? esc_url_raw(get_permalink(get_option('ly_dashboard_page', 0))) : site_url();
?>
<div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12">
            <div class="card shadow-2-strong" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">

                    <h3 class="mb-5"><?php esc_html_e('Sign in', BaseController::$text_domain); ?></h3>

                    <form action="<?php echo site_url('/wp-login.php'); ?>" method="post" autocomplete="off">

                        <input type="hidden" value="<?php echo esc_attr(esc_url($student_dashboard_page)); ?>" name="redirect_to">
                        <input type="hidden" value="1" name="testcookie">

                        <div class="form-outline mb-4">
                            <input type="text" name="log" id="typeEmailX-2" class="form-control form-control-lg" placeholder="<?php esc_html_e('Email', BaseController::$text_domain); ?>" />
                        </div>

                        <div class="form-outline mb-4">
                            <input type="password" name="pwd" id="typePasswordX-2" class="form-control form-control-lg" placeholder="<?php esc_html_e('Password', BaseController::$text_domain); ?>" />
                        </div>

                        <!-- Checkbox -->
                        <div class="form-check d-flex justify-content-start mb-4">
                            <input name="rememberme" type="checkbox" id="rememberme" value="forever">
                            <label class="form-check-label ms-2" for="rememberme"> <?php esc_html_e('Remember password', BaseController::$text_domain); ?> </label>
                        </div>

                        <button class="btn btn-primary btn-lg btn-block" type="submit"><?php esc_html_e('Login', BaseController::$text_domain); ?></button>
                    </form>

                    <hr class="my-4">

                    <a href="<?php echo add_query_arg('action', 'registration'); ?>"><?php esc_html_e('Do not have an account', BaseController::$text_domain); ?>?</a>
                </div>
            </div>
        </div>
    </div>
</div>