<?php
namespace WP2S\Blocks\UserAuthProviders;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$providers = $controller->get_user_auth_providers();
?>
<div useBlockProps class="wp2s-user-auth-providers">
    <?php echo esc_html(json_encode($providers)); ?>
</div>