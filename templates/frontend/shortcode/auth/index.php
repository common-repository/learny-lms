<?php

use Learny\base\BaseController;

defined('ABSPATH') or die('You can not access it directly');

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);
?>

<?php if ($action == "registration") : ?>
    <?php include 'registration.php'; ?>
<?php else : ?>
    <?php include 'login.php'; ?>
<?php endif; ?>
