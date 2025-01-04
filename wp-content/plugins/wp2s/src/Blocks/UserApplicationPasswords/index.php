<?php
namespace WP2S\Blocks\UserApplicationPasswords;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$passwords = $controller->get_user_application_passwords();
?>
<div useBlockProps class="wp2s-user-application-passwords">
    <?php echo esc_html(json_encode($passwords)); ?>
</div>