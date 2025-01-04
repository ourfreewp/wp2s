<?php
namespace WP2S\Blocks\UserCapabilities;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$capabilities = $controller->get_user_capabilities();
?>
<div useBlockProps class="wp2s-user-capabilities">
    <?php echo esc_html(json_encode($capabilities)); ?>
</div>