<?php
namespace WP2S\Blocks\UserHandle;
use WP2S\Users\Controller as UserController;
$controller = new UserController();
$handle = $controller->get_user_handle();
?>
<div useBlockProps class="wp2s-user-handle">
    <?php echo esc_html($handle); ?>
</div>