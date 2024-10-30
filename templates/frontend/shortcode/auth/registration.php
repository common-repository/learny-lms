<?php

use Learny\base\BaseController;

defined('ABSPATH') or die('You can not access it directly');

use Learny\base\Helper;

$is_succeeded = filter_input(INPUT_GET, 'success', FILTER_SANITIZE_URL);
$error = filter_input(INPUT_GET, 'error', FILTER_SANITIZE_URL);


// HANDLES STUDENT REGISTRATION
if ($_POST) {
    if (BaseController::verify_nonce('add_student_nonce')) {

        // CHECK IF ANY FIELDS ARE EMPTY
        if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirm_password']) || empty($_POST['username'])) {
            wp_redirect(esc_url_raw(Helper::get_url("action=registration&success=false&error=empty-field")));
            exit;
        }

        // CHECK IF PASSWORD ARE MATCHED
        if (sanitize_text_field($_POST['password']) != sanitize_text_field($_POST['confirm_password'])) {
            wp_redirect(esc_url_raw(Helper::get_url("action=registration&success=false&error=mismatched-password")));
            exit;
        }

        // VALIDATING EMAIL
        if (!Helper::validate_email(sanitize_email($_POST['email']))) {
            wp_redirect(esc_url_raw(Helper::get_url("action=registration&success=false&error=invalid-email")));
            exit;
        }

        // Create a wp user of role 'student'
        $username     = sanitize_text_field($_POST['username']);
        $email        = sanitize_email($_POST['email']);
        $password     = sanitize_text_field($_POST['password']);
        $display_name = sanitize_text_field($_POST['fullname']);

        // Return if username or email already exists otherwise add user
        if (username_exists($username) || email_exists($email)) {
            wp_redirect(esc_url_raw(Helper::get_url("action=registration&success=false&error=duplication")));
            exit;
        } else {
            $user_id = wp_create_user($username, $password, $email);
            $user = get_user_by('id', $user_id);
            $user->remove_role('subscriber');
            $user->add_role(BaseController::$custom_roles['student']['role']);

            wp_update_user(array('ID' => $user_id, 'display_name' => $display_name));

            wp_redirect(esc_url_raw(Helper::get_url("action=registration&success=true")));
            exit;
        }
    } else {
        wp_redirect(esc_url_raw(Helper::get_url("action=registration&success=false&error=invalid-nonce")));
        exit;
    }
}
?>
<div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12">
            <div class="card shadow-2-strong" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">

                    <h3 class="mb-5"><?php esc_html_e('Registration', BaseController::$text_domain); ?></h3>


                    <?php if (!empty($is_succeeded)) : ?>
                        <?php if ($is_succeeded == "true") : ?>
                            <div class="alert alert-success" role="alert">
                                <?php esc_html_e('Registration has been done successfully', BaseController::$text_domain); ?>
                            </div>
                        <?php else : ?>
                            <?php if ($error == "duplication") : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php esc_html_e('Duplication Email or Username', BaseController::$text_domain); ?>
                                </div>
                            <?php elseif ($error == "invalid-email") : ?>
                                <div class="alert alert-warning" role="alert">
                                    <?php esc_html_e('Invalid Email Address', BaseController::$text_domain); ?>
                                </div>
                            <?php elseif ($error == "empty-field") : ?>
                                <div class="alert alert-warning" role="alert">
                                    <?php esc_html_e('Fields can not be empty', BaseController::$text_domain); ?>
                                </div>
                            <?php elseif ($error == "mismatched-password") : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php esc_html_e('Mismatched Password', BaseController::$text_domain); ?>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php esc_html_e('Invalid Form Submission', BaseController::$text_domain); ?>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>

                    <form action="" method="post">
                        <input type="hidden" name="action" value="<?php echo esc_attr(BaseController::$plugin_id) . '_student'; ?>">
                        <input type="hidden" name="task" value="add_student">
                        <input type="hidden" name="add_student_nonce" value="<?php echo wp_create_nonce('add_student_nonce'); ?>"> <!-- kind of csrf token-->

                        <div class="form-outline mb-4">
                            <input type="text" name="fullname" id="fullname" class="form-control form-control-lg" placeholder="<?php esc_html_e('Name', BaseController::$text_domain); ?>" />
                        </div>

                        <div class="form-outline mb-4">
                            <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="<?php esc_html_e('Unique Username', BaseController::$text_domain); ?>" />
                        </div>

                        <div class="form-outline mb-4">
                            <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="<?php esc_html_e('Email', BaseController::$text_domain); ?>" />
                        </div>

                        <div class="form-outline mb-4">
                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="<?php esc_html_e('Password', BaseController::$text_domain); ?>" />
                        </div>

                        <div class="form-outline mb-4">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control form-control-lg" placeholder="<?php esc_html_e('Confirm Password', BaseController::$text_domain); ?>" />
                        </div>

                        <button class="btn btn-primary btn-lg btn-block" type="submit"><?php esc_html_e('Sign Up', BaseController::$text_domain); ?></button>
                    </form>

                    <hr class="my-4">

                    <a href="<?php echo add_query_arg('action', 'login'); ?>"><?php esc_html_e('Back To Login', BaseController::$text_domain); ?>?</a>
                </div>
            </div>
        </div>
    </div>
</div>