<?php
namespace WP2S\Blocks\UserEmail;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$email = $controller->get_user_email();
?>
<div useBlockProps class="wp2s-user-email">
    <?php echo esc_html($email); ?>
</div>